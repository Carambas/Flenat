<?php
@session_start ();
@ob_start ();
@error_reporting (E_ALL ^ E_NOTICE);

define ('_ENGINE_', true);
define ('ROOT_DIR', dirname (__FILE__));
define ('ENGINE_DIR', dirname (__FILE__) . '/engine');

if (file_exists(ROOT_DIR . "/config.php")) include (ROOT_DIR . '/config.php');

function rv()
{
	$var = rand(111111111, 999999999);
	return $var;
}

function formatsize($file_size)
{
	if ($file_size >= 1073741824) {
		$file_size = round($file_size / 1073741824 * 100) / 100 . " Gb";
	} elseif ($file_size >= 1048576) {
		$file_size = round($file_size / 1048576 * 100) / 100 . " Mb";
	} elseif ($file_size >= 1024) {
		$file_size = round($file_size / 1024 * 100) / 100 . " Kb";
	} else {
		$file_size = $file_size . " b";
	}
	return $file_size;
}

define ("DBHOST", $config['dbhost']);
define ("DBNAME", $config['dbname']);
define ("DBUSER", $config['dbuser']);
define ("DBPASS", $config['dbpass']);
define ("COLLATE", $config['dbcollate']);

class db {
	var $db_id = false;
	var $connected = false;
	var $query_num = 0;
	var $query_list = array();
	var $mysql_error = '';
	var $mysql_version = '';
	var $mysql_error_num = 0;
	var $mysql_extend = "MySQL";
	var $MySQL_time_taken = 0;
	var $query_id = false;

	function connect($db_user, $db_pass, $db_name, $db_location = 'localhost', $show_error = 1)
	{
		if (!$this->db_id = @mysql_connect($db_location, $db_user, $db_pass)) {
			if ($show_error == 1) {
				$this->display_error(mysql_error(), mysql_errno());
			} else {
				return false;
			}
		}

		if (!@mysql_select_db($db_name, $this->db_id)) {
			if ($show_error == 1) {
				$this->display_error(mysql_error(), mysql_errno());
			} else {
				return false;
			}
		}

		$this->mysql_version = mysql_get_server_info();

		if (!defined('COLLATE')) {
			define ("COLLATE", "utf8");
		} 

		if (version_compare($this->mysql_version, '4.1', ">=")) mysql_query("/*!40101 SET NAMES '" . COLLATE . "' */");

		$this->connected = true;

		return true;
	}

	function query($query, $show_error = true)
	{
		$time_before = $this->get_real_time();

		if (!$this->connected) $this->connect(DBUSER, DBPASS, DBNAME, DBHOST);

		if (!($this->query_id = mysql_query($query, $this->db_id))) {
			$this->mysql_error = mysql_error();
			$this->mysql_error_num = mysql_errno();

			if ($show_error) {
				$this->display_error($this->mysql_error, $this->mysql_error_num, $query);
			}
		}

		$this->MySQL_time_taken += $this->get_real_time() - $time_before;
		$this->query_num ++;

		return $this->query_id;
	}

	function get_row($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysql_fetch_assoc($query_id);
	}

	function get_array($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysql_fetch_array($query_id);
	}

	function super_query($query, $multi = false)
	{
		if (!$multi) {
			$this->query($query);
			$data = $this->get_row();
			$this->free();
			return $data;
		} else {
			$this->query($query);
			$rows = array();
			while ($row = $this->get_row()) {
				$rows[] = $row;
			}
			$this->free();
			return $rows;
		}
	}

	function num_rows($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;
		return mysql_num_rows($query_id);
	}

	function insert_id()
	{
		return mysql_insert_id($this->db_id);
	} 

	function get_result_fields($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;
		while ($field = mysql_fetch_field($query_id)) {
			$fields[] = $field;
		}
		return $fields;
	}

	function safesql($source)
	{
		if ($this->db_id) return mysql_real_escape_string ($source, $this->db_id);
		else return mysql_escape_string($source);
	}

	function free($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;
        @mysql_free_result($query_id);
	}

