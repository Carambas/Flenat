<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$page_id = intval($_REQUEST['page_id']);
$page_name = check_full($_REQUEST['page_name']);
$page_text = check_full($_REQUEST['page_text']);
if (!$page_name or !$page_text or !$page_id) {
    info('Ошибка', 'Незаполнено одно из полей.');
} else {
    $db->query("UPDATE pages SET name = '$page_name', text = '$page_text' WHERE id = '$page_id'");
    info('Успешно', 'Страница успешно обновлена.');
    if ($config['cache'] == '1') {
        @clear_cache('main_counters');
        @clear_var("pages");
    } 
} 
$buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=pages">Вернуться назад</a></div>
HTML;

?>