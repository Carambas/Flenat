<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$act = totranslit (check_full($_REQUEST['act']));

function convertorderbyin($orderby)
{
    switch ($orderby) {
        case "skill" :
            $orderby = "user_skill DESC";
            break;
        case "life" :
            $orderby = "life_time DESC";
            break;
        default :
            $orderby = "reg_date DESC";
    } 
    return $orderby;
} 

if (isset($_REQUEST['orderby'])) {
    $orderby = convertorderbyin($_REQUEST['orderby']);
    $postfix = "&orderby=" . check_full($_REQUEST['orderby']);
} else {
    $postfix = "";
    $orderby = "reg_date DESC";
} 

if ($config['cache'] == '1') {
    $its_all = cache("members_count");
    if ($its_all == "") {
        $all_mod = $db->super_query("SELECT COUNT(*) as count FROM users");
        $its_all = $all_mod['count'];
        create_cache("members_count", $its_all);
    } 
} else {
    $all_mod = $db->super_query("SELECT COUNT(*) as count FROM users");
    $its_all = $all_mod['count'];
}

$search .= '<form method="get"><input type="hidden" value="members" name="do">
<input name="searchname"> <input type="submit" value="Поиск"></form>';

$tpl->load_tpl('members.tpl');
$tpl->set_block("'\\[members\\](.*?)\\[/members\\]'si", "");
$tpl->set ('[navigation]', '');
$tpl->set ('[/navigation]', '');

$tpl->set ('[sort_skill]', '<a href="' . $config['home_url'] . '?do=members&orderby=skill">');
$tpl->set ('[/sort_skill]', '</a>');

$tpl->set ('[sort_life]', '<a href="' . $config['home_url'] . '?do=members&orderby=life">');
$tpl->set ('[/sort_life]', '</a>');

$tpl->set ('[sort_view_all]', '<a href="' . $PHP_SELF . '?do=members">');
$tpl->set ('[/sort_view_all]', '</a>');

$tpl->set ('{all_members}', $its_all);

$tpl->set ('{search}', $search);

$tpl->compile('content');
$tpl->clear();

$orderby = $db->safesql(check_full($orderby));

$page = new page($all_all, $config['page']);

if (isset($_REQUEST['searchname'])) {
    $_REQUEST['searchname'] = $db->safesql(check_full($_REQUEST['searchname']));
    $postfix .= "&searchname={$_REQUEST['searchname']}";
    $members_sql = $db->query("SELECT * FROM users WHERE name LIKE '%{$_REQUEST['searchname']}%' ORDER BY $orderby  LIMIT ".$page->go.", ".$config['page']."");
    $row = $db->super_query("SELECT COUNT(*) as count FROM users WHERE name LIKE '%{$_REQUEST['searchname']}%'");
    $its_all = $row['count'];
} else {
    $members_sql = $db->query("SELECT * FROM users ORDER BY $orderby  LIMIT ".$page->go.", ".$config['page']."");
} 

$entries_showed = 0;
$entries = "";

while ($row = $db->get_row($members_sql)) {
    $tpl->load_tpl('members.tpl');
    $tpl->set_block("'\\[navigation\\](.*?)\\[/navigation\\]'si", "");
    $tpl->set ('[members]', '');
    $tpl->set ('[/members]', '');

    $i++;

    $tpl->set('{usertitle}', '<a href="' . $PHP_SELF . '?do=profile&name=' . urlencode(stripslashes($row['name'])) . '">' . stripslashes($row['name']) . '</a>');
	$tpl->set('{registration}', iTime($row['reg_date']));
	$tpl->set('{lastdate}', iTime($row['lastdate'])); 

    $tpl->set('{avatar}', $config['home_url'].'uploads/avatars/'.$row['avatar']);

    $entries_showed ++;

    $tpl->compile('content');
    $tpl->clear();
} 

if ($entries_showed == 0) $tpl->copy_tpl = 'К сожалению ни одного пользователя ненайдено.<br><br>';
$tpl->copy_tpl = $page->listing($PHP_SELF.'?do=members');
$tpl->compile('content');
$tpl->clear();
?>
