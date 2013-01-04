<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$password_get = array ('login' => $_REQUEST['password_get_login'],
    'captcha' => $_REQUEST['password_get_captcha'],
    'key' => $_REQUEST['password_get_key'],
    );

$password_get = array_map("check_full", $password_get);
$is_pass = 1;

if ($is_pass == 1 and $_SESSION['sec_key_pass_1'] != $password_get['key']) {
    $error .= 'Произошла ошибка сессии. ';
    $is_pass++;
} 

if ($is_pass == 1 and $password_get['login'] == '') {
    $error .= 'Вы не ввели логин. ';
    $is_pass++;
} 

if ($is_pass == 1 and $password_get['login'] != '' and strlen($password_get['login']) < 4) {
    $error .= 'Логин должен быть более 4 символов. ';
    $is_pass++;
} 

if ($is_pass == 1 and $password_get['login'] != '' and strlen($password_get['login']) > 15) {
    $error .= 'Логин должен быть не более 15 символов. ';
    $is_pass++;
} 

$u_count = $db->super_query("SELECT COUNT(*) as count FROM users where name = '{$password_get['login']}'");
if ($is_pass == 1 and $password_get['login'] != '' and $u_count['count'] != 1) {
    $error .= 'Такой пользователь не зарегистрирован!. ';
    $is_pass++;
} 

if ($is_pass == 1 and $_SESSION['sec_code'] != $password_get['captcha']) {
    $error .= 'Введён неверный секретный код. ';
    $is_pass++;
} 

$_SESSION['sec_key_pass_2'] = rv();

if (isset($_SESSION[sec_key_pass_1])) unset ($_SESSION[sec_key_pass_1]);
if (isset($_SESSION[sec_key_pass_3])) unset ($_SESSION[sec_key_pass_3]);

if ($is_pass != 1) {
    info('Ошибка', $error);
} else {
    $vopros_otvet = $db->super_query("SELECT user_id, vopros, otvet FROM users where name = '{$password_get['login']}'");

    $tpl->load_tpl('password_get.tpl');
    $tpl->set_block("'\\[form_1\\](.*?)\\[/form_1\\]'si", "");
    $tpl->set_block("'\\[form_3\\](.*?)\\[/form_3\\]'si", "");
    $tpl->set_block("'\\[good\\](.*?)\\[/good\\]'si", "");
    $tpl->set ('[form_2]', '<form  method="post" action="' . $PHP_SELF . '?do=password_get&act_password_get=3&password_get_key=' . $_SESSION['sec_key_pass_2'] . '&password_get_id=' . intval($vopros_otvet['user_id']) . '">');
    $tpl->set ('{vopros}', check_full($vopros_otvet['vopros']));
    $tpl->set ('{code}', '<img src="' . $config['home_url'] . 'img.php" alt="sec_code">');
    $tpl->set ('[/form_2]', '</form>');
    $tpl->compile('content');
    $tpl->clear();
} 

?>