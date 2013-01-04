<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$id = intval($_GET['id']);

$cat_down = $db->super_query ("SELECT * FROM downloads_category WHERE id = '$id'");
$id_parent = $cat_down['id_parent'];

if ($cat_down['name']) {
    $cat_info = array ();

    $db->query ("SELECT * FROM downloads_category ORDER BY position DESC");
    while ($row = $db->get_row ()) {
        $cat_info[$row['id']] = array ();

        foreach ($row as $key => $value) {
            $cat_info[$row['id']][$key] = stripslashes ($value);
        } 
    } 
    function downloads_cat_select2($categoryid = 0, $parentid = 0, $nocat = true, $sublevelmarker = '', $returnstring = '')
    {
        global $cat_info, $user_group, $member_id, $category_id;

        $root_category = array ();

        if ($parentid == 0) {
            if ($nocat) $returnstring .= '<option value="0"></option>';
        } else {
            $sublevelmarker .= '---';
        } 

        if (count($cat_info)) {
            foreach ($cat_info as $cats) {
                if ($cats['id_parent'] == $parentid) $root_category[] = $cats['id'];
            } 

            if (count($root_category)) {
                foreach ($root_category as $id) {
                    $returnstring .= "<option value=\"" . $id . '"';
                    if ($categoryid == $id and $categoryid != "") $returnstring .= ' style="color:#FF0000;"';
                    $returnstring .= '>' . $sublevelmarker . $cat_info[$id]['name'] . "</option>\n";

                    $returnstring = downloads_cat_select2($categoryid, $id, $nocat, $sublevelmarker, $returnstring);
                } 
            } 
        } 
        return $returnstring;
    } 

    $buffer .= <<<HTML
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=downloads&act_2=category_edit_save&submit=1">
<input type="hidden" name="cat_id" value="{$cat_down['id']}"><br>
Название категории:<br>
<input id="add" type="text" name="cat_name" value="{$cat_down['name']}"><br>
<input type="submit" value="Сохранить"></form></div>
<div class="post_add">
<form  method="post" action="{$PHP_SELF}?do=admin&act=downloads&act_2=category_edit_save&submit=2">
Основная категория:<br>
HTML;
    $buffer .= '<select name="cat_id">' . downloads_cat_select2($cat_down['id']) . '</select><br>';
    $buffer .= <<<HTML
<input type="hidden" name="cat_id2" value="{$cat_down['id']}">
<input type="submit" value="Сохранить"></form></div>
<div class="block"><a href="{$PHP_SELF}?do=admin&act=downloads&act_2=category">Вернуться назад</a></div>
HTML;
} else {
    @header ('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} 

?>