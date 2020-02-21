<?php

/* <meta http-equiv="content-type" content="text/html;charset=utf-8"> */

error_reporting (0);

define ("RECEIVER", "chinbinh@9binh.com,jiuping@lycos.co.uk,quychan@yandex.ru");
define ("NOBODY", "anonymous@anonymous.cc");
define ("TITLE", "Cong bo thoai dang");
define ("HOME_PAGE", "/");
define ("TUIDANG", "[tuidang]");

function makeHeader ($fr, $to) {
	$headers .= "Content-type: text/plain; charset=utf-8\r\n";
	$headers .= "From: ${fr}\r\n";
	$headers .= "To: ${to}\r\n";
	return $headers;
} /* makeHeader */

function jumpPage ($url) {
	print ("<html><head><meta http-equiv=\"refresh\" content=\"0;url='${url}'\"></head></html>");
} /* jumpPage */

switch (trim ($_POST['op'])) {
	case TUIDANG: $to = RECEIVER; break;
	default: $to = ""; break;
}

if ($to == "") {

	jumpPage (HOME_PAGE);

} else {

	$jump_page_ok = $_POST['okpage'] ? trim ($_POST['okpage']) : HOME_PAGE;
	$jump_page_error = $_POST['errorpage'] ? trim ($_POST['errorpage']) : HOME_PAGE;

	$from = $_POST['email'] ? trim ($_POST['email']) : NOBODY;

	$subject = "<" . $_SERVER['SERVER_NAME'] . "> " . trim ($_POST['op']) . " " . date ("Y.n.j") . " " . $from;

	$title = TITLE;

	$message = "Subject: ${subject}\r\n";
	$message .= "From: ${from}\r\n";
	if ($_POST['name']) $message .= "Name: " . trim ($_POST['name']) . "\r\n";
	if ($_POST['address']) $message .= "Address: " . trim ($_POST['address']) . "\r\n";
	if ($_POST['title']) $message .= "Title: " . trim ($_POST['title']) . "\r\n";
	if ($_POST['number']) $message .= "Number: " . trim ($_POST['number']) . "\r\n";
	$message .= "\r\n";
	if ($_POST['message']) $message .= $_POST['message'];

	jumpPage ($jump_page_ok);

	mail ($to, $subject, $message, makeHeader ($from, $to));

}

?>
