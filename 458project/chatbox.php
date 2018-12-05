<!--
    Put this where you want the chatbox
-->
<div class="chatBox">
    <div id="chatLog"></div>
    <hr id="chathr" />
    <form id="chatform" onsubmit="event.preventDefault();">
    <input id="chatTextInput" type="text" placeholder="Type message here, press ENTER to send" maxlength="128">
    <button id="chatMessageSend" hidden>Send</button>
<!--    <script src="jquery-3.3.1.min.js" type="text/javascript" ></script>
    <script src="chat.js" type="text/javascript" >
    </script>
-->
    <hr />
    </form>

</div>
