<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
if ($moder) {
    $profile_name = check_full($db->safesql($_REQUEST['name']));
    $profile_result = $db->super_query("SELECT * FROM users WHERE name='$profile_name'");

    switch ($_GET['profile_admin']) {
        case "del_ok" :
            info('Успешно', 'Выбранный вами пользователь был удален.');
            $tpl->copy_tpl = '<a href="' . $PHP_SELF . '?do=profile">Вернуться назад</a><br>';
            $tpl->compile('content');
            $tpl->clear();
            break;

        case "del" :
            if ($profile_result['user_id'] == "1") {
                info('Ошибка', 'Невозможно удалить этот профиль.');
                $tpl->copy_tpl = '<a href="' . $PHP_SELF . '?do=profile&name=' . $profile_result['name'] . '">Вернуться назад</a><br>';
                $tpl->compile('content');
                $tpl->clear();
            } else {
                if ($profile_result['user_id'] != "") {
                    if ($_GET['submit'] == true) {
                        $db->query("DELETE FROM users WHERE user_id='" . intval($profile_result['user_id']) . "'");
                        if ($config['cache'] == '1') {
                            clear_cache('main_counters');
                            clear_cache('members_count');
                        } 
                        header ('Location: ' . $PHP_SELF . '?do=profile&act=admin&profile_admin=del_ok&name=' . $profile_result['name']);
                        exit;
                    } else {
                        $tpl->copy_tpl = '
  Вы действительно хотите удалить пользователя <b>' . $profile_result['name'] . '</b>?<br>
  <a href="' . $PHP_SELF . '?do=profile&act=admin&profile_admin=del&name=' . $profile_result['name'] . '&submit=true">Удалить</a> | <a href="' . $PHP_SELF . '?do=profile&act=index&name=' . $profile_result['name'] . '">Отмена</a><br><br>
  <a href="' . $PHP_SELF . '?do=profile&act=index&name=' . $profile_result['name'] . '">Вернуться назад</a><br>
 ';
                        $tpl->compile('content');
                        $tpl->clear();
                    } 
                } else {
                    header ('Location: ' . $PHP_SELF . '?do=profile');
                    exit;
                } 
            } 
            break;

        default :
            header ('Location: ' . $PHP_SELF . '?do=profile&act=index&name=' . $profile_result['name']);
            exit;
    } 
    $db->free();
} else {
    info('Ошибка', 'У вас нет доступа в данный раздел. ');
} 

?>