
$(document).ready( function() {
    var $taskLink = $("#tasks");
    var $maindiv = $("#contents");
/*
    $messageSendBtn.click( function() {
        sendMessage();
    });
*/
    $taskLink.click( function() {
        $maindiv.empty();
        getTasks();
    });

    getTasks();

});

function getTasks() {
    $.get("./getTasks.php", {
        text: "hello"
    }, function(data) {
        var $maindiv = $("#contents");
        var jsonData = JSON.parse(data);
        var html = "";

        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            html += '<h2>' + result.id + ' : ' + result.task_name + '</h2>';
            html += '<hr />';
            html += '<p><b>' + result.task_description + '</b></p>';
            html += '<p> current status: ' + result.current_status + '</p>';
            html += '<textarea rows="5" cols="80"> ' + result.user_comment + '</textarea>';
            html += '<hr />';
        }
        $maindiv.append(html);
        //$chatLog.scrollTop($chatLog[0].scrollHeight);
    });
}
