<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

switch ($_REQUEST['act']) {
    case "page" :
        include ROOT_DIR . '/modules/pages/page.php';
        break;

    default :
        if ($config['cache'] == '1') {
            $its_all = cache("pages_count");

            if ($its_all == "") {
                $all_mod = $db->super_query("SELECT COUNT(*) as count FROM pages");
                $its_all = $all_mod['count'];
                create_cache("pages_count", $its_all);
            }
        } else {
            $all_mod = $db->super_query("SELECT COUNT(*) as count FROM  pages");
            $its_all = $all_mod['count'];
        } 

        if ($its_all == 0) info('Информация', 'Страницы не найдены.');
		
		$page = new page($all_all, $config['page']);
		
        $pages_sql = $db->query("SELECT * FROM pages ORDER BY id DESC LIMIT ".$page->go.", ".$config['page']."");

        while ($row = $db->get_row($pages_sql)) {
            $tpl->load_tpl('pages.tpl');
			$tpl->set_block("'\\[view_page\\](.*?)\\[/view_page\\]'si", "");
			$tpl->set('[pages]', '');
			$tpl->set('[/pages]', '');
			$tpl->set('[title]', '<a href="' . $PHP_SELF . '?do=pages&act=page&id=' . $row['id'] . '">');
			$tpl->set('[/title]', '</a>');
            $tpl->set('{count}', intval($row['count']));
            $tpl->set('{name}', check_full($row['name']));
            $tpl->compile('content');
            $tpl->clear();
        } 
		$tpl->copy_tpl = $page->listing($PHP_SELF.'?do=pages');
        $tpl->compile('content');
        $tpl->clear();
        $db->free();
} 

?>