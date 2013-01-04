<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$id = intval($_GET['id']);

$row = $db->super_query("SELECT * FROM downloads_files WHERE id='$id'");

if ($row['id']) {

    $tpl->load_tpl('downloads.tpl');

    $tpl->set_block("'\\[category\\](.*?)\\[/category\\]'si", "");
    $tpl->set_block("'\\[file_list\\](.*?)\\[/file_list\\]'si", "");
    $tpl->set_block("'\\[cat_navigation\\](.*?)\\[/cat_navigation\\]'si", "");

    $tpl->set('[file_info]', '');
    $tpl->set('[/file_info]', '');

    $category = $db->super_query("SELECT * FROM  downloads_category WHERE id='" . $row['category'] . "'");

    $tpl->set('[cat_link]', '<a href="' . $PHP_SELF . '?do=downloads&category=' . $row['category'] . '">');
    $tpl->set('{cat_title}', $category['name']);
    $tpl->set('[/cat_link]', '</a>');

    $tpl->set('[title_link]', '');
    $tpl->set('[/title_link]', '');

    $title = check_full($row['title']);
    $tpl->set('{title}', $title);

    if (! $login and $config['guest_down'] == 0) {
        $tpl->set_block("'\\[title\\](.*?)\\[/title\\]'si", '<a href="' . $PHP_SELF . '?do=registration">Зарегистрируйтесь для загрузки</a>');
    } else {
        $tpl->set('[title]', '<a href="' . $PHP_SELF . '?do=downloads&act=get_file&id=' . $row['id'] . '">');
        $tpl->set('[/title]', '</a>');
    } 

    $tpl->set('{file_count}', intval($row['count']));

    $tpl->set('{description}', iOpis($row['description']));

    if ($row['screen'] != "") {
        $tpl->set('{screen}', '<img src="' . $config['home_url'] . 'uploads/downloads/screen_small/' . $row['screen'] . '" alt="' . check_full($row['title']) . '">');
        $tpl->set('[screen]', '<a href="' . $config['home_url'] . 'uploads/downloads/screen/' . $row['screen'] . '">');
        $tpl->set('[/screen]', '</a>');
    } else {
        $tpl->set('{screen}', '<img src="' . $config['home_url'] . 'uploads/avatars/guest_avatar.png" alt="no_screen" />');
        $tpl->set('[screen]', '');
        $tpl->set('[/screen]', '');
    } 
    $tpl->set('{comm_count}', $row['count_comm']);

    $tpl->set('{author}', '<a href="' . $PHP_SELF . '?do=profile&name=' . check_full($row['author']) . '">' . check_full($row['author']) . '</a>');

	$tpl->set('{date}', iTime($row['date']));
    // ////// Разрешено ли показывать похожие файлы
    if ($config['allow_files'] == 1) {
        // ////// Получаем кэш похожих файлов для файла
        $buffer_cache = get_vars('allow_files_' . $id);

        if (!$buffer_cache) {
            $sql_result = $db->query("SELECT id, title, description, date  FROM downloads_files WHERE MATCH title, description AGAINST ('$title' IN BOOLEAN MODE) AND id != " . $id . " AND approve = '1' LIMIT 5");

            while ($related = $db->get_row($sql_result)) {
                if (strlen($related['title']) > 75)$related['title'] = substr($related['title'], 0, 75) . ' ...';
                $buffer_cache .= '<small> - <a href="' . $PHP_SELF . '?do=downloads&act=file_info&id=' . $related['id'] . '">' . check_full($related['title']) . '</a></small><br>';
            } 
            set_vars('allow_files_' . $id, $buffer_cache);
        } 

        $tpl->set('[related_files]', '');
        $tpl->set('[/related_files]', '');
        if ($buffer_cache != '')$tpl->set('{related_files}', $buffer_cache);
        else $tpl->set('{related_files}', 'Похожих файлов не найдено.');
    } else {
        $tpl->set_block("'\\[related_files\\](.*?)\\[/related_files\\]'si", "");
    } 
    $tpl->compile('content');
    $tpl->clear();

	$comm = new comments('downloads_comments', $id, $PHP_SELF.'?do=downloads&act=file_info&id='.$id);
	$comm->add_ref('downloads_files', 'count_comms', $id);
	
	if (isset($_POST['comm_text']) and $login) $comm->add($user_id['user_id'], $_POST['comm_text'], $_TIME, true);
	
	$comm->add_form($id);
	$comm->listing($PHP_SELF.'?do=downloads&act=file_info&id='.$id, true);
} else info('Ошибка', 'Запрашиваемого файла несуществует.');
?>