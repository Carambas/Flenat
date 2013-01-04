<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
if ($moder) {
    switch ($_GET['book_admin']) {
        case "del_all" :
            if ($_GET['submit'] == true) {
                $db->query("TRUNCATE TABLE book");
                if ($config['cache'] == '1') {
                    clear_cache('main_counters');
                    clear_cache('book_count');
                } 
                header ('Location: ' . $PHP_SELF . '?do=book&act=index&set=del_all');
                exit;
            } else {
                $tpl->copy_tpl = '
  Вы действительно хотите удалить все сообщения?<br>
  <a href="' . $PHP_SELF . '?do=book&act=admin&book_admin=del_all&submit=true">Удалить</a> | <a href="' . $PHP_SELF . '?do=book&act=index">Отмена</a><br><br>
  <a href="' . $PHP_SELF . '?do=book&act=index">Вернуться назад</a><br>
 ';
                $tpl->compile('content');
                $tpl->clear();
            } 

            break;
        case "del" :

            if ($_GET['submit'] == true) {
                $db->query("DELETE FROM book WHERE id='" . intval($_GET['post_id']) . "'");
                if ($config['cache'] == '1') {
                    clear_cache('main_counters');
                    clear_cache('book_count');
                } 
                header ('Location: ' . $PHP_SELF . '?do=book&act=index&set=del');
                exit;
            } else {
                $tpl->copy_tpl = '
  Вы действительно хотите удалить это сообщение?<br>
  <a href="' . $PHP_SELF . '?do=book&act=admin&book_admin=del&post_id=' . intval($_GET['post_id']) . '&submit=true">Удалить</a> | <a href="' . $PHP_SELF . '?do=book&act=index">Отмена</a><br><br>
  <a href="' . $PHP_SELF . '?do=book&act=index">Вернуться назад</a><br>
 ';
                $tpl->compile('content');
                $tpl->clear();
            } 

            break;
        case "otvet" :
            if (strlen($_POST['book_otvet']) < 4 or strlen($_POST['book_otvet']) > 500) {
                header ('Location: ' . $PHP_SELF . '?do=book&act=index&set=otvet_no');
                exit;
            } else {
                $db->query("UPDATE book SET o_date = '{$_TIME}', o_author = '{$user_id['name']}', otvet = '" . check($_POST['book_otvet']) . "' WHERE id='" . intval($_GET['post_id']) . "'");

                header ('Location: ' . $PHP_SELF . '?do=book&act=index&set=otvet_ok');
                exit;
            } 
            break;

        default :
            header ('Location: ' . $PHP_SELF . '?do=book&act=index');
            exit;
    } 
    $db->free();
} else {
    info('Ошибка', 'У вас нет доступа в данный раздел. ');
    $tpl->copy_tpl = '<a href="' . $PHP_SELF . '?do=book&act=index">Вернуться назад</a><br>';
    $tpl->compile('content');
    $tpl->clear();
} 

?>