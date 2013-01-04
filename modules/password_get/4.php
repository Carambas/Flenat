<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
$password_get = array ('id' => $_REQUEST['password_get_id'],
    'captcha' => $_REQUEST['password_get_captcha'],
    'otvet' => $_REQUEST['password_get_otvet'],
    'key' => $_REQUEST['password_get_key'],
    'password' => $_REQUEST['password_get_password'],
    'password_2' => $_REQUEST['password_get_password_2'],
    );

$password_get = array_map("check_full", $password_get);
$is_pass = 1;

if ($is_pass == 1 and $login) {
    $error .= 'Вы уже авторизованны на сайте. ';
    $is_reg++;
} 

if ($is_pass == 1 and $_SESSION['sec_key_pass_3'] != $password_get['key']) {
    $error .= 'Произошла ошибка сессии. ';
    $is_pass++;
} 

if ($is_pass == 1 and $password_get['id'] == '') {
    $error .= 'Произошла ошибка. ';
    $is_pass++;
} 

if ($is_pass == 1 and $_SESSION['sec_code'] != $password_get['captcha']) {
    $error .= 'Введён неверный секретный код. ';
    $is_pass++;
} 

$u_count = $db->super_query("SELECT COUNT(*) as count FROM users where user_id = '{$password_get['id']}'");
if ($is_pass == 1 and $password_get['id'] != '' and $u_count['count'] != 1) {
    $error .= 'Такой пользователь не зарегистрирован!. ';
    $is_pass++;
} 

if ($is_pass == 1 and $password_get['password'] == '') {
    $error .= 'Вы не ввели пароль. ';
    $is_reg++;
} 

if ($is_pass == 1 and $password_get['password_2'] == '') {
    $error .= 'Вы не ввели подтверждающий пароль. ';
    $is_reg++;
} 

if ($is_pass == 1 and $password_get['password'] != '' and $password_get['password_2'] != '' and strlen($password_get['password']) < 5) {
    $error .= 'Пароль должен быть более 5 символов. ';
    $is_reg++;
} 

if ($is_pass == 1 and $password_get['name'] != '' and strlen($password_get['name']) > 15) {
    $error .= 'Логин должен быть не более 15 символов. ';
    $is_reg++;
} 

if ($is_pass == 1 and $password_get['password'] != '' and $password_get['password_2'] != '' and strlen($password_get['password']) > 15) {
    $error .= 'Пароль должен быть не более 15 символов. ';
    $is_reg++;
} 

if ($is_pass == 1 and $password_get['password'] != $password_get['password_2']) {
    $error .= 'Введёные пароли несовпадают. ';
    $is_reg++;
} 

if (isset($_SESSION[sec_key_pass_1])) unset ($_SESSION[sec_key_pass_1]);
if (isset($_SESSION[sec_key_pass_2])) unset ($_SESSION[sec_key_pass_2]);
if (isset($_SESSION[sec_key_pass_3])) unset ($_SESSION[sec_key_pass_3]);

if ($is_pass != 1) {
    info('Ошибка востановления', $error);
    $tpl->copy_tpl = '<a href="' . $PHP_SELF . '?do=password_get&act_password_get=3">Вернуться назад</a><br>';
    $tpl->compile('content');
    $tpl->clear();
} else {
    info('Операция успешна', 'Ваш пароль был успешно изменён. ');
    $user_new_pass = md5(md5($password_get['password']));
    $db->query("UPDATE users SET password = '{$user_new_pass}' WHERE user_id = '{$password_get['id']}'");

    $tpl->load_tpl('password_get.tpl');
    $tpl->set_block("'\\[form_1\\](.*?)\\[/form_1\\]'si", "");
    $tpl->set_block("'\\[form_2\\](.*?)\\[/form_2\\]'si", "");
    $tpl->set_block("'\\[form_3\\](.*?)\\[/form_3\\]'si", "");
    $tpl->set ('[good]', '');
    $tpl->set ('[/good]', '');
    $tpl->set ('{new_password}', $password_get['password']);

    $tpl->compile('content');
    $tpl->clear();
} 

?>