<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$profile_name = check_full($db->safesql($_REQUEST['id']));

$profile_result = $db->super_query("SELECT * FROM users WHERE user_id='$profile_name'");

if ($profile_result['user_id'] and (($profile_result['user_id'] == $user_id['user_id']) or $moder))
{
	$profile = array (
	'name' => $_POST['info_name'],
	'text' => $_POST['info_text'],
	'icq' => $_POST['icq'],
	'skype' => $_POST['skype'],
	'jabber' => $_POST['jabber'],
	'mail' => $_POST['mail'],
	'captcha' => $_POST['profile_captcha'],
	'key' => $_GET['profile_key'],
	);

	$profile['icq'] = intval(substr($profile['icq'], 0, 11));
	$profile['skype'] = substr($profile['skype'], 0, 50);
	$profile['jabber'] = substr($profile['jabber'], 0, 70);
	$profile['mail'] = substr($profile['mail'], 0, 70);
	$gav = true;
	$profile = array_map("check_full", $profile);
	$is_profile = 1;

	if ($is_profile == 1 and $sec_code and $_SESSION['sec_code'] != $profile['captcha'])
	{
		$error .= 'Введён неверный секретный код.';
		$is_profile++;
	}

	if ($is_profile == 1 and $_SESSION['sec_key_profile_1'] != $profile['key'])
	{
		$error .= 'Произошла ошибка сессии.';
		$is_profile++;
	}

	if ($profile['mail'])
	{
		$email = $profile['mail'];
		if ((!preg_match('/^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,6}$/', $email))or(empty($email)))
		{
			$error .= 'Поле Email содержит недопустимые знаки.';
			$is_profile++;
		}
	}

	if ($profile['jabber'])
	{
		$jabber = $profile['jabber'];
		if ((!preg_match('/^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,6}$/', $jabber))or(empty($jabber)))
		{
			$error .= 'Поле Jabber содержит недопустимые знаки.';
			$is_profile++;
		}
	}

	if ($is_profile == 1 and preg_match("/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $profile['name']))
	{
        $error .= 'Имя состоит из недопустимых символов. ';
        $is_profile++;
    } 

    if ($is_profile == 1 and $profile['name'] != '' and strlen($profile['name']) < 2) {
        $error .= 'Имя должно быть более 2 символов. ';
        $is_profile++;
    } 

    if ($is_profile == 1 and $profile['name'] != '' and strlen($profile['name']) > 15) {
        $error .= 'Имя должно быть не более 15 символов. ';
        $is_profile++;
    } 

    if ($is_profile == 1 and $profile['text'] != '' and strlen($profile['text']) < 10) {
        $error .= 'Информация о себе должена быть более 10 символов. ';
        $is_profile++;
    } 

    if ($is_profile == 1 and $profile['text'] != '' and strlen($profile['text']) > 1000) {
        $error .= 'Информация о себе должена быть не более 1000 символов. ';
        $is_profile++;
    } 

    if ($is_profile == 1 and flooder($_IP) == true) {
        $error .= 'Вы слишком часто изменяете профиль. Подождите ' . $config['flood_time'] . ' секунд. ';
        $is_profile++;
    } 
    $_SESSION['sec_key_profile_2'] = rv();
    if (isset($_SESSION['sec_key_profile_1'])) unset ($_SESSION['sec_key_profile_1']);
    if ($is_profile != 1) {
		$_SESSION['echo'] = $error;
		move($PHP_SELF.'?do=profile&name=' . $profile_result['name']);
    } else {
        $db->query("UPDATE users SET info_name='{$profile['name']}', info_text='{$profile['text']}', icq='{$profile['icq']}', skype='{$profile['skype']}', jabber='{$profile['jabber']}', mail='{$profile['mail']}' WHERE user_id='{$profile_result['user_id']}'");
        $db->query("INSERT INTO flood (id, ip) values ('$_TIME', '$_IP')");
        $db->free();
        if ($config['cache'] == '1') {
            clear_cache('main_counters');
            clear_cache('profile_count');
        }
		$_SESSION['echo'] = 'Профиль изменен.';
        move($PHP_SELF.'?do=profile&name=' . $profile_result['name']);
    } 
} else {
    $_SESSION['echo'] = 'Запрашиваемого вами пользователя несуществует или доступ в этот раздел вам закрыт.';
	move($PHP_SELF.'?do=profile&name=' . $profile_result['name']);
} 

$db->free();

?>