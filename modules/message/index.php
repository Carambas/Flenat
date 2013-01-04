<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
$act = totranslit (check_full($_REQUEST['act']));

$its_page = intval($config['message_str']);
$per_page = $its_page;
$its_mod = 'message';
$its_act = $act;
$its_cstart = intval($_REQUEST['cstart']);

$cstart = $its_cstart;

$stop_pm = false;
if (! $login) {
    info('Ошибка', 'Сообщения доступны только зарегистрированным. ');
    $stop_pm = true;
} 

$tpl->load_tpl('message.tpl');

if ($config['bbcode'] == 1) {
    $tpl->set('[bbcode]', '<a href="' . $PHP_SELF . '?do=others&act=bbcode">');
    $tpl->set('[/bbcode]', '</a>');
} else $tpl->set_block("'\\[bbcode\\](.*?)\\[/bbcode\\]'si", "");

if ($config['smiles'] == 1) {
    $tpl->set('[smiles]', '<a href="' . $PHP_SELF . '?do=others&act=smiles">');
    $tpl->set('[/smiles]', '</a>');
} else $tpl->set_block("'\\[smiles\\](.*?)\\[/smiles\\]'si", "");

$tpl->set('[inbox]', "<a href=\"$PHP_SELF?do=message&amp;act=inbox\">");
$tpl->set('[/inbox]', "</a>");
$tpl->set('[outbox]', "<a href=\"$PHP_SELF?do=message&amp;act=outbox\">");
$tpl->set('[/outbox]', "</a>");
$tpl->set('[new_pm]', "<a href=\"$PHP_SELF?do=message&amp;act=newpm\">");
$tpl->set('[/new_pm]', "</a>");

if (isset($_REQUEST['send']) and ! $stop_pm) {
    $name = $db->safesql(check(trim($_REQUEST['name'])));
    $subj = $db->safesql(check(trim($_REQUEST['subj'])));

    $stop = "";

    $comments = $db->safesql(check(trim($_REQUEST['comments'])));

    if (empty($name) or empty($subj) or $comments == "") $stop .= 'Все поля обязательны к заполнению. ';
    if (strlen($_REQUEST['subj']) < 5) $stop .= 'Тема сообщения должна быть больше 5 символов. ';
    if (strtolower($_REQUEST['name']) == strtolower($user_id['name'])) $stop .= 'Вы не можите отправлять сообщения самому себе. ';

    if (strlen($subj) > 200) {
        $stop .= 'Слишком длинный заголовок письма. ';
    } 

    if (flooder($_IP) == true) {
        $stop .= 'Вы слишком часто отправляете сообщения. Следующее сообщение можно отправить через ' . $config['flood_time'] . ' секунд. ';
    } 

    $db->query("SELECT  name, user_id, message_all FROM users where name = '$name'");

    if (! $db->num_rows()) $stop .= 'Получатель с таким именем не найден. ';

    $row = $db->get_row();
    $db->free();

    if (! $stop) {
        $time = time();
        $db->query("INSERT INTO flood (id, ip) values ('$_TIME', '$_IP')");
        $db->query("INSERT INTO message (subj, text, user, user_from, date, message_read, folder) values ('" . check_full($subj) . "', '" . check_full($comments) . "', '$row[user_id]', '$user_id[name]', '$time', 'no', 'inbox')");

        $db->query("UPDATE users set message_all=message_all+1, message_unread=message_unread+1  where user_id='$row[user_id]'");

        if (intval($_REQUEST['outboxcopy'])) {
            $db->query("INSERT INTO message (subj, text, user, user_from, date, message_read, folder) values ('" . check_full($subj) . "', '" . check_full($comments) . "', '$row[user_id]', '$user_id[name]', '$time', 'yes', 'outbox')");
            $db->query("UPDATE users set message_all=message_all+1 where user_id='$user_id[user_id]'");
        } 

        $replyid = intval($_REQUEST['replyid']);

        if ($replyid) {
            $db->query("UPDATE message SET reply=1 WHERE id= '$replyid'");
        } 

        info('Информация', 'Ваше сообщение было успешно отправлено.<br><a href="' . $PHP_SELF . '?do=message">Вернуться назад</a>');
        $stop_pm = true;
    } else
        info('Ошибка', $stop);
} 

