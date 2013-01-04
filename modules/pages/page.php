<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$page = intval ($_REQUEST['id']);

if ($page < 0) $page = '';

$static_result = $db->super_query("SELECT * FROM pages WHERE id='$page'");

if ($static_result['id']) {
	if ($_SESSION['page'] !== $page) { 
		$_SESSION['page'] = $page; 
		$db->query("UPDATE pages set count=count+1 where id='{$page}'");
	}

    $it_title = check_full($static_result['name']);
	$tpl->load_tpl('pages.tpl');
	$tpl->set_block("'\\[pages\\](.*?)\\[/pages\\]'si", "");
	$tpl->set('[view_page]', '');
	$tpl->set('[/view_page]', '');
	$tpl->set('{text}', iOpis($static_result['text']));
    $tpl->compile('content');
    $tpl->clear();
} else {
    info('Ошибка', 'Запрашиваемой вами страницы несуществует.');
} 

?>