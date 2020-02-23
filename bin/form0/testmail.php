<?php

function makeHeader($fr, $to)
{
    $headers = "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: ${fr}\r\n";
    $headers .= "To: ${to}\r\n";
    return $headers;
}

$from = "anonymous@anonymous.cc";
$to = "cuubinh2004@gmail.com";
$subject = "<" . $_SERVER['SERVER_NAME'] . "> " . date("Y.n.j") . " " . $from;
$title = "Test mail form santui";
$message = "Subject: ${subject}\r\n";
$message .= "From: ${from}\r\n";

mail($to, $subject, $message, makeHeader($from, $to));
