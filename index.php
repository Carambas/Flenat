<?php

session_start ();
ob_start ();
ini_set('php_flag display_errors', 'on');
ini_set('php_value error_reporting', E_ALL);

define ('_ENGINE_', true);
define ('ROOT_DIR', dirname (__FILE__));
define ('ENGINE_DIR', ROOT_DIR . '/engine');

if (!file_exists(ROOT_DIR . "/config.php")) {
    if (!file_exists(ROOT_DIR . "/install.php")) {
        die ("Файл конфигурации ненайден!");
    } else {
        header ("Location: http://" . $_SERVER['HTTP_HOST'] . "/install.php");
        exit;
    } 
} else {
    include (ROOT_DIR . '/config.php');
} 

require_once ENGINE_DIR . '/functions.php';
require_once ENGINE_DIR . '/classes.php';

$skin = $config['skin'];

if (isset ($_GET['tpl_change'])) {
    $_GET['tpl_name'] = explode("/", $_GET['tpl_name']);
    $_GET['tpl_name'] = end($_GET['tpl_name']);

    if (@is_dir (ROOT_DIR . '/templates/' . $_GET['tpl_name']) and $_GET['tpl_name'] != '') {
        $config['skin'] = htmlspecialchars($_GET['tpl_name']);
        set_cookie("tpl_in", htmlspecialchars($_GET['tpl_name']), 365);
    } 
} elseif (isset ($_COOKIE['tpl_in']) and $_COOKIE['tpl_in'] != '') {
    if (@is_dir (ROOT_DIR . '/templates/' . $_COOKIE['tpl_in'])) {
        $config['skin'] = htmlspecialchars($_COOKIE['tpl_in']);
    } 
} 

$db = new db;
$tpl = new tpls;
$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'];
define ('TEMPLATE_DIR', $tpl->dir);

$Timer = new microTimer;
$Timer->start ();

check_request ();

$PHP_SELF = $config['home_url'] . "index.php";
$_TIME = time();

if ($config['cache'] == '1') {
    $time_update = $_TIME + ($config['cache_check_all'] * 3600);
    $cache_time = cache('cache_time');
    if (!$cache_time) create_cache("cache_time", $time_update);
    elseif ($_TIME >= $cache_time) clear_cache();
} 

include ENGINE_DIR . '/login.php';

if ($login and $config['captcha_out'] == 1) {
    $sec_code = false;
} else {
    $sec_code = true;
} 

$do = totranslit (check_full ($_GET['do']));
$it_do = $do;
$it_do = $it_do ? $it_do : "index";

switch ($do) {
    case "registration" :
        $it_title = 'Регистрация';
        include ROOT_DIR . '/modules/registration/index.php';
        break;

    case "password_get" :
        $it_title = 'Восстановление пароля';
        include ROOT_DIR . '/modules/password_get/index.php';
        break;

    case "book" :
        $it_title = 'Гостевая книга';
        include ROOT_DIR . '/modules/book/index.php';
        break;

    case "pages" :
        $it_title = 'Страницы';
        include ROOT_DIR . '/modules/pages/index.php';
        break;

    case "profile" :
        $it_title = 'Профиль';
        include ROOT_DIR . '/modules/profile/index.php';
        break;

    case "members" :
        $it_title = 'Пользователи';
        include ROOT_DIR . '/modules/members/index.php';
        break;

    case "message" :
        $it_title = 'Сообщения';
        include ROOT_DIR . '/modules/message/index.php';
        break;

    case "others" :
        $it_title = 'Разное';
        include ROOT_DIR . '/modules/others/index.php';
        break;

    case "downloads" :
        $it_title = 'Файловый архив';
        include ROOT_DIR . '/modules/downloads/index.php';
        break;

    case "news" :
        $it_title = 'Новости';
        include ROOT_DIR . '/modules/news/index.php';
        break;
    case "login" :
        include ROOT_DIR . '/modules/login/index.php';
        break;
		
    case "forum" :
        $it_title = 'Форум';
        include ROOT_DIR . '/modules/forum/index.php';
        break;

    case "online" :
        $it_title = 'Онлайн';
        $on_online = 1;
        break;

    case "admin" :
        if ($login) {
            $it_title = 'Админка';
            include ROOT_DIR . '/modules/admin/index.php';
        } else {
			$_SESSION['echo'] = 'Ошибка. <br /> Доступ только для зарегистрированных.';
			move($PHP_SELF);
        } 
        break;

    default :
        if ($do) {
            $_SESSION['echo'] = 'Ошибка. <br /> Нет такой страницы или у вас нет досупа для просмотра этого раздела. ';
			move($PHP_SELF);
        } 
} 

$module_title = $it_title ? $it_title : $config['description'];

include ROOT_DIR . '/modules/online/index.php';
include ROOT_DIR . '/engine/index.php';

echo check_index($tpl->result['index']);

$tpl->all_clear ();
$db->close ();
Output ();
?>