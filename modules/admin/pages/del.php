<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$page = intval($_REQUEST['id']);
$static_result = $db->super_query("SELECT * FROM pages WHERE id='$page'");
if ($static_result['name']) {
    if ($_GET['submit'] == true) {
        $db->query("DELETE FROM pages WHERE id='{$page}'");
        if ($config['cache'] == '1') {
            clear_cache('main_counters');
            clear_cache('pages_count');
        } 
        header ('Location: ' . $PHP_SELF . '?do=admin&act=pages&submit_del=' . $page);
        exit;
    } else {
        $buffer .= '
<div class="post">Вы действительно хотите удалить эту страницу?<br>
  <a href="' . $PHP_SELF . '?do=admin&act=pages&act_2=del&id=' . $page . '&submit=true">Удалить</a> | <a href="' . $PHP_SELF . '?do=admin&act=pages">Отмена</a></div>
<div class="block"><a href="' . $PHP_SELF . '?do=admin&act=pages">Вернуться назад</a></div>';
    } 
} else {
    info('Ошибка', 'Такой страницы несуществует.');
    $buffer .= <<<HTML
<a href="{$PHP_SELF}?do=admin&act=pages">Вернуться назад</a><br>
HTML;
} 

?>