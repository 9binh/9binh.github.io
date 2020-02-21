document.write ("<style> body { margin: 0 } </style>");
function jp_rightbox0 (n) {
	document.write ('<table width=100 cellspacing=0 cellpadding=0 border=0 align=right><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class=imagenotes>' + n + '</td></tr></table>');
}
function jp_leftbox0 (n) {
	document.write ('<table width=100 cellspacing=0 cellpadding=0 border=0 align=right><tr><td class=imagenotes>' + n + '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>');
}
function jp_showhide(name) {
	var e = document.getElementById(name);
	e.style.display = (e.style.display == 'inline') ? 'none' : 'inline';
}