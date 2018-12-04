$(document).ready( function() {
    var $slidePaneBtn = $("#slidePaneBtn");
    var $slidePane = $("#groupbar");
    var $slideChatBtn = $("#slideChatBtn");
    var $slideChat = $("#chatbar");
/*
    $messageSendBtn.click( function() {
        sendMessage();
    });
*/
    $slidePaneBtn.click( function() {
        $slidePane.toggle("slide", {}, 500);
    });

    $slideChatBtn.click( function() {
        $slideChat.toggle("slide", {direction:"down"}, 500);
    });

    $slidePane.toggle("slide", {});
    $slideChat.toggle("slide", {direction:"down"});

});
