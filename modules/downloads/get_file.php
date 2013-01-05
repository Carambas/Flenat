<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

$id = intval($_REQUEST['id']);

$coun_file = $db->super_query("SELECT * FROM downloads_files WHERE id = '{$id}'");

if (! $login and $config['guest_down'] == 0) {
	info('Запрещено', 'Загрузка файлов для гостей закрыта. Пожалуйста <a href="' . $PHP_SELF . '?do=registration">зарегистрируйтесь</a>.');
} else {
	if($config['bad_link'] == '0'){
		if ($coun_file['id']) {
			header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
			header('Content-type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $coun_file['file'] . '');
			header('Location: ' . $config['home_url'] . 'uploads/downloads/files/' . $coun_file['file'] . '');

			$db->query("UPDATE downloads_files SET count = count+1 WHERE id ='" . $id . "'");
			if($login) $db->query("UPDATE users SET count_down = count_down+1 WHERE user_id ='" . $user_id['user_id'] . "'");
		} else {
			info('Ошибка', 'Запрошенный файл несуществует. ');
			$tpl->copy_tpl = '<div class="block"><a href="javascript:history.go(-1)">Вернуться назад</a></div>';
			$tpl->compile('content');
			$tpl->clear();
		} 
	} else{
		function force_download($filename = '', $data = '', $prefix = '', $attachment = TRUE) {
			if ($filename == '' OR $data == '') return FALSE;
			if (FALSE === strpos($filename, '.')) return FALSE;

			$x = explode('.', $filename);
			$extension = end($x);

			@include('mimes.php');

			if ( ! isset($mimes[$extension])) $mime = 'application/octet-stream';
			else $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];

			if(!$attachment) {
				header('Content-Type: '.$mime);
				header("Content-Length: ".strlen($data));
			} else {
				if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
					header('Content-Type: '.$mime);
					header('Content-Disposition: attachment; filename='.$prefix . $filename);
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header("Content-Transfer-Encoding: binary");
					header('Pragma: public');
					header("Content-Length: ".strlen($data));
				} else {
					header('Content-Type: '.$mime);
					header('Content-Disposition: attachment; filename='.$prefix . $filename);
					header("Content-Transfer-Encoding: binary");
					header('Expires: 0');
					header('Pragma: no-cache');
					header("Content-Length: ".strlen($data));
				}
			}
			exit($data);
		}

		$File = $coun_file['file'];

		if ($coun_file['id']) {
			
			$db->query("UPDATE downloads_files SET count = count+1 WHERE id ='" . $id . "'");
			if($login) $db->query("UPDATE users SET count_down = count_down+1 WHERE user_id ='" . $user_id['user_id'] . "'");
			
			$File_new_name = substr_replace($File, '', 0, 10);
			$Content = @file_get_contents(ROOT_DIR . '/uploads/downloads/files/'.$File);
			force_download($File_new_name, $Content, false, true);
			
			if (FALSE === strpos($File_new_name, '.')) return FALSE;

			$x = explode('.', $File_new_name);
			$extension = end($x);

			@include(ROOT_DIR . '/modules/downloads/mimes.php');

			if ( ! isset($mimes[$extension])) $mime = 'application/octet-stream';
			else $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];

			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
				header('Content-Type: '.$mime);
				header('Content-Disposition: attachment; filename='.$File_new_name);
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header("Content-Transfer-Encoding: binary");
				header('Pragma: public');
				header("Content-Length: ".strlen($Content));
			} else {
				header('Content-Type: '.$mime);
				header('Content-Disposition: attachment; filename='.$File_new_name);
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0');
				header('Pragma: no-cache');
				header("Content-Length: ".strlen($Content));
			}
		} else {
			info('Ошибка', 'Запрошенный файл несуществует. ');
			$tpl->copy_tpl = '<div class="block"><a href="javascript:history.go(-1)">Вернуться назад</a></div>';
			$tpl->compile('content');
			$tpl->clear();
		}
	}
}
?>
