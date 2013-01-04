<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

// ////////////////////////////
// ///// Получаем имя
if ($login) $who = check_full($user_id['name']);
else $who = 'Гость';
// ////////////////////////////
// ///// Приветствие
$chas = date("H");
if ($chas > 24) $chas = round($chas-24);
if ($chas < 0) $chas = round($chas + 24);
if ($chas <= 4 || $chas >= 23) $welcome = 'Доброй ночи';
if ($chas >= 5 && $chas <= 10) $welcome = 'Доброе утро';
if ($chas >= 11 && $chas <= 17) $welcome = 'Добрый день';
if ($chas >= 18 && $chas <= 22) $welcome = 'Добрый вечер';
// ////////////////////////////
// ///// Обновляем время прибывания на сайте
if ($login) {
    if ($user_id['life_time'] != '') {
        $lifestr = explode('|', $user_id['life_time']);
        $lifetime = time() - $lifestr[0];
        if ($lifetime < 600 && $lifetime > 2) {
            $usertime = $lifestr[1] + $lifetime;
        } else {
            $usertime = $lifestr[1];
        } 
        if ($usertime > 0) {
            $write_tlife = time() . '|' . $usertime;
            $db->query("UPDATE users SET life_time = '{$write_tlife}' WHERE user_id = '{$user_id['user_id']}'");
        } 
    } else {
        $write_tlife = time() . '|0';
        $db->query("UPDATE users SET life_time = '{$write_tlife}' WHERE user_id = '{$user_id['user_id']}'");
    } 
} 
// ////////////////////////////
// ///// Кэшируем счетчики главной
if ($config['cache'] == '1') {
    $main_counts = cache('main_counters');

    if ($main_counts == "") {
        $members_count = $db->super_query("SELECT COUNT(*) as count FROM users");
        $book_count = $db->super_query("SELECT COUNT(*) as count FROM book");
        $pages_count = $db->super_query("SELECT COUNT(*) as count FROM pages");
        $files_count = $db->super_query("SELECT COUNT(*) as count FROM downloads_files");
        $news_count = $db->super_query("SELECT COUNT(*) as count FROM news");
        $main_counts = $members_count['count'] . '|' . $book_count['count'] . '|' . $pages_count['count'] . '|' . $files_count['count'] . '|' . $news_count['count'];
        create_cache('main_counters', $main_counts);
    } 
    $cache_main = explode('|', $main_counts);

    $members_colls = $cache_main[0];
    $book_colls = $cache_main[1];
    $pages_colls = $cache_main[2];
    $files_colls = $cache_main[3];
    $news_colls = $cache_main[4];
} else {
    $members_count = $db->super_query("SELECT COUNT(*) as count FROM users");
    $members_colls = $members_count['count'];

    $book_count = $db->super_query("SELECT COUNT(*) as count FROM book");
    $book_colls = $book_count['count'];

    $pages_count = $db->super_query("SELECT COUNT(*) as count FROM pages");
    $pages_colls = $book_count['count'];

    $files_count = $db->super_query("SELECT COUNT(*) as count FROM downloads_files");
    $files_colls = $files_count['count'];

    $news_count = $db->super_query("SELECT COUNT(*) as count FROM news");
    $news_colls = $news_count['count'];
} 
// ////////////////////////////
// ///// Подгружает статистику
all_stat();
// ////////////////////////////
// ///// Генерируем основную страницу
$tpl->load_tpl ('index.tpl');
// ////////////////////////////
// ///// Ссылка на гостевую
$url_book = '<a href="' . $PHP_SELF . '?do=book">';
$tpl->set ('[book]', $url_book);
$tpl->set ('[/book]', '</a>');
// ////////////////////////////
// ///// Ссылка на пользователей
$url_members = '<a href="' . $PHP_SELF . '?do=members">';
$tpl->set ('[members]', $url_members);
$tpl->set ('[/members]', '</a>');
// ////////////////////////////
// ///// Ссылка на страницы
$url_pages = '<a href="' . $PHP_SELF . '?do=pages">';
$tpl->set ('[pages]', $url_pages);
$tpl->set ('[/pages]', '</a>');
// ////////////////////////////
// ///// Ссылка на загрузки
$url_down = '<a href="' . $PHP_SELF . '?do=downloads">';
$tpl->set ('[downloads]', $url_down);
$tpl->set ('[/downloads]', '</a>');
// ////////////////////////////
// ///// Ссылка на новости
$url_news = '<a href="' . $PHP_SELF . '?do=news">';
$tpl->set ('[news]', $url_news);
$tpl->set ('[/news]', '</a>');
// ////////////////////////////
// ///// Ссылка на форум
$url_news = '<a href="' . $PHP_SELF . '?do=forum">';
$tpl->set ('[forum]', $url_news);
$tpl->set ('[/forum]', '</a>');
// ////////////////////////////
// ///// Вывод онлайна
if ($do == 'online') {
    $tpl->set_block("'\\[online\\](.*?)\\[/online\\]'si", "");
} else {
    if ($config['onl_here'] == 1) {
        $tpl->set ('[online]', '');
		$tpl->set ('{online_link}',  $PHP_SELF . '?do=online');
        $tpl->set ('[/online]', '');
    } else {
        $tpl->set_block("'\\[online\\](.*?)\\[/online\\]'si", "");
    } 
} 
// ////////////////////////////
// ///// Показ новых писем
if ($login and $user_id['message_unread'] > 0 and $do != 'message') {
    $tpl->set ('[new_message]', '<a href="' . $PHP_SELF . '?do=message">');
    $tpl->set ('[/new_message]', '</a><br>');
    $tpl->set ('{message_new}', intval($user_id['message_unread']));
} else {
    $tpl->set_block("'\\[new_message\\](.*?)\\[/new_message\\]'si", "");
} 
// ////////////////////////////
// ///// Генерируем и выводим КЭШ новостей главной
if ($config['index_news'] == 1 and !$do and $news_colls != 0) {
    if ($config['cache'] == '1') {
        $main_news = cache('main_news');
        if ($main_news == "") {
            $news = $db->super_query("SELECT * FROM news ORDER BY date DESC LIMIT 1");
            $main_news = $news['author'] . '|' . $news['name'] . '|' . $news['text'] . '|' . $news['date'] . '|' . $news['count_comm'] . '|' . $news['id'];
            create_cache('main_news', $main_news);
        } 
    } else {
        $news = $db->super_query("SELECT * FROM news ORDER BY date DESC LIMIT 1");
        $main_news = $news['author'] . '|' . $news['name'] . '|' . $news['text'] . '|' . $news['date'] . '|' . $news['count_comm'] . '|' . $news['id'];
    } 

    $news = explode('|', $main_news);

    $tpl->set ('[index_news]', '');
    $tpl->set ('[/index_news]', '');
    $tpl->set ('{author_news}', $news[0]);
    $tpl->set ('{name_news}', $news[1]);
    $tpl->set ('{text_news}', iOpis($news[2]));
    if (date(Ymd, $news[3]) == date(Ymd, $_TIME)) {
        $tpl->set('{date_news}', 'Сегодня' . date(", H:i", $news[3]));
    } elseif (date(Ymd, $news[3]) == date(Ymd, ($_TIME - 86400))) {
        $tpl->set('{date_news}', 'Вчера' . date(", H:i", $news[3]));
    } else {
        $tpl->set('{date_news}', date($config['date_format'], $news[3]));
    } 
    $tpl->set ('{comm_news}', $news[4]);
    $tpl->set ('[link_news]', '<a href="' . $PHP_SELF . '?do=news&act=news&id=' . $news['5'] . '">');
    $tpl->set ('[/link_news]', '</a>');
} else $tpl->set_block("'\\[index_news\\](.*?)\\[/index_news\\]'si", "");
// ////////////////////////////
// ///// Вывод
$tpl->set ('[login]', '<a href="' . $PHP_SELF . '?do=login">');
$tpl->set ('{who}', $who);
$tpl->set ('[/login]', '</a>');
$tpl->set ('{all_members}', $members_colls);
$tpl->set ('{all_book}', $book_colls);
$tpl->set ('{all_pages}', $pages_colls);
$tpl->set ('{all_downloads}', $files_colls);
$tpl->set ('{all_news}', $news_colls);
$tpl->set ('{description}', check($config['description']));
$tpl->set ('{keywords}', check($config['keywords']));
$tpl->set ('{welcome}', $welcome);
$tpl->set ('{header}', check_full($config['home_name']));
$tpl->set ('{title}', check_full($module_title));
$tpl->set ('{date}', date($config['date_format'], time()));

$tpl->set ('{home}', check_full($config['home_url']));
$tpl->set ('{info}', $tpl->result['info']);
$tpl->set ('{stats}', $tpl->result['stats']);
$tpl->set ('{online}', $tpl->result['online']);
$tpl->set ('{index}', $tpl->result['content']);
// ////////////////////////////
// ///// Сборка
$tpl->compile ('index');

?>