	function close()
	{
		@mysql_close($this->db_id);
	}

	function get_real_time()
	{
		list($seconds, $microSeconds) = explode(' ', microtime());
		return ((float)$seconds + (float)$microSeconds);
	}

	function display_error($error, $error_num, $query = '')
	{
		if ($query) {
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query);
			$query_str = "$query";
		}

		echo '<?xml version="1.0" encoding="iso-8859-1"?>
		<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
		<head>
		<title>MySQL Fatal Error</title>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
		<style type="text/css">
		<!--
		body {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 10px;
			font-style: normal;
			color: #000000;
		}
		-->
		</style>
		</head>
		<body>
			<font size="4">MySQL Error!</font> 
			<br />------------------------<br />
			<br />
			
			<u>The Error returned was:</u> 
			<br />
				<strong>' . $error . '</strong>

			<br /><br />
			</strong><u>Error Number:</u> 
			<br />
				<strong>' . $error_num . '</strong>
			<br />
				<br />
			
			<textarea name="" rows="10" cols="52" wrap="virtual">' . $query_str . '</textarea><br />

		</body>
		</html>';

		exit();
	}
}

$db = new db;

$PHP_SELF = $config['home_url'] . "index.php";
$_TIME = time();

echo <<<HTML
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<title>Install / Flenat Engine</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="/templates/default/style.css">
</head>
<body>
<div class="head">
<a href="/install.php">Flenat Engine</a>
</div>
<div class="title">Установка сайта</div>
<div class="content">
HTML;

