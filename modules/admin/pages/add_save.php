<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
$page_name = check_full($_REQUEST['page_name']);
$page_text = check_full($_REQUEST['page_text']);
if (!$page_name or !$page_text) {
    info('Ошибка', 'Незаполнено одно из полей.');
} else {
    $is_page = 1;

    if ($is_page == 1 and strlen($page_name) < 4 and strlen($page_name) > 20) {
        $error .= 'Название страницы должно содеражать от 4 до 20 символов. ';
        $is_page++;
    } 

    if ($is_page == 1 and preg_match("/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $page_name)) {
        $error .= 'Название состоит из недопустимых символов. ';
        $is_page++;
    } 

    if ($is_page != 1) {
        info('Ошибка', $error);
    } else {
        $db->query("INSERT INTO pages SET name = '$page_name', text = '$page_text'");
        info('Успешно', 'Страница успешно добавлена.');
        if ($config['cache'] == '1') {
            @clear_cache('main_counters');
            @clear_cache('pages_count');
        } 
    } 
} 

$buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=pages">Вернуться назад</a></div>
HTML;

?>