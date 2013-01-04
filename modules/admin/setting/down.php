<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

if ($config['watermark'] == 1) $config_watermark_1 = 'selected';
else $config_watermark_0 = 'selected';

if ($config['screen_add'] == 1) $config_screen_add_1 = 'selected';
else $config_screen_add_0 = 'selected';

if ($config['comm_add_files'] == 1) $config_comm_add_1 = 'selected';
else $config_comm_add_0 = 'selected';

if ($config['allow_files'] == 1) $config_allow_files_1 = 'selected';
else $config_allow_files_0 = 'selected';

if ($config['guest_down'] == 1) $config_guest_down_1 = 'selected';
else $config_guest_down_0 = 'selected';

if ($config['bad_link'] == 1) $config_bad_link_1 = 'selected';
else $config_bad_link_0 = 'selected';

$buffer = <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=setting&act_2=save">
Запрещенные для загрузки файлы:<br>
<textarea name="save_con[ignor_files]">{$config['ignor_files']}</textarea><br>
Разрешенные для загрузки файлы:<br>
<textarea name="save_con[good_files]">{$config['good_files']}</textarea><br>
Максимальный размер файла: (Байт)<br>
<input id="add" type="text" name="save_con[files_sizes]" value="{$config['files_sizes']}"><br>
Максимальный размер скриншота: (Байт)<br>
<input id="add" type="text" name="save_con[screen_sizes]" value="{$config['screen_sizes']}"><br>
<br>
Максимальное разрешение скриншота: <br>
<input id="add" type="text" name="save_con[screen_xy_max]" value="{$config['screen_xy_max']}"><br>
Разрешение уменьшеного скриншота:<br>
<input id="add" type="text" name="save_con[screen_xy_min]" value="{$config['screen_xy_min']}"><br>
Качество уменьшеного скриншота: (0-100)<br>
<input id="add" type="text" name="save_con[screen_xy_quality]" value="{$config['screen_xy_quality']}"><br>
<br>

Показ похожих файлов:<br>
<select name="save_con[allow_files]">
<option value="1" {$config_allow_files_1}>Разрешено</option>
<option value="0" {$config_allow_files_0}>Запрещено</option>
</select><br>

Скачивание файлов для гостей:<br>
<select name="save_con[guest_down]">
<option value="1" {$config_guest_down_1}>Разрешено</option>
<option value="0" {$config_guest_down_0}>Запрещено</option>
</select><br>

Загрузка скриншота:<br>
<select name="save_con[screen_add]">
<option value="1" {$config_screen_add_1}>Обязательно</option>
<option value="0" {$config_screen_add_0}>Не обязательно</option>
</select><br>

Накладывать водяной знак:<br>
<select name="save_con[watermark]">
<option value="1" {$config_watermark_1}>Накладывать</option>
<option value="0" {$config_watermark_0}>Не накладывать</option>
</select><br>

Скрывать прямые ссылки на файл:<br>
<select name="save_con[bad_link]">
<option value="1" {$config_bad_link_1}>Скрывать</option>
<option value="0" {$config_bad_link_0}>Не Скрывать</option>
</select><br>

Название файла копирайта добавляемое в архив: <br>
<input id="add" type="text" name="save_con[file_copyr]" value="{$config['file_copyr']}"><br>
</div>

<div class="post_add"><input type="submit" value="Сохранить"></form></div>
<div class="block"><a href="{$config['home_url']}index.php?do=admin&act=setting">Вернуться назад</a></div>
HTML;

?>