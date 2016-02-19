<form action="/email-test/send" method="post">
    <input name="user-email" type="text"/>
    <select name="template">
        <option value="jackpot-rollover">jackpot-rollover</option>
        <option value="latest-results">latest-results</option>
        <option value="low-balance">low-balance</option>
        <option value="long-play-is-ended">long-play-is-ended</option>
        <option value="win-email">win-email</option>
        <option value="win-email-above-1500">win-email-above-2500</option>
        <option value="register">register (Validate your email)</option>
        <option value="send-password-request">send-password-request</option> 
<!-- Not used anymore        <option value="send-new-password">send-new-password</option> -->
        <option value="welcome">welcome</option>
        <option value="all"><strong>send them all</strong></option>
    </select>
    <input type="submit" value="Send!"/>
</form>