<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$cat_info = array ();

$it_title = 'Массовая загрузка файлов';

$db->query ("SELECT * FROM downloads_category ORDER BY position DESC");
while ($row = $db->get_row ()) {
    $cat_info[$row['id']] = array ();

    foreach ($row as $key => $value) {
        $cat_info[$row['id']][$key] = stripslashes ($value);
    } 
} 

$db->query('DELETE FROM tmp_files');
$file_list = list_files_save("uploads/tmp/");

if (!$file_list) $file_list = 'Файлы для загрузки не найдены.';
else $file_list = $file_list . '<br><input type="submit" value="Добавить">';

$buffer .= <<<HTML
<div class="post">
<form enctype="multipart/form-data" method="post" action="{$PHP_SELF}?do=admin&act=downloads&act_2=files_add_mass_save">
Выбирите категорию:<br>
HTML;

$buffer .= '<select name="file_cat">' . downloads_cat_select() . '</select><br>';

$buffer .= <<<HTML
</div>
<div class="block">
Выберите как загружать файлы:
</div>
<div class="post">
<input name="primer" type="radio" value="1" checked>
<small>
Пример 1:  <br>
<b>file_name.rar</b> (Файл для загрузки,  вид допустимых файлов указывается в настройках)<br>
<b>file_name.txt</b> (Описание должно быть строго в формате txt).<br>
<b>file_name.jpg</b> (Скриншоты любого размера, вид допустимых скриншотов: .jpg  .png  .gif).<br>
<br></small>
</div>
<div class="post">
<input name="primer" type="radio" value="2">
<small>
Пример 2:  <br>
<b>file_name.rar</b> (Файл для загрузки,  вид допустимых файлов указывается в настройках)<br>
<b>file_name.rar.txt</b> (Описание должно быть строго в формате txt).<br>
<b>file_name.rar.jpg</b> (Скриншоты любого размера, вид допустимых скриншотов: .jpg  .png  .gif).<br>
<br></small>
</div>
<div class="block">Список разрешенных к загрузке файлов (uploads/tmp/):</div>
<div class="text">{$file_list}</div>
</form>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=downloads">Вернуться назад</a></div>
HTML;

?>