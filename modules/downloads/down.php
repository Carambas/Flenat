<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$category = intval($_GET['category']);

if ($category != 0) {
    $tpl->load_tpl('downloads.tpl');

    $tpl->set_block("'\\[category\\](.*?)\\[/category\\]'si", "");
    $tpl->set_block("'\\[file_list\\](.*?)\\[/file_list\\]'si", "");
    $tpl->set('[cat_navigation]', '');
    $tpl->set('[/cat_navigation]', '');
    $tpl->set_block("'\\[file_info\\](.*?)\\[/file_info\\]'si", "");
    $tpl->set('{navigation}', name_cat($category));

    $tpl->compile('content');
    $tpl->clear();
} 

if ($category) {
    $where_cat = "WHERE id_parent = '$category'";
    $where_files = "WHERE category = '$category'";
} else {
    $where_cat = "WHERE id_parent = 0";
    $where_files = "WHERE category = 0";
}

$downloads_cat_sql = $db->query("SELECT * FROM downloads_category $where_cat ORDER BY position DESC");

$i = 0;
while ($row = $db->get_row($downloads_cat_sql)) {
    $i ++;
    $tpl->load_tpl('downloads.tpl');
    $tpl->set_block("'\\[cat_navigation\\](.*?)\\[/cat_navigation\\]'si", "");
    $tpl->set_block("'\\[file_list\\](.*?)\\[/file_list\\]'si", "");
    $tpl->set_block("'\\[file_info\\](.*?)\\[/file_info\\]'si", "");
    $tpl->set('[category]', '');
    $tpl->set('[/category]', '');

    if ($config['cache'] == '1') {
        $coun_file = cache("files_count_" . $row['id']);

        if ($coun_file == "") {
            $coun_file_db = $db->super_query("SELECT count(*) as count FROM downloads_files WHERE category = '{$row['id']}'");
            $coun_file = $coun_file_db['count'];
            create_cache("files_count_" . $row['id'], $coun_file);
        } 

        $coun_cat = cache("files_cat_" . $row['id']);

        if ($coun_cat == "") {
            $coun_cat_db = $db->super_query("SELECT count(*) as count FROM downloads_category WHERE id_parent = '{$row['id']}'");
            $coun_cat = $coun_cat_db['count'];
            create_cache("files_cat_" . $row['id'], $coun_cat);
        } 
    } else {
        $coun_file_db = $db->super_query("SELECT count(*) as count FROM downloads_files WHERE category = '{$row['id']}'");
        $coun_file = $coun_file_db['count'];
        $coun_cat_db = $db->super_query("SELECT count(*) as count FROM downloads_category WHERE id_parent = '{$row['id']}'");
        $coun_cat = $coun_cat_db['count'];
    } 

    $tpl->set('[cat_link]', '<a href="' . $PHP_SELF . '?do=downloads&category=' . $row['id'] . '">');
    $tpl->set('[/cat_link]', '</a>');

    if ($coun_file > 0 and $coun_cat > 0) {
        $tpl->set('{count}', 'К: ' . $coun_cat . ' / Ф: ' . $coun_file);
    } else {
        if ($coun_file['count'] > 0) {
            $tpl->set('{count}', 'Ф: ' . $coun_file);
        } else {
            if ($coun_cat['count'] > 0) {
                $tpl->set('{count}', 'К: ' . $coun_cat);
            } else {
                $tpl->set('{count}', 'Пусто');
            } 
        } 
    } 

    $tpl->set('{category}', check_full($row['name']));
    $tpl->compile('content');
    $tpl->clear();
} 

$all_mod = $db->super_query("SELECT COUNT(*) as count FROM downloads_files $where_files");
$its_all = $all_mod['count'];

$page = new page($its_all, $config['page']);

$downloads_files_sql = $db->query("SELECT * FROM downloads_files $where_files ORDER BY date DESC LIMIT ".$page->go.", ".$config['page']."");

$b = 0;
while ($row = $db->get_row($downloads_files_sql)) {
    $tpl->load_tpl('downloads.tpl');
    $tpl->set_block("'\\[file_info\\](.*?)\\[/file_info\\]'si", "");
    $tpl->set_block("'\\[cat_navigation\\](.*?)\\[/cat_navigation\\]'si", "");
    $tpl->set_block("'\\[category\\](.*?)\\[/category\\]'si", "");
    $tpl->set('[file_list]', '');
    $tpl->set('[/file_list]', '');

    $tpl->set('[title_link]', '<a href="' . $PHP_SELF . '?do=downloads&act=file_info&id=' . $row['id'] . '">');
    $tpl->set('[/title_link]', '</a>');

    $tpl->set('{title}', check_full($row['title']));
    $tpl->set('[title]', '<a href="' . $PHP_SELF . '?do=downloads&act=get_file&id=' . $row['id'] . '">');
    $tpl->set('[/title]', '</a>');

    $tpl->set('{file_count}', $row['count']);

    if (strlen($row['description']) < $config['description_ext']) {
        $tpl->set('{description}', iOpis($row['description']));
    } else {
        $text_opis_obrez = substr($row['description'], 0, $config['description_ext']) . '...';
        $tpl->set('{description}', iOpis($text_opis_obrez));
    } 

    $tpl->set('{comm_count}', $row['count_comm']);

    if ($row['screen'] != "") {
        $tpl->set('{screen}', '<img src="' . $config['home_url'] . 'uploads/downloads/screen_small/' . $row['screen'] . '" alt="' . check_full($row['title']) . '">');
        $tpl->set('[screen]', '<a href="' . $config['home_url'] . 'uploads/downloads/screen/' . $row['screen'] . '">');
        $tpl->set('[/screen]', '</a>');
    } else {
        $tpl->set('{screen}', '<img src="' . $config['home_url'] . 'uploads/avatars/guest_avatar.png" alt="no_screen" />');
        $tpl->set('[screen]', '');
        $tpl->set('[/screen]', '');
    } 

    $tpl->set('{author}', '<a href="' . $PHP_SELF . '?do=profile&name=' . check_full($row['author']) . '">' . check_full($row['author']) . '</a>');

	$tpl->set('{date}', iTime($row['date']));

    $tpl->compile('content');
	$tpl->clear();
	$b++;
}

if ($i == 0 and $b == 0) {
    $tpl->copy_tpl .= '<div class="text">В данной категории ничего нет.</div>';
} 

$tpl->copy_tpl .= $page->listing($PHP_SELF.'?do=downloads&category='.$category);
$tpl->compile('content');
$tpl->clear();
?>