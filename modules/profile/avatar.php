<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$profile_name = check_full($db->safesql($_REQUEST['name']));

$profile_result = $db->super_query("SELECT * FROM users WHERE name='$profile_name'");

if ($profile_result['user_id'] and (($profile_result['user_id'] == $user_id['user_id']) or $moder)) {
	$it_title = 'Управление аватаром профиля ' . check_full($profile_result['name']);

	$tpl->load_tpl('profile.tpl');
	
	$tpl->set_block("'\\[profile\\](.*?)\\[/profile\\]'si", "");
	$tpl->set_block("'\\[profile_edit\\](.*?)\\[/profile_edit\\]'si", "");
	
	$tpl->set ('[avatar_edit]', '');
	$tpl->set ('[/avatar_edit]', '');

	$tpl->set('[form]', '<form method="post" enctype="multipart/form-data" action="'.$PHP_SELF.'?do=profile&act=avatar_save&name='.check_full($profile_result['name']).'">');
	$tpl->set('[/form]', '</form>');
	$tpl->set('{avatar}', $config['home_url'].'/uploads/avatars/'.$profile_result['avatar']);
	
	$tpl->set('[return]', '<a href="' . $PHP_SELF . '?do=profile&name=' . $profile_result['name'] . '">');
	$tpl->set('[/return]', '</a>');

	$tpl->compile('content');
	$tpl->clear();
} else {
	$_SESSION['echo'] = 'Запрашиваемого вами пользователя несуществует или доступ в этот раздел вам закрыт.';
	move($PHP_SELF.'?do=profile&name=' . $profile_name);
}
?>