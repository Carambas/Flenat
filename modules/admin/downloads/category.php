<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ////////////////////////////
// ///// Получаем список категорий
$cat_info = get_vars("category_down"); //из кэша

$cat_info = array ();

$db->query ("SELECT * FROM downloads_category ORDER BY position DESC");
while ($row = $db->get_row ()) {
    $cat_info[$row['id']] = array ();

    foreach ($row as $key => $value) {
        $cat_info[$row['id']][$key] = stripslashes ($value);
    } 
} 
// ////////////////////////////
// ///// функция вывода категорий для админки
function downloads_cat($categoryid = 0, $parentid = 0, $nocat = true, $sublevelmarker = '', $returnstring = '')
{
    global $cat_info, $user_group, $member_id, $db;

    $root_category = array ();

    if ($parentid == 0) {
        if ($nocat) $returnstring .= '';
    } else {
        $sublevelmarker .= '---';
    } 

    if (count($cat_info)) {
        foreach ($cat_info as $cats) {
            if ($cats['id_parent'] == $parentid) $root_category[] = $cats['id'];
        } 

        if (count($root_category)) {
            foreach ($root_category as $id) {
                $coun = $db->super_query("SELECT count(*) as count FROM downloads_files WHERE category = '{$id}'");

                $returnstring .= "<div class=\"post\">[<a href=\"" . $PHP_SELF . '?do=admin&act=downloads&act_2=category_into&id=' . $id . '&is=up">&#8593;</a>|' . $cat_info[$id]['position'] . '|<a href="' . $PHP_SELF . '?do=admin&act=downloads&act_2=category_into&id=' . $id . '&is=down">&#8595;</a>]</small> ' . $sublevelmarker . '<b>' . $cat_info[$id]['name'] . '</b> <small>(Файлов: ' . $coun['count'] . ') [<a href="' . $PHP_SELF . '?do=admin&act=downloads&act_2=category_edit&id=' . $id . '">Изменить</a>] [<a href="' . $PHP_SELF . '?do=admin&act=downloads&act_2=category_del&id=' . $id . '">Удалить</a>]</small></div>';

                $returnstring = downloads_cat($categoryid, $id, $nocat, $sublevelmarker, $returnstring);
            } 
        } 
    } 
    return $returnstring;
} 
// ////////////////////////////
// ///// выводим список категорий
$buffer .= downloads_cat();

if ($buffer == "") $buffer .= "<div class=\"text\">Категории не найдены.</div>";

$buffer .= <<<HTML
<div class="link"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category_add">Добавить категорию</a></div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads">Вернуться назад</a></div>
HTML;

?>