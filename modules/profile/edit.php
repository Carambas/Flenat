<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$profile_name = check_full($db->safesql($_REQUEST['name']));

$profile_result = $db->super_query("SELECT * FROM users WHERE name='$profile_name'");

if ($profile_result['user_id'] and (($profile_result['user_id'] == $user_id['user_id']) or $moder)) {
    $it_title = 'Редактирование профиля ' . check_full($profile_result['name']);

    $_SESSION['sec_key_profile_1'] = rv();

    if (isset($_SESSION[sec_key_profile_2])) unset ($_SESSION[sec_key_profile_2]);

    $tpl->load_tpl('profile.tpl');
    $tpl->set_block("'\\[profile\\](.*?)\\[/profile\\]'si", "");
	$tpl->set_block("'\\[avatar_edit\\](.*?)\\[/avatar_edit\\]'si", "");
    $tpl->set ('[profile_edit]', '');
    $tpl->set ('[/profile_edit]', '');

    $tpl->set ('[form]', '<form  method="post" action="' . $PHP_SELF . '?do=profile&act=edit_save&id=' . $profile_result['user_id'] . '&profile_key=' . $_SESSION['sec_key_profile_1'] . '">');
    $tpl->set ('{code}', '<img src="' . $config['home_url'] . 'img.php" alt="sec_code">');
    $tpl->set ('[/form]', '</form>');

    $tpl->set ('{name}', $profile_result['info_name']);
    $tpl->set ('{text}', $profile_result['info_text']);

    if ($profile_result['icq'] != 0)$tpl->set ('{icq}', $profile_result['icq']);
    else $tpl->set ('{icq}', '');

    $tpl->set ('{skype}', $profile_result['skype']);
    $tpl->set ('{mail}', $profile_result['mail']);
    $tpl->set ('{jabber}', $profile_result['jabber']);

    if ($sec_code) {
        $tpl->set ('[captcha]', '');
        $tpl->set ('[/captcha]', '');
    } else {
        $tpl->set_block("'\\[captcha\\](.*?)\\[/captcha\\]'si", "");
    } 
	
	$tpl->set('[return]', '<a href="' . $PHP_SELF . '?do=profile&name=' . $profile_result['name'] . '">');
	$tpl->set('[/return]', '</a>');

    $tpl->compile('content');
    $tpl->clear();
} else {
	$_SESSION['echo'] = 'Запрашиваемого вами пользователя несуществует или доступ в этот раздел вам закрыт.';
	move($PHP_SELF.'?do=profile&name=' . $profile_name);
} 

$db->free();

?>