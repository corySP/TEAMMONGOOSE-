var lastID = 0;

$(document).ready( function() {
    var refreshInterval = 10000;
    var $chatTextInput = $("#chatTextInput");
    var $messageSendBtn = $("#chatMessageSend");

    $messageSendBtn.click( function() {
        sendMessage();
    });

    setInterval( function() {
        retrieveMessages()
    }, refreshInterval);
});
    
function sendMessage() {
    var chatTextString = $("#chatTextInput").val();

    if (chatTextString !== "")
    {
        $.get("./write.php", {
            text: chatTextString
        });
    }

    $("#chatTextInput").val("");
    retrieveMessages();
}

function retrieveMessages() {
    $.ajax({
        type: "GET",
        url: "./read.php?lastID=" + lastID
    }).done( function(data) {
        var $chatLog = $("#chatLog");
        var jsonData = JSON.parse(data);
        var html = "";

        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            html += '<div class="chatMessage"> <b>' + result.username
                    + '</b>: ' + result.text + '</div>';
            lastID = result.id;
        }
        $chatLog.append(html);
    });
}

/* 
function retrieveMessages() {
    $.get("./read.php?lastID=" + lastID, function(data) {
        var $chatLog = $("#chatLog");
        var jsonData = JSON.parse(data);
        var html = "";

        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            html += '<div class="chatMessage"> <b>' + result.username
                    + '</b>: ' + result.text + '</div>';
            lastID = result.id;
        }
        $chatLog.append(html);
    });
}*/
