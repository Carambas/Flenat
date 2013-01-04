<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

if ($config['cache'] == '1') {
    $its_all = cache("book_count");
    if ($its_all == "") {
        $all_mod = $db->super_query("SELECT COUNT(*) as count FROM book");
        $its_all = $all_mod['count'];
        create_cache("book_count", $its_all);
    } 
} else {
    $all_mod = $db->super_query("SELECT COUNT(*) as count FROM book");
    $its_all = $all_mod['count'];
}

$page = new page($its_all, $config['page']);

if ($its_all == 0) info('Гостевая пуста', 'Добавьте сообщение и оно станет первым. ');

$_SESSION['sec_key_book_1'] = rv();

if (isset($_SESSION[sec_key_book_2])) unset ($_SESSION[sec_key_book_2]);

if (! $login and $config['book_on'] == 0) {
    info('Гостевая закрыта', 'Гостям нельзя добавлять сообщения. ');
} else {
    if ($login and $config['book_on_user'] == 0) info('Гостевая закрыта', 'Пользователям нельзя добавлять сообщения. ');
    else $tpl->load_tpl('book.tpl');
    if ($login) {
        $tpl->set_block("'\\[guest\\](.*?)\\[/guest\\]'si", "");
    } else {
        $tpl->set ('[guest]', '');
        $tpl->set ('[/guest]', '');
    }

    $tpl->set_block("'\\[admin\\](.*?)\\[/admin\\]'si", "");
    $tpl->set_block("'\\[post\\](.*?)\\[/post\\]'si", "");
    $tpl->set ('[form]', '<form  method="post" action="' . $PHP_SELF . '?do=book&act=add&book_key=' . $_SESSION['sec_key_book_1'] . '">');
    $tpl->set ('{code}', '<img src="' . $config['home_url'] . 'img.php" alt="sec_code">');
    if ($sec_code) {
        $tpl->set ('[captcha]', '');
        $tpl->set ('[/captcha]', '');
    } else {
        $tpl->set_block("'\\[captcha\\](.*?)\\[/captcha\\]'si", "");
    } 
    $tpl->set ('[/form]', '</form>');
    $tpl->compile('content');
    $tpl->clear();
} 

$post = intval($_GET['post_id']);

$book_sql = $db->query("SELECT * FROM book ORDER BY id DESC LIMIT ".$page->go.", ".$config['page']."");

while ($row = $db->get_row($book_sql)) {
    $tpl->load_tpl('book.tpl');
    $tpl->set_block("'\\[form\\](.*?)\\[/form\\]'si", "");
    $tpl->set_block("'\\[admin\\](.*?)\\[/admin\\]'si", "");
    $tpl->set('[post]', '');
    $tpl->set('[/post]', '');
	
	$user = $db->super_query("SELECT * FROM users WHERE name = '".$row['author']."' LIMIT 1");

    if ($row['register'] == 1) {
		$tpl->set('{avatar}', $config['home_url'].'uploads/avatars/'.$user['avatar']);
		$tpl->set('{online}', online($row['author']));
        $tpl->set('{author}', '<a href="' . $PHP_SELF . '?do=profile&name=' . check_full($row['author']) . '">' . check_full($row['author']) . '</a>');
    } else {
		$tpl->set('{online}', '');
		$tpl->set('{avatar}', $config['home_url'].'uploads/avatars/guest_avatar.png');
        $tpl->set('{author}', check_full($row['author']));
    }
    $tpl->set('{text}', iPost($row['text']));
	$tpl->set('{date}', iTime($row['date']));

    $tpl->compile('content');
    $tpl->clear();
}
$tpl->copy_tpl = $page->listing($PHP_SELF.'?do=book&act=index');
$tpl->compile('content');
$tpl->clear(); 
$db->free();
?>