document.write("<style> body { margin: 0 } </style>");
function jp_rightbox0(n) {
    document.write('<table width=100 cellspacing=0 cellpadding=0 border=0 align=right><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td class=imagenotes>' + n + '</td></tr></table>');
}
function jp_leftbox0(n) {
    document.write('<table width=100 cellspacing=0 cellpadding=0 border=0 align=right><tr><td class=imagenotes>' + n + '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>');
}
function jp_showhide(name) {
    var e = document.getElementById(name);
    e.style.display = (e.style.display == 'inline') ? 'none' : 'inline';
}


jQuery(document).ready(function ($) {
    function check_statics_loaded() {
        var data = $('#jp_tuidang_total1').html();
        /*console.info('DATA', data);*/
        if (data.trim() == '<script language="javascript">jp_tuidang_counter(0)</script>') {
            return false;
        } else {
            return true;
        }
    }

    if (!check_statics_loaded()) {
        $.ajax({
            type: "GET",
            url: "/stat/statics.xml",
            dataType: "xml",
            success: function (xml) {
                var total = $(xml).find('TOTAL').text();
                var thismonth = $(xml).find('THISMONTH').text();
                var thisweek = $(xml).find('THISWEEK').text();
                var yesterday = $(xml).find('YESTERDAY').text();
                var today = $(xml).find('TODAY').text();
                var lastupdate = $(xml).find('LASTUPDATE').text();

                $('#jp_tuidang_total1').html(total);
                $('#jp_tuidang_total2').html(total);
                $('#jp_tuidang_thismonth').html(thismonth);
                $('#jp_tuidang_thisweek').html(thisweek);
                $('#jp_tuidang_yesterday').html(yesterday);
                $('#jp_tuidang_today').html(today);
                $('#jp_tuidang_lastupdate').html(lastupdate);
            }
        });
    }


});

