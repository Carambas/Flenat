<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$id = intval ($_REQUEST['id']);

$row = $db->super_query("SELECT * FROM news WHERE id='$id'");

if ($row['id'])
{
	if ($_SESSION['news_{$id}'] !== $id) { 
		$_SESSION['news_{$id}'] = $id; 
		$db->query("UPDATE news set count=count+1 where id='{$id}'");
	}
	$tpl->load_tpl('news.tpl');
	$tpl->set_block("'\\[news_small\\](.*?)\\[/news_small\\]'si", "");
	$tpl->set('[news_full]', '');
	$tpl->set('[/news_full]', '');

	$tpl->set('{title}', check_full($row['name']));
	$tpl->set('{text_full}', iOpis($row['text_full']));
	$tpl->set('{date}', iTime($row['date']));
	$tpl->set('{count_comms}', intval($row['count_comms']));

	$tpl->compile('content');
	$tpl->clear();

	$comm = new comments('news_comments', $id, $PHP_SELF.'?do=news&act=news&id='.$id);
	$comm->add_ref('news', 'count_comms', $id);

	if (isset($_POST['comm_text']) and $login) $comm->add($user_id['user_id'], $_POST['comm_text'], $_TIME, true);

	$comm->add_form($id);
	$comm->listing($PHP_SELF.'?do=news&act=news&id='.$id, true);
} else info('Ошибка', 'Запрашиваемой вами новости несуществует.');
?>