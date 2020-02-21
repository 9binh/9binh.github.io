<?php
/* <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> */

$my_param = array (
	'java-charset-utf-8' => 'Content-type: application/x-javascript; charset=utf8',
	'sub_files' => array ('main.js', 'ssi.js')
);

header ($my_param['java-charset-utf-8']);

foreach ($my_param['sub_files'] as $i) readfile ($i);

?>