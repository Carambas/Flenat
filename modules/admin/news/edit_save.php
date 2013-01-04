<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$news_id = intval($_REQUEST['news_id']);
$news_name = check_full($_REQUEST['news_name']);
$news_text = check_full($_REQUEST['news_text']);
$news_text_full = check_full($_REQUEST['news_text_full']);

if (!$news_name or !$news_text or !$news_id or !$news_text_full) {
    info('Ошибка', 'Незаполнено одно из полей.');
} else {
    $is_news = 1;

    if ($is_news == 1 and strlen($news_name) < 4 and strlen($news_name) > 20) {
        $error .= 'Название страницы должно содеражать от 4 до 20 символов. ';
        $is_news++;
    } 

    if ($is_news == 1 and strlen($news_text) < 10 and strlen($news_text) > 200) {
        $error .= 'Краткий текст новости должен содеражать от 10 до 200 символов. ';
        $is_news++;
    } 

    if ($is_news == 1 and strlen($news_text_full) < 10 and strlen($news_text_full) > 5000) {
        $error .= 'Полный текст новости должен содеражать от 10 до 5000 символов. ';
        $is_news++;
    } 

    if ($is_news == 1 and preg_match("/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $news_name)) {
        $error .= 'Название состоит из недопустимых символов. ';
        $is_news++;
    } 

    if ($is_news != 1) {
        info('Ошибка', $error);
    } else {
        $db->query("UPDATE news SET name = '$news_name', text = '$news_text', text_full = '$news_text_full' WHERE id = '$news_id'");
        info('Информация', 'Новость успешно обновлена.');

        if ($config['cache'] == '1') {
            @clear_all();
        } 
    } 
} 

$buffer .= <<<HTML
<div class="block"><a href="{$PHP_SELF}?do=admin&act=news">Вернуться назад</a></div>
HTML;

?>