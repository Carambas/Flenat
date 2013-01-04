<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$profile_name = check_full($db->safesql($_REQUEST['name']));

$profile_result = $db->super_query("SELECT * FROM users WHERE name='$profile_name'");
  
if ($profile_result['user_id'] and (($profile_result['user_id'] == $user_id['user_id']) or $moder)) {
	$file = $_FILES['avatar']['name'];
	$type = strtolower(substr($file, 1 + strrpos($file,".")));
	$check = explode (",", $config['good_files_screen']);
	if (in_array($type, $check)) {
		if ($type == 'jpg') $src = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
		elseif ($type == 'jpeg') $src = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
		elseif ($type == 'png') $src = imagecreatefrompng($_FILES['avatar']['tmp_name']);
		elseif ($type == 'gif') $src = imagecreatefromgif($_FILES['avatar']['tmp_name']);
	} else {
		$_SESSION['echo'] = 'Ошибка установки аватара.';
		move($PHP_SELF.'?do=profile&name=' . $profile_name);
	}
	$imagew = imagesx($src);
	$imageh = imagesy($src);
	$avatar = $profile_name.'.gif';
	$path = ROOT_DIR . '/uploads/avatars/'.$avatar;
	$size = '48';
	$dest = imagecreatetruecolor($size,$size);
	imagecopyresampled($dest, $src, 0, 0, 0, 0, $size, $size, $imagew, $imageh);
	imagegif($dest,$path,$size);
	imagedestroy($dest);
	$screen_avatar = check_full($screen_name);
	$db->query("UPDATE users SET avatar = '$avatar' WHERE name = '$profile_name'");
	$_SESSION['echo'] = 'Аватар установлен.';
	move($PHP_SELF.'?do=profile&name=' . $profile_name);
} else {
    $_SESSION['echo'] = 'Запрашиваемого вами пользователя несуществует или доступ в этот раздел вам закрыт.';
	move($PHP_SELF.'?do=profile&name=' . $profile_name);
}
?>