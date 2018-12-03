$(document).ready( function() {
    var $calBtn = $("#calendar");
    var $maindiv = $("#contents");

    $calBtn.click( function() {
        $maindiv.empty();
        getCal();
    });

});

function getCal() {
    $.get("./calendarHelper.php", {
        text: "hello"
    }, function(data) {
        var $maindiv = $("#contents");
        var jsonData = JSON.parse(data);
        var html = "";

        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            html += '<h2>' + result.cname + ' : ' + result.cdate + '</h2>';
            html += '<hr />';
        }
        $maindiv.append(html);
        //$chatLog.scrollTop($chatLog[0].scrollHeight);
    });
}
