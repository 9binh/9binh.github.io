<?php

/* <meta http-equiv="content-type" content="text/html;charset=utf-8"> */

error_reporting (0);

define ('JP_ALLOWED', 1);

require_once ('../../sysop/conf.php');

define ('ALL_TYPE', '--all--');

global $my_param;

process_my_form ();
display_my_form ();

function process_my_form () {
	global $jp_glob, $my_param;

	$my_param['offset'] = (0 < trim ($_POST['Offset']) ) ? trim ($_POST['Offset']) : 0; /* default is from the starting page */
	$my_param['limit'] = (int) trim ($_POST['Limit']);
	if ((20 != $my_param['limit']) && (30 != $my_param['limit']) &&(40 != $my_param['limit']) && (50 != $my_param['limit']))
			$my_param['limit'] = 30; /* default value */
	$my_param['offset'] = $my_param['limit'] * (int)($my_param['offset'] / $my_param['limit']);

	$t = trim ($_POST['Type']);
	foreach ($jp_glob['article_type'] as $type => $desc) {
		if ($t == $type):
			$my_param['type'] = $type;
			break;
		endif;
		$my_param['type'] = ALL_TYPE;
	}

	$my_param['article_types'] = $my_param['article_type_list'] = "";
	foreach ($jp_glob['article_type'] as $type => $desc) {
		$my_param['article_type_list'] .= ($my_param['type'] == $type) ? ("<option value=\"{$type}\" selected>{$type}: {$desc}</option>") : ("<option value=\"{$type}\">{$type}: {$desc}</option>");
		$my_param['article_types'] .= '<b>' . $type . '</b> <span class=fade>' . $desc . '</span> ';
	}

} /* process_my_form */

function display_my_form () {
	global $jp_glob, $my_param;

	$my_form = new myDocument ('my_form.html');

	$pairs[] = array ('%%table_row%%' =>
			"<td class=th0>Ngày</td>"
			. "<td class=th0>Loại</td>"
			. "<td class=th0>Tiêu đề</td>"
		);

	($link = mysql_connect($jp_glob['mysql']['host'], $jp_glob['mysql']['user'], $jp_glob['mysql']['password']))
			or die ("display_my_form(): mysql_connect() Fail.\n");

	$table_name =  $jp_glob['mysql']['tbl_prefix'] . $jp_glob['mysql']['alist_tbl'];
	$condition = "WHERE 0=LOCATE('-', `Type`)";
	if (ALL_TYPE != $my_param['type']) $condition .= " AND 0<LOCATE('{$my_param['type']}', `Type`)";

	/* count all number of rows */
	$query = "SELECT COUNT(*) AS `Count` FROM `{$jp_glob['mysql']['database']}`.`{$table_name}` {$condition};";

	($result = mysql_query($query))
			or die ("display_my_form(): mysql_query(_SELECT_) Fail.\n");

	$row = mysql_fetch_assoc($result);
	$my_param['countall_item'] = $row['Count'];

	mysql_free_result($result);

	/* retrive table */
	$query = "SELECT * FROM `{$jp_glob['mysql']['database']}`.`{$table_name}` {$condition} ORDER BY (`Date`) DESC LIMIT {$my_param['offset']}, {$my_param['limit']};";

	($result = mysql_query($query))
			or die ("display_my_form(): mysql_query(_SELECT_) Fail.\n");

	for ($line_count = 0; $row = mysql_fetch_assoc($result); ):
		$line_count++;
		$style = ($line_count == 2 * (int) ($line_count / 2)) ? ('class=i2') : ('class=i1');
		$pairs[] = array ('%%table_row%%' =>
				"<td {$style} nowrap>" . substr ($row['Date'], 0, 10) . "</td>"
				. "<td {$style}>{$row['Type']}</td>"
				. "<td {$style}><a href=\"../../{$row['Link']}\">{$row['Title']}</a> <span class=\"fade\">{$row['Comment']}</span></td>"
			);
	endfor;

	mysql_free_result($result);

	mysql_close($link);

	$my_param['last_page_offset'] = 0;
	$my_param['more_offset'] = "";
	for ($i = 0; $i < $my_param['countall_item']; $i += $my_param['limit']):
		$my_param['last_page_offset'] = $i;
		if (($my_param['offset'] <= $i ) && ($i < $my_param['offset'] + $my_param['limit'])):
			$my_param['more_offset'] .= "<option value=\"{$i}\" selected>" . ($i / $my_param['limit'] + 1) . "</option>";
		else:
			$my_param['more_offset'] .= "<option value=\"{$i}\">" . ($i / $my_param['limit'] + 1) . "</option>";
		endif;
	endfor;

	$my_param['more_limit'] = "";
	for ($i = 10; $i <= 50; $i += 10):
		if ($i == $my_param['limit']):
			$my_param['more_limit'] .= "<option value=\"{$i}\" selected>{$i}</option>";
		else:
			$my_param['more_limit'] .= "<option value=\"{$i}\">{$i}</option>";
		endif;
	endfor;

	$my_form->copycat ('<!--STARTS1-->', '<!--ENDS1-->', $pairs);

	$my_param['prev_page'] = (0 < $my_param['offset']) ? "<a href=\"javascript:jp_prevpage()\">Trước</a>" : "<span class=fade>Trước</span>";
	$my_param['next_page'] = (($my_param['offset'] + $line_count) >= $my_param['countall_item']) ? "<span class=fade>Sau</span>" : "<a href=\"javascript:jp_nextpage()\">Sau</a>";

	$my_form->substitute (array (
			'%%sys_date%%' => date ('Y-m-d • H:i:s'),
			'%%my_message%%' => $my_param['message'],
			'%%prev_page%%' => $my_param['prev_page'],
			'%%next_page%%' => $my_param['next_page'],
			'%%limit%%' => $my_param['limit'],
			'%%countall_item%%' => $my_param['countall_item'],
			'%%itemcount%%' => $line_count,
			'%%article_types%%'=> $my_param['article_types'],
			'%%offset%%' => $my_param['offset'],
			'%%type%%' => $my_param['type'],
			'%%more_types%%' => $my_param['article_type_list'],
			'%%last_page_offset%%' => $my_param['last_page_offset'],
			'%%more_offset%%' => $my_param['more_offset'],
			'%%more_limit%%' => $my_param['more_limit']
		));

	$my_form->writeme ();

} /* display_my_form */

?>