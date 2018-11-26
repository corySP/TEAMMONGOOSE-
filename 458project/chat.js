var lastID = 0;

$(document).ready( function() {
    var refreshInterval = 30000;
    var $chatLog = $("#chatLog");
    var $chatTextInput = $("#chatTextInput");
    var $messageSendBtn = $("#chatMessageSend");

    function sendMessage() {
        var chatTextString = $chatTextInput.val();

        $.get("./write.php", {
            username: usernameString,
            text: chatTextString
        });

        // $chatUsername.val("");
        $chatTextInput.val("");
        retrieveMessages();
    }

    function retrieveMessages() {
        $.get("./read.php?lastID=" + lastID, function(data) {
            $chatLog.html(data);
        });
    }

    $messageSendBtn.click( function() {
        sendMessage();
    });

    setInterval( function() {
        retrieveMessages()
    }, refreshInterval);
});
    
