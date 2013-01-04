<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$act = totranslit (check_full($_REQUEST['act']));
$act = $act ? $act : 'index';

switch ($act) {
    case "index" :
        include ROOT_DIR . '/modules/profile/profiles.php';
        break;
    case "admin" :
        include ROOT_DIR . '/modules/profile/admin.php';
        break;
    case "edit" :
        include ROOT_DIR . '/modules/profile/edit.php';
        break;
    case "edit_save" :
        include ROOT_DIR . '/modules/profile/edit_save.php';
        break;
    case "avatar" :
        include ROOT_DIR . '/modules/profile/avatar.php';
        break;
    case "avatar_save" :
        include ROOT_DIR . '/modules/profile/avatar_save.php';
        break;
    default :
        info('Ошибка', 'Недопустимый запрос!');
} 

?>