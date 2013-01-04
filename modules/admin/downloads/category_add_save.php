<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$cat_id = intval($_REQUEST['cat_id']);
$cat_name = check_full($_REQUEST['cat_name']);

if (!$cat_name) {
    info('Ошибка', 'Незаполнено одно из полей.');
} else {
    $db->query("INSERT INTO downloads_category SET name = '$cat_name', id_parent = '$cat_id'");
    info('Успешно', 'Категория успешно добавлена.');

    if ($config['cache'] == '1') @clear_cache();
} 

$buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Перейти в категории</a></div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category_add">Вернуться назад</a></div>
HTML;

?>