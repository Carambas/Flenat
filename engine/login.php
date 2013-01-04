<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$login = false;
$user_id = false;

$_IP = $db->safesql($_SERVER['REMOTE_ADDR']);

if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) {
    $browsus = htmlspecialchars(stripslashes($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']));
} elseif (isset($_SERVER['HTTP_USER_AGENT'])) {
    $browsus = htmlspecialchars(stripslashes($_SERVER['HTTP_USER_AGENT']));
} else {
    $browsus = 'Not_detected';
}

if (preg_match('/(Opera|Firefox|Safari|Flock|MSIE|K-Meleon|SeaMonkey|Camino|Firebird|Epiphany|Chrome|America Online Browser)[\/: ]([\d.]+)/', $browsus, $out)) {
	if ($out[1] == "MSIE") {
		$out[1] = "Internet Explorer";
	} 
	$user_agent = $out[1] . " " . $out[2];
}

$_SOFT = check($user_agent);

$login_hash = "";

if (isset($_REQUEST['action']) and $_REQUEST['action'] == "logout") {
    $w_user_id = "";
    $w_password = "";
    set_cookie("user_id", "", 0);
    set_cookie("name", "", 0);
    set_cookie("password", "", 0);
    set_cookie("tpl_in", "", 0);
    set_cookie("hash", "", 0);
    set_cookie(session_name(), "", 0);
    @session_destroy();
    @session_unset();
    $login = 0;

    header("Location: $PHP_SELF");
    die();
} 

$login = 0;
$user_id = array ();
if ($_SESSION['engine_log'] > 10) die("Hacking attempt!");

if (isset($_REQUEST['login']) and $_REQUEST['login'] == "submit") {
    $_REQUEST['login_name'] = $db->safesql(check_full($_REQUEST['login_name']));
    $_REQUEST['login_password'] = md5(check_full($_REQUEST['login_password']));

    if (! preg_match("/[\||\'|\<|\>|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $_REQUEST['login_name'])) {
        $user_id = $db->super_query("SELECT * FROM users where name='{$_REQUEST['login_name']}' and password='" . md5($_REQUEST['login_password']) . "'");

        if ($user_id['user_id']) {
            set_cookie("user_id", $user_id['user_id'], 365);
            set_cookie("password", $_REQUEST['login_password'], 365);

            @session_register('user_id');
            @session_register('password');
            @session_register('member_lasttime');

            $_SESSION['user_id'] = $user_id['user_id'];
            $_SESSION['password'] = $_REQUEST['login_password'];
            $_SESSION['member_lasttime'] = $user_id['lastdate'];
            $_SESSION['engine_log'] = 0;
            $login_hash = md5(strtolower($_SERVER['HTTP_HOST'] . $user_id['name'] . $_REQUEST['login_password'] . date("Ymd")));

            if ($config['log_hash'] == 1) {
                $salt = "abchefghjkmnpqrstuvwxyz0123456789";
                $hash = '';
                srand((double) microtime() * 1000000);

                for($i = 0; $i < 9; $i ++) {
                    $hash .= $salt{rand( 0, 33 )};
                } 

                $hash = md5($hash);

                $db->query("UPDATE users set hash='" . $hash . "', lastdate='{$_TIME}', logged_ip='" . $_IP . "' WHERE user_id='$user_id[user_id]'");

                set_cookie("hash", $hash, 365);

                $_COOKIE['hash'] = $hash;
                $user_id['hash'] = $hash;
            } else
                $db->query("UPDATE LOW_PRIORITY users set lastdate='{$_TIME}', logged_ip='" . $_IP . "' WHERE user_id='$user_id[user_id]'");

            $login = true;
        } 
    } 
} elseif (intval($_SESSION['user_id']) > 0) {
    $user_id = $db->super_query("SELECT * FROM users WHERE user_id='" . intval($_SESSION['user_id']) . "'");

    if ($user_id['password'] == md5($_SESSION['password'])) {
        $login = true;
        $login_hash = md5(strtolower($_SERVER['HTTP_HOST'] . $user_id['name'] . $_SESSION['password'] . date("Ymd")));
    } else {
        $user_id = array ();
        $login = false;
    } 
} elseif (intval($_COOKIE['user_id']) > 0) {
    $user_id = $db->super_query("SELECT * FROM users WHERE user_id='" . intval($_COOKIE['user_id']) . "'");

    if ($user_id['password'] == md5($_COOKIE['password'])) {
        $login = true;
        $login_hash = md5(strtolower($_SERVER['HTTP_HOST'] . $user_id['name'] . $_COOKIE['password'] . date("Ymd")));

        @session_register('user_id');
        @session_register('password');

        $_SESSION['user_id'] = $user_id['user_id'];
        $_SESSION['password'] = $_COOKIE['password'];
    } else {
        $user_id = array ();
        $login = false;
    } 
} 

if (isset($_REQUEST['login']) and ! $login) {
    $_SESSION['engine_log'] = intval($_SESSION['engine_log']);
    $_SESSION['engine_log'] ++;

    $_SESSION['echo'] = 'Ошибка авторизации. <br /> Возможно, Вы ввели неверное имя пользователя или пароль.';
	move($PHP_SELF);
} 

if ($login) {
    if (($user_id['lastdate'] + 3600 * 4) < $_TIME) {
        $db->query("UPDATE LOW_PRIORITY users SET lastdate='{$_TIME}' where user_id='$user_id[user_id]'");
    } 

    if (! allowed_ip($user_id['allowed_ip'])) {
        $login = 0;

        $_SESSION['echo'] = 'Ошибка авторизации. <br /> Доступ к вашему аккаунту с данной подсети запрещен.';
    } 

    if (($config['log_hash'] == 1) and (($_COOKIE['hash'] != $user_id['hash']) or ($user_id['hash'] == ""))) {
        $login = 0;
    }
	
	if ($user_id['grup'] == 1) $moder = true;
} 

if (! $login) {
    $user_id = array ();
    set_cookie("user_id", "", 0);
    set_cookie("password", "", 0);
    set_cookie("hash", "", 0);
    $_SESSION['user_id'] = 0;
    $_SESSION['password'] = "";
} 

?>