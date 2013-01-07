<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$registration = array ('name' => $_REQUEST['registration_login'],
    'password' => $_REQUEST['registration_password'],
    'password_2' => $_REQUEST['registration_password_2'],
    'vopros' => $_REQUEST['registration_vopros'],
    'otvet' => $_REQUEST['registration_otvet'],
    'captcha' => $_REQUEST['registration_captcha'],
    'sex' => $_REQUEST['registration_sex'],
    'key' => $_REQUEST['registration_key'],
    );

$registration = array_map("check_full", $registration);
$is_reg = 1;

if ($is_reg == 1 and $config['registr'] == 0) {
    $error .= 'Регистрация закрыта администратором сайта. ';
    $is_reg++;
} 

if ($is_reg == 1 and $login) {
    $error .= 'Вы уже зарегистрированны на сайте. ';
    $is_reg++;
} 

if ($is_reg == 1 and $_SESSION['sec_key_reg_1'] != $registration['key']) {
    $error .= 'Произошла ошибка сессии. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['name'] == '') {
    $error .= 'Вы не ввели логин. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['password'] == '') {
    $error .= 'Вы не ввели пароль. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['password_2'] == '') {
    $error .= 'Вы не ввели подтверждающий пароль. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['name'] != '' and strlen($registration['name']) < 3) {
    $error .= 'Логин должен быть более 3 символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['password'] != '' and $registration['password_2'] != '' and strlen($registration['password']) < 5) {
    $error .= 'Пароль должен быть более 5 символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['name'] != '' and strlen($registration['name']) > 15) {
    $error .= 'Логин должен быть не более 15 символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['password'] != '' and $registration['password_2'] != '' and strlen($registration['password']) > 15) {
    $error .= 'Пароль должен быть не более 15 символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['password'] != $registration['password_2']) {
    $error .= 'Введёные пароли несовпадают. ';
    $is_reg++;
} 

if ($is_reg == 1 and preg_match("/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $registration['name'])) {
    $error .= 'Логин состоит из недопустимых символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and preg_match("/[^A-z0-9-]/", $registration['name'])) {
    $error .= 'Логин должен состоять только из английских символов и цифр. ';
    $is_reg++;
} 

if ($is_reg == 1 and preg_match("/[^A-z0-9-]/", $registration['password'])) {
    $error .= 'Пароль должен состоять только из английских символов и цифр. ';
    $is_reg++;
} 

if ($is_reg == 1 and $_SESSION['sec_code'] != $registration['captcha']) {
    $error .= 'Введён неверный секретный код. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['vopros'] == '') {
    $error .= 'Не введён секретный вопрос. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['vopros'] != '' and strlen($registration['vopros']) > 100) {
    $error .= 'Секретный вопрос должен быть не более 100 символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['vopros'] != '' and strlen($registration['vopros']) < 5) {
    $error .= 'Секретный вопрос должен содержать не менее 5 символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['otvet'] == '') {
    $error .= 'Не введён ответ на секретный вопрос. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['otvet'] != '' and strlen($registration['otvet']) > 25) {
    $error .= 'Ответ на секретный вопрос должен быть не более 25 символов. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['otvet'] != '' and strlen($registration['otvet']) < 2) {
    $error .= 'Ответ на секретный вопрос должен содержать не менее 2 символов. ';
    $is_reg++;
} 

$u_count = $db->super_query("SELECT COUNT(*) as count FROM users where name = '{$registration['name']}'");

if ($is_reg == 1 and $registration['name'] != '' and $u_count['count'] > 0) {
    $error .= 'Данный логин занят, выбирите другой. ';
    $is_reg++;
} 

if ($is_reg == 1 and $registration['sex'] != 'm') {
    if ($registration['sex'] != 'w') {
        $error .= 'Вы неуказали пол. ';
        $is_reg++;
    } 
} 

if (isset($_SESSION[sec_key_reg_1])) unset ($_SESSION[sec_key_reg_1]);

if ($is_reg != 1) {
    $_SESSION['echo'] = $error;
	move($PHP_SELF.'?do=registration&act=1');
} else {
    $tpl->load_tpl('registration.tpl');
    $tpl->set_block("'\\[form\\](.*?)\\[/form\\]'si", "");
    $tpl->set ('[good]', '');
    $tpl->set ('[/good]', '');
    $tpl->set ('{login}', $registration['name']);
    $tpl->set ('{pass}', $registration['password']);
    $tpl->set ('{vopros}', $registration['vopros']);
    $tpl->set ('{otvet}', $registration['otvet']);
	$tpl->set ('{autologin}', $PHP_SELF . '?login_name=' . $registration['name'] . '&login_password=' . $registration['password'] . '&login=submit');
	$tpl->set ('{enter}', '<a href="' . $PHP_SELF . '?login_name=' . $registration['name'] . '&login_password=' . $registration['password'] . '&login=submit"><input type="submit" value="Войти на сайт"></a>');
    $tpl->compile('content');
    $tpl->clear();
	
    $user_password_code = md5(md5($registration['password']));
    $user_pass = md5($registration['password']);
    $date_reg = time();
    $_IP = @$db->safesql($_SERVER['REMOTE_ADDR']);
    $db_otvet = md5($registration['otvet']);

    $user_adm = $db->super_query("SELECT COUNT(*) as count FROM users");
    if ($user_adm['count'] == 0) {
        $is_grup = 1;
    } else {
        $is_grup = 0;
    } 

    $db->query(" INSERT INTO users SET grup = '{$is_grup}', name = '{$registration['name']}', vopros = '{$registration['vopros']}', otvet = '{$db_otvet}', password = '{$user_password_code}', sex = '{$registration['sex']}', reg_date = '{$date_reg}', logged_ip = '{$_IP}', lastdate = '{$date_reg}'");

    $id = $db->insert_id();

    if ($config['cache'] == '1') {
        clear_all ();
    } 

    set_cookie("user_id", $id, 365);
    set_cookie("password", $registration['password'], 365);

    @session_register('user_id');
    @session_register('password');

    $_SESSION['user_id'] = $id;
    $_SESSION['password'] = $registration['password'];
} 

?>
