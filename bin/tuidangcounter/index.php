<?php
/* <meta http-equiv="Content-Type" content="text/html; charset=gb2312"> */

define ('JP_ALLOWED', 1);
function jp_loadpage ($filnam) {
	$data = '';
	foreach (file ($filnam) as $row) if (0 < strlen ($row)) $data .= trim ($row) . ' ';
	return $data;
} /* jp_loadpage */


error_reporting (0);
 
$jp_param = array (
	'java_header' => 'Content-type: application/x-javascript',
	'counter_url' => 'http://tuidang.epochtimes.com/stat/statics/',
	'total_start' => '\<TOTAL\>',
	'total_end' => '\<\/TOTAL\>',
	'today_start' => '\<TODAY\>',
	'today_end' => '\<\/TODAY\>',
	'yesterday_start' => '\<YESTERDAY\>',
	'yesterday_end' => '\<\/YESTERDAY\>',
	'thisweek_start' => '\<THISWEEK\>',
	'thisweek_end' => '\<\/THISWEEK\>',
	'thismonth_start' => '\<THISMONTH\>',
	'thismonth_end' => '\<\/THISMONTH\>',
	'lastupdate_start' => '\<LASTUPDATE\>',
	'lastupdate_end' => '\<\/LASTUPDATE\>',	 
	'my_counter_file' => 'my_counter.txt',
	'time_out' => 600, /* equal 10 minutes */
	'counter_function' => "function jp_tuidang_counter(i){switch(i){case 1: document.write('%%counter1%%');break;case 2: document.write('%%counter2%%');break;case 3: document.write('%%counter3%%');break;case 4: document.write('%%counter4%%');break;case 5: document.write('%%counter5%%');break;default: document.write('%%counter0%%');}}"
);

if ((false == file_exists ($jp_param['my_counter_file']) or ($jp_param['time_out'] < (time() - filemtime($jp_param['my_counter_file']))))):
	/* Load all the home page from tuidang website */
	$data = jp_loadpage ($jp_param ['counter_url']);

	$total = strtr (preg_replace ("/^.*?{$jp_param ['total_start']}(.*?){$jp_param ['total_end']}.*$/si", "\\1", $data), array (',' => '.'));
	$today = strtr (preg_replace ("/^.*?{$jp_param ['today_start']}(.*?){$jp_param ['today_end']}.*$/si", "\\1", $data), array (',' => '.'));
	$yesterday = strtr (preg_replace ("/^.*?{$jp_param ['yesterday_start']}(.*?){$jp_param ['yesterday_end']}.*$/si", "\\1", $data), array (',' => '.'));
	$thisweek = strtr (preg_replace ("/^.*?{$jp_param ['thisweek_start']}(.*?){$jp_param ['thisweek_end']}.*$/si", "\\1", $data), array (',' => '.'));
	$thismonth = strtr (preg_replace ("/^.*?{$jp_param ['thismonth_start']}(.*?){$jp_param ['thismonth_end']}.*$/si", "\\1", $data), array (',' => '.'));
	$lastupdate = strtr (preg_replace ("/^.*?{$jp_param ['lastupdate_start']}(.*?){$jp_param ['lastupdate_end']}.*$/si", "\\1", $data), array (',' => '.'));  
	if (strlen($total)>2 && strlen($today)>2 && strlen($yesterday)>2 && strlen($thisweek)>2 && strlen($thismonth)>2): /* Looks like I get the number correctly */
		if ($fp = fopen ($jp_param['my_counter_file'], 'w')):
			fwrite ($fp, strtr ($jp_param['counter_function'], array ('%%counter0%%' => $total,'%%counter1%%' => $thismonth,'%%counter2%%' => $thisweek,'%%counter3%%' => $yesterday,'%%counter4%%' => $today,'%%counter5%%' => $lastupdate)));
			fclose ($fp);
		endif;
	endif;
endif;

header ($jp_param['java_header']);
readfile ($jp_param['my_counter_file']);

?>
