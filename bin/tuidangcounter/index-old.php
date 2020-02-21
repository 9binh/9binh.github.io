<?php
/* <meta http-equiv="Content-Type" content="text/html; charset=gb2312"> */

define ('JP_ALLOWED', 1);
require_once ('../../sysop/conf.php');

error_reporting (0);

$jp_param = array (
	'java_header' => 'Content-type: application/x-javascript',
	'counter_url' => 'http://tuidang.dajiyuan.com/',
	'counter_start' => '人数：\<font color=red\>',
	'counter_end' => '\<\/font\>',
	'my_counter_file' => 'my_counter.txt',
	'time_out' => 600, /* equal 10 minutes */
	'counter_function' => "function jp_tuidang_counter(i){switch(i){case 1: document.write('%%counter1%%');break;case 2: document.write('%%counter2%%');break;case 3: document.write('%%counter3%%');break;case 4: document.write('%%counter4%%');break;default: document.write('%%counter0%%');}}"
);

if ((false == file_exists ($jp_param['my_counter_file']) or ($jp_param['time_out'] < (time() - filemtime($jp_param['my_counter_file']))))):
	/* Load all the home page from tuidang website */
	$data = jp_loadpage ($jp_param ['counter_url']);
	$tuidang_counter = explode (':', strtr (preg_replace ("/^.*?{$jp_param ['counter_start']}(.*?){$jp_param ['counter_end']}.*?{$jp_param ['counter_start']}(.*?){$jp_param ['counter_end']}.*?{$jp_param ['counter_start']}(.*?){$jp_param ['counter_end']}.*?{$jp_param ['counter_start']}(.*?){$jp_param ['counter_end']}.*?{$jp_param ['counter_start']}(.*?){$jp_param ['counter_end']}.*$/si", "\\1:\\2:\\3:\\4:\\5", $data), array (',' => '.')));
	if (5 == count ($tuidang_counter)): /* Looks like I get the number correctly */
		if ($fp = fopen ($jp_param['my_counter_file'], 'w')):
			fwrite ($fp, strtr ($jp_param['counter_function'], array ('%%counter0%%' => $tuidang_counter[0],'%%counter1%%' => $tuidang_counter[1],'%%counter2%%' => $tuidang_counter[2],'%%counter3%%' => $tuidang_counter[3],'%%counter4%%' => $tuidang_counter[4])));
			fclose ($fp);
		endif;
	endif;
endif;

header ($jp_param['java_header']);
readfile ($jp_param['my_counter_file']);

?>