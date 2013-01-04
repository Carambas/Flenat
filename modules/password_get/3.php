<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$password_get = array ('id' => $_REQUEST['password_get_id'],
    'captcha' => $_REQUEST['password_get_captcha'],
    'otvet' => $_REQUEST['password_get_otvet'],
    'key' => $_REQUEST['password_get_key'],
    );

$password_get = array_map("check_full", $password_get);
$is_pass = 1;

if ($is_pass == 1 and $_SESSION['sec_key_pass_2'] != $password_get['key']) {
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

if ($is_pass == 1 and $password_get['otvet'] == '') {
    $error .= 'Не введён ответ на секретный вопрос. ';
    $is_pass++;
} 

if ($is_pass == 1 and $password_get['otvet'] != '' and strlen($password_get['otvet']) > 25) {
    $error .= 'Ответ на секретный вопрос должен быть не более 25 символов. ';
    $is_pass++;
} 

if ($is_pass == 1 and $password_get['otvet'] != '' and strlen($password_get['otvet']) < 2) {
    $error .= 'Ответ на секретный вопрос должен содержать не менее 2 символов. ';
    $is_pass++;
} 

$u_count = $db->super_query("SELECT COUNT(*) as count FROM users where user_id = '{$password_get['id']}'");
if ($is_pass == 1 and $password_get['id'] != '' and $u_count['count'] != 1) {
    $error .= 'Такой пользователь не зарегистрирован!. ';
    $is_pass++;
} else {
    $vopros_otvet = $db->super_query("SELECT vopros, otvet FROM users where user_id = '{$password_get['id']}'");

    $otvet_u = md5($password_get['otvet']);
    $otvet_db = $vopros_otvet['otvet'];

    if ($is_pass == 1 and $password_get['otvet'] != '' and $otvet_u != $otvet_db) {
        $error .= 'Введён неверный ответ на секретный вопрос. ';
        $_SESSION['engine_log'] = intval($_SESSION['engine_log']);
        $_SESSION['engine_log'] ++;
        $is_pass++;
    } 
} 

$_SESSION['sec_key_pass_3'] = rv();

if (isset($_SESSION[sec_key_pass_1])) unset ($_SESSION[sec_key_pass_1]);
if (isset($_SESSION[sec_key_pass_2])) unset ($_SESSION[sec_key_pass_2]);

if ($is_pass != 1) {
    info('Ошибка востановления', $error);
} else {
    $tpl->load_tpl('password_get.tpl');
    $tpl->set_block("'\\[form_1\\](.*?)\\[/form_1\\]'si", "");
    $tpl->set_block("'\\[form_2\\](.*?)\\[/form_2\\]'si", "");
    $tpl->set_block("'\\[good\\](.*?)\\[/good\\]'si", "");
    $tpl->set ('[form_3]', '<form  method="post" action="' . $PHP_SELF . '?do=password_get&act_password_get=4&password_get_key=' . $_SESSION['sec_key_pass_3'] . '&password_get_id=' . $password_get['id'] . '">');
    $tpl->set ('{code}', '<img src="' . $config['home_url'] . 'img.php" alt="sec_code">');
    $tpl->set ('[/form_3]', '</form>');
    $tpl->compile('content');
    $tpl->clear();
} 

?>