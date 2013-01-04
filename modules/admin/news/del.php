<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$news = intval($_REQUEST['id']);
$static_result = $db->super_query("SELECT * FROM news WHERE id='$news'");
if ($static_result['name']) {
    if ($_GET['submit'] == true) {
        $db->query("DELETE FROM news WHERE id='{$news}'");
        if ($config['cache'] == '1') {
            @clear_all();
        } 
        header ('Location: ' . $PHP_SELF . '?do=admin&act=news&submit_del=' . $news);
        exit;
    } else {
        $buffer .= '
<div class="post">Вы действительно хотите удалить эту новость?<br>
  <a href="' . $PHP_SELF . '?do=admin&act=news&act_2=del&id=' . $news . '&submit=true">Удалить</a> | <a href="' . $PHP_SELF . '?do=admin&act=news">Отмена</a></div>
<div class="block"><a href="' . $PHP_SELF . '?do=admin&act=news">Вернуться назад</a></div>';
    } 
} else {
    info('Ошибка', 'Такой новости несуществует.');
    $buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=news">Вернуться назад</a></div>
HTML;
} 

?>