<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

if ($user_id['grup'] == 1) $moder = true;

if (! $login) {
	$it_title = 'Авторизация';
    $tpl->load_tpl ('login.tpl');
    $tpl->set_block("'\\[user\\](.*?)\\[/user\\]'si", "");
    $tpl->set ('[guest]', '');
    $tpl->set ('[/guest]', '');
    $tpl->set ('[form]', '<form method="post" action="' . $PHP_SELF . '">');
    $tpl->set ('[/form]', '<input name="login" type="hidden" id="login" value="submit"></form>');

    $tpl->set ('[reg]', '<a href="' . $PHP_SELF . '?do=registration">');
    $tpl->set ('[/reg]', '</a>');
    $tpl->set ('[pass]', '<a href="' . $PHP_SELF . '?do=password_get">');
    $tpl->set ('[/pass]', '</a>');

    $tpl->compile ('content');
	$tpl->clear();
} else {
	$it_title = 'Моё меню';
    $tpl->load_tpl ('login.tpl');
    $tpl->set_block("'\\[guest\\](.*?)\\[/guest\\]'si", "");
    $tpl->set ('[user]', '');
    $tpl->set ('[/user]', '');

    $tpl->set ('[exit]', '<a href="' . $PHP_SELF . '?action=logout">');
    $tpl->set ('[/exit]', '</a>');

    $tpl->set ('[messages]', '<a href="' . $PHP_SELF . '?do=message">');
    $tpl->set ('[/messages]', '</a>');
    $tpl->set ('{mes_new}', intval($user_id['message_unread']));
    $tpl->set ('{mes_all}', intval($user_id['message_all']));

    $tpl->set ('[profile]', '<a href="' . $PHP_SELF . '?do=profile&name=' . $user_id['name'] . '">');
    $tpl->set ('[/profile]', '</a>');

    if ($moder) {
        $tpl->set ('[admin]', '<a href="' . $PHP_SELF . '?do=admin">');
        $tpl->set ('[/admin]', '</a>');
    } else {
        $tpl->set_block("'\\[admin\\](.*?)\\[/admin\\]'si", "");
    }
	
	$tpl->set ('[smiles]', '<a href="' . $PHP_SELF . '?do=other&act=smiles">');
	$tpl->set ('[/smiles]', '</a>');
	$tpl->set ('[bbcode]', '<a href="' . $PHP_SELF . '?do=other&act=bbcode">');
	$tpl->set ('[/bbcode]', '</a>'); 
    $tpl->compile ('content');
	$tpl->clear();
} 

?>