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

    retrieveMessages();
});
    
function sendMessage() {
    var chatTextString = $("#chatTextInput").val();

    if (chatTextString === "/hangman")
    {
        getHangman();
    }
    else if (chatTextString ==="/hangman new")
    {
        $.get("./genHangman.php", {
            text: "gen"
        }, function(data){
            getHangman();
        });
    }
    else if (chatTextString.substring(0,9) == "/hangman " && chatTextString.length == 10)
    {
        $.get("./letHangman.php", {
            letter: chatTextString[9]
        }, function(data){
            getHangman();
        });
    }
    else if (chatTextString !== "")
    {
        $.get("./write.php", {
            text: chatTextString
        }, function(data){
            retrieveMessages();
        });
    }

    $("#chatTextInput").val("");
    //retrieveMessages();
}

function getHangman() {
    $.get("./getHangman.php?lastID=" + lastID, function(data) {
        var $chatLog = $("#chatLog");
        var jsonData = JSON.parse(data);
        var html = "";

        for (var i=0; i<jsonData.results.length; i++)
        {
            var result = jsonData.results[i];
            html += '<div class="chatMessage"><pre> _________      </pre> ';
            html += ' <pre>|         |     </pre> ';
            if (result.level == 0)
            {
                html += ' <pre>|               </pre> ';
            }
            else
            {
                html += ' <pre>|         0     </pre> ';
            }

            if (result.level <= 1)
            {
                html += ' <pre>|               </pre> ';
            }
            else if (result.level == 2)
            {
                html += ' <pre>|         |     </pre> ';
            }
            else if (result.level == 3)
            {
                html += ' <pre>|        /|     </pre> ';
            }
            else
            {
                html += ' <pre>|        /|\\    </pre> ';
            }

            if (result.level <= 4)
            {
                html += ' <pre>|               </pre> ';
            }
            else if (result.level == 5)
            {
                html += ' <pre>|        /      </pre> ';
            }
            else
            {
                html += ' <pre>|        / \\    </pre> ';
            }

            html += ' <pre>|  </pre>  <pre>| </pre></div>';

            if (result.level >= 6)
            {
                html += '<div class="chatMessage"><pre>DEAD! YOU LOSE! </pre></div>';
            }
            else if (result.complete == 1)
            {
                html += '<div class="chatMessage"><pre>YOU WON! </pre></div>'
            }

            html += '<div class="chatMessage"><pre>';

            for (var j=0; j<result.prog.length; j++)
            {
                html += result.prog.charAt(j) + ' ';
            }

            html += '</pre> </div>';
            html += '<div> <b> ' + result.word + result.prog + result.level + result.complete +'</b></div>';
        }
        $chatLog.append(html);
    });
}
 
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
}