if ($act == "del" and ! $stop_pm) {
    $delete_count = 0;

    if ($_REQUEST['allow_hash'] == "" or $_REQUEST['allow_hash'] != $login_hash) {
        die("Hacking attempt! User ID not valid");
    } 

    if ($_REQUEST['pmid']) {
        $pmid = $db->safesql($_REQUEST['pmid']);
        $row = $db->super_query("SELECT id, user, user_from, message_read, folder FROM message where id= '$pmid'");

        if (($row['user'] == $user_id['user_id'] and $row['folder'] == "inbox") or ($row['user_from'] == $user_id['name'] and $row['folder'] == "outbox")) {
            $db->query("DELETE FROM message WHERE id='$row[id]'");
            $delete_count ++;

            if ($row['message_read'] != "yes") {
                $db->query("UPDATE users set message_unread=message_unread-1 where user_id='$user_id[user_id]'");
            } 

            $db->query("UPDATE users set message_all=message_all-1 where user_id='$user_id[user_id]'");
        } 
    } elseif (count($_REQUEST['selected_pm'])) {
        foreach ($_REQUEST['selected_pm'] as $pmid) {
            $pmid = $db->safesql($pmid);
            $row = $db->super_query("SELECT id, user, user_from, message_read, folder FROM message where id= '$pmid'");

            if (($row['user'] == $user_id['user_id'] and $row['folder'] == "inbox") or ($row['user_from'] == $user_id['name'] and $row['folder'] == "outbox")) {
                $db->query("DELETE FROM message WHERE id='$row[id]'");

                $delete_count ++;

                if ($row['message_read'] != "yes") {
                    $db->query("UPDATE users set message_unread=message_unread-1 where user_id='$user_id[user_id]'");
                } 

                $db->query("UPDATE users set message_all=message_all-1 where user_id='$user_id[user_id]'");
            } 
        } 
    } 

    if ($delete_count) info('Информация', 'Сообщение успешно удалено.<br><a href="' . $PHP_SELF . '?do=message">Вернуться назад</a>');
    else info('Ошибка', 'Удаление персональных сообщений не было произведено. Либо Вы ничего не выбрали, либо у Вас нет на это прав.<br><a href="' . $PHP_SELF . '?do=message">Вернуться назад</a>');
    $is_back = 1;
} elseif ($act == "readpm" and ! $stop_pm) {
    $pmid = intval($_REQUEST['pmid']);

    $tpl->set('[readpm]', "");
    $tpl->set('[/readpm]', "");
    $tpl->set_block("'\\[pmlist\\].*?\\[/pmlist\\]'si", "");
    $tpl->set_block("'\\[newpm\\].*?\\[/newpm\\]'si", "");

    $db->query("SELECT * FROM message where id= '$pmid'");
    $row = $db->get_row();

    if ($db->num_rows() < 1) {
        info('Ошибка', 'Сообщение с таким номером не найдено<br><a href="' . $PHP_SELF . '?do=message">Вернуться назад</a>');
        $stop_pm = true;
    } elseif ($row['user'] != $user_id['user_id'] and $row['user_from'] != $user_id['name']) {
        info('Ошибка', 'У Вас нет прав просматривать чужие персональные сообщения. ');
        $stop_pm = true;
    } else {
        if ($row['user'] == $user_id['user_id'] and $row['message_read'] != "yes") {
            $db->query("UPDATE users set message_unread=message_unread-1  where user_id='$user_id[user_id]'");

            $db->query("UPDATE message set message_read='yes'  where id='$row[id]'");
        } 

        $tpl->set('{subj}', check_full($row['subj']));
        $tpl->set('{text}', iOpis(check($row['text'])));

        $tpl->set('{author}', "<a href=\"$PHP_SELF?do=profile&name=" . urlencode($row['user_from']) . "\">" . $row['user_from'] . "</a>");

        $tpl->set('[reply]', "<a href=\"$PHP_SELF?do=message&amp;act=newpm&amp;replyid=" . $row['id'] . "\">");
        $tpl->set('[/reply]', "</a>");

        $tpl->set('[del]', "<a href=\"$PHP_SELF?do=message&amp;act=del&amp;pmid=" . $row['id'] . "&amp;allow_hash=" . $login_hash . "\">");
        $tpl->set('[/del]', "</a>");

        $tpl->compile('content');
        $tpl->clear();
    } 
} elseif ($act == "newpm" and ! $stop_pm) {
    $it_title = 'Сообщения / Отправить';
    $tpl->set('[newpm]', $ajax_form);
    $tpl->set('[/newpm]', "");
    $tpl->set_block("'\\[pmlist\\].*?\\[/pmlist\\]'si", "");
    $tpl->set_block("'\\[readpm\\].*?\\[/readpm\\]'si", "");

    $replyid = intval($_REQUEST['replyid']);
    $user = intval($_REQUEST['user']);
    if (isset($_REQUEST['username'])) $username = $db->safesql(strip_tags(urldecode($_REQUEST['username'])));
    else $username = '';

    if ($replyid) {
        $row = $db->super_query("SELECT * FROM message where id= '$replyid'");

        if (($row['user'] != $user_id['user_id']) and ($row['user_from'] != $user_id['name'])) {
            info('Ошибка', 'У Вас нет прав просматривать чужие персональные сообщения. ');
            $stop_pm = true;
        } 

        $text = smiles(check($row['text']));
        $text = "[q]" . $text . "[/q]";

        $tpl->set('{author}', $row['user_from']);

        if (strpos ($row['subj'], "RE:") === false)
            $tpl->set('{subj}', "RE: " . check_full($row['subj']));
        else
            $tpl->set('{subj}', check_full($row['subj']));

        $tpl->set('{text}', $text);

        $row = $db->super_query("SELECT message_all FROM users WHERE name = '" . $db->safesql(check_full($row['user_from'])) . "'");
    } elseif ($user or $username != "") {
        if ($user) $row = $db->super_query("SELECT name, message_all FROM users where user_id = '$user'");
        elseif ($username != "") $row = $db->super_query("SELECT name, message_all FROM users where name='$username'");

        $tpl->set('{author}', check_full($row['name']));
        $tpl->set('{subj}', "");
        $tpl->set('{text}', "");
    } else {
        $tpl->set('{author}', "");
        $tpl->set('{subj}', "");
        $tpl->set('{text}', "");
    } 

    $tpl->copy_tpl = "<form  method=\"post\" action=\"\">\n" . $tpl->copy_tpl . "<input name=\"send\" type=\"hidden\" value=\"send\" /></form>";

    $tpl->compile('content');
    $tpl->clear();
} elseif (! $stop_pm) {
    $tpl->set('[pmlist]', "");
    $tpl->set('[/pmlist]', "");
    $tpl->set_block("'\\[newpm\\].*?\\[/newpm\\]'si", "");
    $tpl->set_block("'\\[readpm\\].*?\\[/readpm\\]'si", "");

    if ($user_id['message_unread'] < 0) {
        $db->query("UPDATE users SET message_unread='0' WHERE user_id='{$user_id['user_id']}'");
    } 

    $pmlist = <<<HTML
<form action="$PHP_SELF?do=message&act=del" method="post" name="pmlist">
<input type="hidden" name="allow_hash" value="{$login_hash}" />
HTML;

    if ($act == "outbox") {
        $it_title = 'Сообщения / Исходящие';
        $all_in = $db->super_query("SELECT count(*) as count FROM message WHERE user_from = '{$user_id['name']}' AND folder = 'outbox'");
		$its_all = $all_in['count'];
		$page = new page($its_all, $config['page']);
		$sql = $db->query("SELECT * FROM message LEFT JOIN users ON message.user=users.user_id WHERE user_from = '{$user_id['name']}' AND folder = 'outbox' order by date desc LIMIT ".$page->go.", ".$config['page']."");
    } else {
        $it_title = 'Сообщения / Входящие';
        $all_in = $db->super_query("SELECT count(*) as count FROM message WHERE user = '{$user_id['user_id']}' AND folder = 'inbox'");
		$its_all = $all_in['count'];
		$page = new page($its_all, $config['page']);
		$sql = $db->query("SELECT * FROM message where user = '{$user_id['user_id']}' AND folder = 'inbox' order by date desc  LIMIT ".$page->go.", ".$config['page']."");
    } 

    $pmlist .= "";
    $i = 0;

    while ($row = $db->get_row($sql)) {
        $i ++;

        $user_from = "<a class=\"pm_list\" href=\"$PHP_SELF?do=profile&name=" . urlencode($row['user_from']) . "\">" . $row['user_from'] . "</a>";
        $text_o = $row['subj'];
		$text_t = $row['text'];
		
		$ava_result = $db->super_query("SELECT * FROM users WHERE name = '{$row['user_from']}' LIMIT 1");
		$avatar = "<img src=\"".$config['home_url']."uploads/avatars/".$ava_result['avatar']."\"/>";

        if (strlen($text_o) < 15) {
            $text_opis = $row['subj'];
        } else {
            $text_opis = $row['subj'];
            $text_opis = substr($text_opis, 0 , 15) . "...";
        }
		
		if (strlen($text_t) < 150) {
            $text_text = $row['text'];
        } else {
            $text_text = $row['text'];
            $text_text = substr($text_text, 0 , 150) . "...";
        } 
		
        if ($row['message_read'] == "yes") {
            $icon = "";
        } else {
            $icon = "<font color=\"red\"><b>New</b></font> ";
        }
		$subj = stripslashes($text_opis);
		$text = "<a href=\"$PHP_SELF?do=message&act=readpm&pmid=" . $row['id'] . "\">" . stripslashes($text_text) . "</a>"; 

        $pmlist .= "<div class=\"post\">
		<div class=\"i\">{$avatar}</div>
		<div class=\"cont\"><span class=\"user\"><em>" .iTime($row['date']) . "</em>
		<input name=\"selected_pm[]\" value=\"{$row['id']}\" type=\"checkbox\">{$icon} {$user_from}<br/>
		<strong>{$subj}</strong><br/>
		{$text}
		</div>
		</div>";
    } 

    $db->free();

    $pmlist .= "<input type=\"submit\" value=\"Удалить выбраные\"></form>";

    if ($i) {
        $tpl->set('{pmlist}', $pmlist);
    } else {
        $tpl->set('{pmlist}', '<div class="text">Персональных сообщений ненайдено.</div>');
    } 
	$tpl->copy_tpl .= $page->listing($PHP_SELF.'?do=message');
    $tpl->compile('content');
    $tpl->clear();
} 

?>