switch ($_REQUEST['act']) {
	case "1" :
		$_SESSION['sec_key_install_2'] = rv();

		if ($_REQUEST['key'] == $_SESSION['sec_key_install_1'] and $_REQUEST['key'] != '') {
			echo <<<HTML
<div class="post_add">
<form method="post" action="../install.php?act=2&key={$_SESSION['sec_key_install_2']}">
<b>Настройки базы:</b><br>
Cервер ДБ:<br><input id="add" type="text" name="dbserv" value="localhost"><br>
Название ДБ:<br><input id="add" type="text" name="dbname"><br>
Пользователь ДБ:<br><input id="add" type="text" name="dbuser"><br>
Пароль  ДБ:<br><input id="add" type="text" name="dbpass"><br><br>
<b>Настройки системы:</b><br>
Адрес сайта:<br><input id="add" type="text" name="home_url" value="http://{$_SERVER['HTTP_HOST']}/"><br>
<br><input type="submit" value="Продолжить"></form></div>
HTML;
		} else {
			echo'<div class="text">Произошла ошибка сессии.</div>';
			echo'<div class="block"><a href="../install.php">Вернуться назад</a></div>';
		}

		if (isset($_SESSION[sec_key_install_1])) unset ($_SESSION[sec_key_install_1]);
		if (isset($_SESSION[sec_key_install_3])) unset ($_SESSION[sec_key_install_3]);
		if (isset($_SESSION[sec_key_install_4])) unset ($_SESSION[sec_key_install_4]);

		break;

	case "2" :
        $_SESSION['sec_key_install_3'] = rv();

		if ($_SESSION['sec_key_install_2'] == $_REQUEST['key'] and $_REQUEST['key'] != '') {
			if (!$_REQUEST['dbuser'] or !$_REQUEST['dbname'] or !$_REQUEST['home_url']) {
				echo'<div class="text">Все поля обязательны к заполнению.</div>';
				echo'<div class="block"><a href="../install.php">Вернуться назад</a></div>';
			} else {
				$connect = @mysql_connect($_REQUEST['dbserv'], $_REQUEST['dbuser'], $_REQUEST['dbpass']);
				$db = @mysql_select_db($_REQUEST['dbname']);
				$is_install = 1;
				if (!$connect) {
					echo"<div class=\"text\">Сосединение с базой отсутсвует.</div>";
					$is_install++;
				}

				if (!$db) {
					echo "<div class=\"text\">База не найдена.</div>";
					$is_install++;
				}

				if ($is_install == 1) {
					echo'<div class="text">Настройки сохранены.</div>';
					echo'<div class="block"><a href="../install.php?act=3&key=' . $_SESSION['sec_key_install_3'] . '">Продолжить установку</a></div>';

					$config = <<<HTML
<?php

\$config = array (

'home_url' => "{$_REQUEST['home_url']}",

'adm_mail' => "admin@mail.ru",

'gzip' => "1",

'guest_down' => "1",

'registr' => "1",

'skin' => "default",

'allow_files' => "1",

'stats' => "0",

'cache' => "1",

'index_news' => "1",

'cache_check_all' => "4",

'captcha_out' => "1",

'log_hash' => "1",

'flood_time' => "10",

'page' => "10",

'home_name' => "WapView.RU",

'description' => "Flenat Engine - CMS для мобильного wap сайта",

'keywords' => "wap cms, движок wap сайта, Flenat Engine, скачать сайт wap, wap сайты для телефона",

'captcha_back' => "255, 255, 255",

'captcha_text' => "75, 118, 159",

'captcha_line' => "228, 232, 237",

'ignor_files' => "php,phtml,html,xml,xhtml,cgi,perl,php3,php4,php5",

'good_files' => "sis,zip,rar,sisx,jar,jad,exe,mp3,mp4",

'good_files_screen' => "png,jpg,gif",

'bad_link' => "1",

'files_sizes' => "10000000",

'screen_sizes' => "800000",

'screen_xy_max' => "1024",

'screen_xy_min' => "50",

'screen_xy_quality' => "100",

'description_ext' => "200",

'screen_add' => "1",

'watermark' => "1",

'book_on' => "1",

'book_on_user' => "1",

'onl_time' => "1",

'onl_proxy' => "1",

'onl_ip' => "1",

'onl_visit' => "1",

'onl_agent' => "1",

'onl_limit_robots' => "20",

'onl_limit_users' => "20",

'onl_here' => "1",

'dbname' => "{$_REQUEST['dbname']}",

'dbuser' => "{$_REQUEST['dbuser']}",

'dbpass' => "{$_REQUEST['dbpass']}",

'dbhost' => "{$_REQUEST['dbserv']}",

'dbcollate' => "utf8",

'version' => "Flenat Engine 0.5 beta",

'file_copyr' => "readme.txt",

);

?>
HTML;

					$con_file = fopen("config.php", "w+") or die("Извините, но невозможно создать файл <b>config.php</b>");
					fwrite($con_file, "$config");
					fclose($con_file);
					@chmod("config.php", 0666);
				} else {
					echo'<div class="block"><a href="../install.php">Вернуться назад</a></div>';
				}
			}
		} else {
			echo'<div class="text">Произошла ошибка сессии.</div>';
			echo'<div class="block"><a href="../install.php">Вернуться назад</a></div>';
		} 
		if (isset($_SESSION[sec_key_install_1])) unset ($_SESSION[sec_key_install_1]);
		if (isset($_SESSION[sec_key_install_2])) unset ($_SESSION[sec_key_install_2]);
		if (isset($_SESSION[sec_key_install_4])) unset ($_SESSION[sec_key_install_4]);

		break;

	case "3" :
		$_SESSION['sec_key_install_4'] = rv();

		if ($_SESSION['sec_key_install_3'] == $_REQUEST['key'] and $_REQUEST['key'] != '') {
			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `book` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `author` varchar(50) NOT NULL,
  `date` int(100) NOT NULL,
  `text` text NOT NULL,
  `ip` varchar(20) NOT NULL,
  `soft` text NOT NULL,
  `register` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `downloads_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT '0',
  `where` int(11) NOT NULL DEFAULT '0',
  `msg` text NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `tmp_files` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` TEXT NOT NULL ,
`ext` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `news_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT '0',
  `where` int(11) NOT NULL DEFAULT '0',
  `msg` text NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `downloads_category` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `id_parent` int(100) NOT NULL DEFAULT '0',
  `position` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `downloads_files` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `category` int(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date` int(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `approve` int(10) NOT NULL DEFAULT '0',
  `file` varchar(100) NOT NULL,
  `screen` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `count_comms` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `title_2` (`title`,`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `flood` (
  `f_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `id` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`f_id`),
  KEY `ip` (`ip`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subj` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `user` mediumint(8) NOT NULL DEFAULT '0',
  `user_from` varchar(50) NOT NULL DEFAULT '',
  `date` varchar(15) NOT NULL DEFAULT '',
  `message_read` char(3) NOT NULL,
  `folder` varchar(10) NOT NULL DEFAULT '',
  `reply` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `folder` (`folder`),
  KEY `user` (`user`),
  KEY `user_from` (`user_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `news` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `author` text NOT NULL,
  `name` text NOT NULL,
  `text` text NOT NULL,
  `text_full` text NOT NULL,
  `count` int(10) NOT NULL,
  `count_comms` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `online` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `uid` varchar(11) NOT NULL,
  `uname` varchar(40) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `time` varchar(20) NOT NULL,
  `user_agent` varchar(255) NOT NULL DEFAULT 'unknown',
  `OS` varchar(255) NOT NULL DEFAULT 'unknown',
  `proxy` varchar(255) NOT NULL DEFAULT 'unknown',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `pages` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `text` text NOT NULL,
  `count` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "CREATE TABLE IF NOT EXISTS `users` (
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL,
  `grup` int(5) NOT NULL,
  `sex` varchar(1) NOT NULL,
  `avatar` varchar(50) NOT NULL DEFAULT 'no_avatar.png',
  `lastdate` varchar(20) DEFAULT NULL,
  `reg_date` varchar(20) DEFAULT NULL,
  `logged_ip` varchar(100) NOT NULL,
  `allowed_ip` varchar(255) NOT NULL DEFAULT '',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `vopros` text NOT NULL,
  `otvet` text NOT NULL,
  `message_all` int(11) NOT NULL,
  `message_unread` int(11) NOT NULL,
  `life_time` text NOT NULL,
  `user_skill` float(9,3) unsigned NOT NULL DEFAULT '0.000',
  `icq` int(11) NOT NULL,
  `skype` text NOT NULL,
  `jabber` text NOT NULL,
  `mail` text NOT NULL,
  `info_name` text NOT NULL,
  `info_text` text NOT NULL,
  `count_down` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

			$tableSchema[] = "ALTER TABLE `downloads_files` ADD FULLTEXT ( 
`title`
)";

			foreach($tableSchema as $table) {
				$db->query($table);
			}
			echo'<div class="text">Таблицы успешно установлены в Базу!<br>После регистрации Вы автоматически станите администратором.</div>';
			echo'<div class="block"><a href="../index.php">Перейти на сайт</a></div>';
			$install_del = true;
		} else {
			echo'<div class="text">Произошла ошибка сессии.</div>';
			echo'<div class="block"><a href="../install.php">Вернуться назад</a></div>';
		}
		if (isset($_SESSION[sec_key_install_1])) unset ($_SESSION[sec_key_install_1]);
		if (isset($_SESSION[sec_key_install_2])) unset ($_SESSION[sec_key_install_2]);
		if (isset($_SESSION[sec_key_install_3])) unset ($_SESSION[sec_key_install_3]);
		break;

	default :

		$_SESSION['sec_key_install_1'] = rv();

		if (isset($_SESSION[sec_key_install_2])) unset ($_SESSION[sec_key_install_2]);
		if (isset($_SESSION[sec_key_install_3])) unset ($_SESSION[sec_key_install_3]);
        if (isset($_SESSION[sec_key_install_4])) unset ($_SESSION[sec_key_install_4]);

		echo'<div class="block">Проверка модулей:</div><div class="text">';

		$is_install = 1;

		if (function_exists('mysql_info')) {
			echo "Поддержка MySQL: <font color='green'>Да</font><br>";
		} else {
            echo "Поддержка MySQL: <font color='red'>Нет</font><br>";
            $is_install++;
        } 

        if (function_exists('iconv')) {
            echo "Поддержка Iconv: <font color='green'>Да</font><br>";
        } else {
            echo "Поддержка Iconv: <font color='red'>Нет</font><br>";
            $is_install++;
        } 

        if (ini_get('register_globals') == false) {
            echo "register_globals off: <font color='green'>Да</font><br>";
        } else {
            echo "register_globals on: <font color='red'>Нет</font><br>";
            $is_install++;
        } 

        list ($php_ver1, $php_ver2, $php_ver3) = explode('.', strtok(strtok(phpversion(), '-'), ' '), 3);
        if ($php_ver1 == 5) {
            echo "Версия PHP: $php_ver1.$php_ver2.$php_ver3 <font color='green'>Да</font><br>";
        } else {
            echo "Версия PHP: $php_ver1.$php_ver2.$php_ver3 <font color='red'>Нет</font><br>";
            $is_install++;
        } 

        if (function_exists('imagecreatefromstring') && function_exists('gd_info')) {
            $gdinfo = gd_info();
            echo "GD: " . $gdinfo['GD Version'] . " <font color='green'>Да</font><br>";
        } else {
            echo "GD: <font color='red'>Нет</font><br>";
            $is_install++;
        } 
		echo'</div>';
        if (function_exists('disk_free_space') && function_exists('disk_total_space')) {
            echo'<div class="block">Проверка  диска:</div><div class="text">';
            $free_space = disk_free_space(ROOT_DIR);
            $total_space = disk_total_space(ROOT_DIR);

            if ($free_space > 1024 * 1024 * 5) {
                echo "Свободно: <font color='green'>" . formatsize($free_space) . '</font><br>';
            } else {
                echo "Свободно: <font color='red'>" . formatsize($free_space) . '</font><br>';
                $is_install++;
            } 
            echo "Всего:  " . formatsize($total_space) . "</div>";
        } 

        echo'<div class="block">Проверка  доступа:</div><div class="text">';

        if (@chmod("cache", 0777)) {
            echo "/cache (777) <font color='green'>Да</font><br>";
        } else {
            echo "/cache (777) <font color='red'>Нет</font><br>";
            $is_install++;
        } 
        if (@chmod("uploads/tmp", 0777)) {
            echo "/uploads/tmp (777) <font color='green'>Да</font><br>";
        } else {
            echo "/uploads/tmp (777) <font color='red'>Нет</font><br>";
            $is_install++;
        } 
        if (@chmod("uploads/downloads/files", 0777)) {
            echo "/uploads/downloads/files (777) <font color='green'>Да</font><br>";
        } else {
            echo "/uploads/downloads/files (777) <font color='red'>Нет</font><br>";
            $is_install++;
        } 
        if (@chmod("uploads/downloads/screen", 0777)) {
            echo "/uploads/downloads/screen (777) <font color='green'>Да</font><br>";
        } else {
            echo "/uploads/downloads/screen (777) <font color='red'>Нет</font><br>";
            $is_install++;
        } 
        if (@chmod("uploads/downloads/screen_small", 0777)) {
            echo "/uploads/downloads/screen_small (777) <font color='green'>Да</font><br>";
        } else {
            echo "/uploads/downloads/screen_small (777) <font color='red'>Нет</font><br>";
            $is_install++;
        } 

        echo'</div>';

        if ($is_install == 1) {
            echo'<div class="block"><a href="../install.php?act=1&key=' . $_SESSION['sec_key_install_1'] . '">Продолжить установку</a></div>';
        } else {
            echo'<div class="block"><font color="red">Устновка невозможна.</font><br><img src="../img.php?pic=ball" alt=""> <a href="../install.php">Обновить</a></div>';
        } 
} 

echo <<<HTML
</div>
<div class="foot">
<center>
&copy; Flenat Engine 0.5 beta
</center>
</div>
</body>
</html>
HTML;

?>