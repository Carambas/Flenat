<div class="panel">
<p>
<small>
[inbox]<span class="active">Входящие</span>[/inbox]
[outbox]<span>Исходящие</span>[/outbox]
[new_pm]<span>Отправить</span>[/new_pm]
</small>
</p>
</div>

[pmlist]
{pmlist}
[/pmlist]

[newpm]
<div class="post_add">
Получатель:<br>
<input id="add" type="text" name="name" value="{author}"><br>
Тема:<br>
<input id="add" type="text" name="subj" value="{subj}"><br>
Сообщение: [bbcode]<small>[Стили]</small>[/bbcode] [smiles]<small>[Смайлы]</small>[/smiles]<br>
<textarea name="comments" id="comments" cols="40">{text}</textarea><br>
<input type="checkbox" name="outboxcopy" value="1" checked="checked"> Сохранить<br>
<input type="submit" name="add" value="Отправить" />
</div>
[/newpm]

[readpm]
<div class="post_add">
<strong>Тема:</strong> {subj}<br>
<strong>Отправил:</strong> {author}
</div>
<div class="text">
{text}
</div>
<div class="link">[reply]Ответить[/reply]</div>
<div class="link">[del]Удалить[/del]</div>
[/readpm]
