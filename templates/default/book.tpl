[form]
<div class="post_add">
[guest]
Ваше имя:<br><input id="add" type="text" name="book_name"><br>
[/guest]
<textarea name="book_text" rows="2"></textarea><br>
[captcha]Секретный код: <br>{code}<br>
Код: <br><input id="add" type="text" name="book_captcha" class="code" /> <br />[/captcha]
<input type="submit" value="Отправить" />
</div>
[/form]


[post]
<div class="post">
<div class="i"><img src="{avatar}" /></div>
<div class="cont">
<span class="user"><em>{date}</em> <strong>{author}</strong> {online}</span>
<div class="text">{text}</div>
</div>
</div>
[/post]