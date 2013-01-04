[profile]
<div class="text">
<img align="left" src="{avatar}" style="margin: 0 5px 2px 0;" />
Пользователь: <b>{user}</b> {id}
[name]Имя: {name}<br>[/name]
Статус: {online}<br>
Пол: {sex}<br>
[info]О себе: {info}<br>[/info]
{ip}
</div> 
<div class="block">Контакты</div>
<div class="text">
[icq]ICQ: {icq}<br>[/icq]
[skype]Skype: {skype}<br>[/skype]
[jabber]Jabber: {jabber}<br>[/jabber]
[mail]E-mail: {mail}<br>[/mail]
</div> 
<div class="block">Статистика</div>
<div class="text">
Дата регистрации: {registration}<br>
Последнее посещение: {lastdate}<br>
Комментариев: {comment}<br>
Сообщений в гостевой: {book}<br>
Скаченных файлов: {count_down}<br>
Время на сайте: {life_time}<br>
</div> 
<br>
<div class="link">[edit_user]Редактировать профиль[/edit_user]</div>
<div class="link">[edit_avatar]Изменить аватар[/edit_avatar]</div>
<div class="link">[new_message]Отправить сообщение[/new_message]</div>
<div class="link">[del_user]Удалить пользователя[/del_user]</div>
[/profile]



[profile_edit]
<div class="post_add">
[form]
Имя:<br><input id="add" type="text" name="info_name" value="{name}"><br>
ICQ:<br><input id="add" type="text" name="icq" value="{icq}"><br>
Skype:<br><input id="add" type="text" name="skype" value="{skype}"><br>
Jabber:<br><input id="add" type="text" name="jabber" value="{jabber}"><br>
E-mail:<br><input id="add" type="text" name="mail" value="{mail}"><br>
О себе:<br><textarea name="info_text">{text}</textarea><br>
[captcha]Секретный код: <br>{code}<br>
Код:<br><input id="add" type="text" name="registration_captcha" class="code"><br>[/captcha]
<input type="submit" value="Сохранить">
[/form]
</div>
<div class="link">[return]Вернуться назад[/return]</div>
[/profile_edit]

[avatar_edit]
<div class="post">
<div class="i">
<img height="50" width="50" src="{avatar}"/>
</div>
<div class="cont">
[form]
<input type="file" name="avatar" accept="image/*,image/gif,image/png,image/jpeg" /><br />
<input value="Загрузить" type="submit" />
[/form]
</div>
</div>
<div class="link">[return]Вернуться назад[/return]</div>
[/avatar_edit]