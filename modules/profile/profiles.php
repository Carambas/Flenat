<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$profile_name = check_full($db->safesql($_REQUEST['name']));

$profile_result = $db->super_query("SELECT * FROM users WHERE name='$profile_name'");

if ($profile_result['user_id']) {
    $it_title = 'Профиль ' . check_full($profile_result['name']);

    $online_user = $db->super_query("SELECT id FROM online WHERE uid = '" . intval($profile_result['user_id']) . "'");
    if (isset($online_user['id'])) {
        $online_status = '<font color="green">На сайте</font>';
    } else {
        $online_status = '<font color="red">Ушёл</font>';
    } 

    if ($_GET['submit'] == "edit_ok") info('Информация', 'Профиль был успешно изменен.');

    $tpl->load_tpl('profile.tpl');
    $tpl->set_block("'\\[profile_edit\\](.*?)\\[/profile_edit\\]'si", "");
	$tpl->set_block("'\\[avatar_edit\\](.*?)\\[/avatar_edit\\]'si", "");
    $tpl->set ('[profile]', '');
    $tpl->set ('[/profile]', '');

    if ($profile_result['sex'] == 'm') {
        $sex_user = 'Мужской';
    } else {
        $sex_user = 'Женский';
    } 

    $tpl->set('{online}', $online_status);
    if ($moder) {
        $tpl->set ('{ip}', 'IP: ' . check_full($profile_result['logged_ip']) . '<br>');
        $tpl->set ('{id}', 'ID: ' . check_full($profile_result['user_id']) . '<br>');
    } else {
        $tpl->set ('{ip}', '');
        $tpl->set ('{id}', '<br>');
    } 

    if ($profile_result['info_name'] != "") {
        $tpl->set ('{name}', $profile_result['info_name']);
        $tpl->set ('[name]', '');
        $tpl->set ('[/name]', '');
    } else {
        $tpl->set_block("'\\[name\\](.*?)\\[/name\\]'si", "");
    } 

    if ($profile_result['info_text'] != "") {
        $tpl->set ('{info}', iOpis($profile_result['info_text']));
        $tpl->set ('[info]', '');
        $tpl->set ('[/info]', '');
    } else {
        $tpl->set_block("'\\[info\\](.*?)\\[/info\\]'si", "");
    } 

    if ($profile_result['icq'] != "0") {
        $tpl->set ('{icq}', intval($profile_result['icq']));
        $tpl->set ('[icq]', '');
        $tpl->set ('[/icq]', '');
    } else {
        $tpl->set_block("'\\[icq\\](.*?)\\[/icq\\]'si", "");
    } 

    if ($profile_result['skype'] != "") {
        $tpl->set ('{skype}', $profile_result['skype']);
        $tpl->set ('[skype]', '');
        $tpl->set ('[/skype]', '');
    } else {
        $tpl->set_block("'\\[skype\\](.*?)\\[/skype\\]'si", "");
    } 

    if ($profile_result['mail'] != "") {
        $tpl->set ('{mail}', $profile_result['mail']);
        $tpl->set ('[mail]', '');
        $tpl->set ('[/mail]', '');
    } else {
        $tpl->set_block("'\\[mail\\](.*?)\\[/mail\\]'si", "");
    } 

    if ($profile_result['jabber'] != "") {
        $tpl->set ('{jabber}', $profile_result['jabber']);
        $tpl->set ('[jabber]', '');
        $tpl->set ('[/jabber]', '');
    } else {
        $tpl->set_block("'\\[jabber\\](.*?)\\[/jabber\\]'si", "");
    } 

    $profile_comm = $db->super_query("SELECT count(*) as count FROM downloads_comments WHERE user='$profile_result[id]'");
    $tpl->set ('{comment}', $profile_comm['count']);

    $profile_book = $db->super_query("SELECT count(*) as count FROM book WHERE author='$profile_name'");
    $tpl->set ('{book}', $profile_book['count']);

	$tpl->set('{count_down}', intval($profile_result['count_down']));
	
    $lifestr_profile = explode('|', $profile_result['life_time']);
    $tpl->set('{life_time}', makestime($lifestr_profile[1]));

    $tpl->set ('{user}', check_full($profile_result['name']));
    $tpl->set ('{sex}', check_full($sex_user));
	$tpl->set ('{avatar}', $config['home_url'] . '/uploads/avatars/'.check_full($profile_result['avatar']));

    if ($login and $profile_result['user_id'] != $user_id['user_id']) {
        $tpl->set ('[new_message]', '<a href="' . $PHP_SELF . '?do=message&act=newpm&user=' . $profile_result['user_id'] . '">');
        $tpl->set ('[/new_message]', '</a>');
    } else {
        $tpl->set_block("'\\[new_message\\](.*?)\\[/new_message\\]'si", "");
    } 

    if (date(Ymd, $profile_result['reg_date']) == date(Ymd, $_TIME)) {
        $tpl->set('{registration}', 'Сегодня' . date(", H:i", $profile_result['reg_date']));
    } elseif (date(Ymd, $profile_result['reg_date']) == date(Ymd, ($_TIME - 86400))) {
        $tpl->set('{registration}', 'Вчера' . date(", H:i", $profile_result['reg_date']));
    } else {
        $tpl->set('{registration}', date($config['date_format'], $profile_result['reg_date']));
    } 

    if (date(Ymd, $profile_result['lastdate']) == date(Ymd, $_TIME)) {
        $tpl->set('{lastdate}', 'Сегодня' . date(", H:i", $profile_result['lastdate']));
    } elseif (date(Ymd, $profile_result['lastdate']) == date(Ymd, ($_TIME - 86400))) {
        $tpl->set('{lastdate}', 'Вчера' . date(", H:i", $profile_result['lastdate']));
    } else {
        $tpl->set('{lastdate}', date($config['date_format'], $profile_result['lastdate']));
    } 

    if (($profile_result['user_id'] == $user_id['user_id']) or $moder) {
        $tpl->set ('[edit_user]', '<a href="' . $PHP_SELF . '?do=profile&act=edit&name=' . $profile_result['name'] . '">');
        $tpl->set ('[/edit_user]', '</a>');
    } else {
        $tpl->set_block("'\\[edit_user\\](.*?)\\[/edit_user\\]'si", "");
    } 

    if (($profile_result['user_id'] == $user_id['user_id']) or $moder) {
        $tpl->set ('[edit_avatar]', '<a href="' . $PHP_SELF . '?do=profile&act=avatar&name=' . $profile_result['name'] . '">');
        $tpl->set ('[/edit_avatar]', '</a>');
    } else {
        $tpl->set_block("'\\[edit_avatar\\](.*?)\\[/edit_avatar\\]'si", "");
    }
	
    if ($moder and ($profile_result['user_id'] != $user_id['user_id'])) {
        $tpl->set ('[del_user]', '<a href="' . $PHP_SELF . '?do=profile&act=admin&name=' . $profile_result['name'] . '&profile_admin=del">');
        $tpl->set ('[/del_user]', '</a>');
    } else {
        $tpl->set_block("'\\[del_user\\](.*?)\\[/del_user\\]'si", "");
    } 

    $tpl->compile('content');
    $tpl->clear();
} else {
    info('Ошибка', 'Запрашиваемого вами пользователя несуществует или доступ в этот раздел вам закрыт.');
} 

$db->free();

?>