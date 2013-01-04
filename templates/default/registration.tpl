[form]
<div class="post_add">
Логин:<br><input id="add" type="text" name="registration_login"><br>
Пароль:<br><input id="add" type="password" name="registration_password"><br>
Повторите пароль:<br><input id="add" type="password" name="registration_password_2"><br>
Секретный вопрос:<br><textarea name="registration_vopros"></textarea><br>
Ответ на вопрос:<br><input id="add" type="text" name="registration_otvet"><br>
Ваш пол:<br><select name="registration_sex"><option value="m">Муж.</option><option value="w">Жен.</option></select><br>
Секретный код: <br>{code}<br>
Код:<br><input id="add" type="text" name="registration_captcha" class="code"><br>
<input type="submit" value="Регистрация">
</div>
[/form]


[good]
<div class="post_add">
Благодарим за регистрацию!<br>
Теперь Вы можете войти на сайт, используя Ваш логин и пароль.<br>
Ваш логин: {login}<br>
Пароль: {pass}<br>
Секретный вопрос: {vopros}<br>
Ответ на вопрос: {otvet}<br><br>
Автологин:<br><input id="add" type="text" value="{autologin}"/><br>
{enter}
</div>
[/good]