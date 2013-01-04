<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
$buffer = <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=setting&act_2=save">
Заголовок:<br>
<input id="add" type="text" name="save_con[home_name]" value="{$config['home_name']}"><br>
Описание сайта:<br>
<textarea name="save_con[description]">{$config['description']}</textarea><br>
Ключевые слова:<br>
<textarea name="save_con[keywords]">{$config['keywords']}</textarea>
</div>
<div class="post_add"><b>Секретный код:</b><br>
Цвет фона (RGB):<br>
<input id="add" type="text" name="save_con[captcha_back]" value="{$config['captcha_back']}"><br>
Цвет текста (RGB):<br>
<input id="add" type="text" name="save_con[captcha_text]" value="{$config['captcha_text']}"><br>
Цвет рамки (RGB):<br>
<input id="add" type="text" name="save_con[captcha_line]" value="{$config['captcha_line']}"></div>
<div class="post_add">
<input type="submit" value="Сохранить"></form>
</div>
<div class="block"><a href="{$config['home_url']}index.php?do=admin&act=setting">Вернуться назад</a></div>
HTML;

?>