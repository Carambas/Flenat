<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

class comments
{
	var $table = ''; # таблица комментариев
    var $where = '';

	var $link = ''; # линк

    var $count = 0; # всего комментариев

    var $adm = false; # доступ

    var $id = 0; # id объекта
    var $ref = ''; # название таблицы обьекта
    var $komm = 'komm'; # название параметра комментариев

    var $no_msg = '<div class="menu">Комментариев нет</div>'; # если нет комментариев

    var $max = 500; # макс. длина сообщения


	# конструктор
	public function __construct($table, $where, $link)
	{
		$this->table = $table;
        $this->where = $where;
        $this->link = $link;
	}

    #
    public function add_ref($ref, $komm = 'count_comms', $id = 0)
    {
    	if ($id == 0) $id = $this->where;
    	$this->id = $id;
    	$this->ref = $ref;
    	$this->komm = $komm;
    }

    public function add($user, $msg, $time, $update = false, $loc = false)
    {
    	global $db, $_IP, $config;
		$id = $this->where;
    	$msg = check_full($msg);
    	$str = strlen($msg);
		
		if ($str < 3)
		{
			$_SESSION['error'] = 'Слишком короткое сообщение';
			move($this->link);
		}
		
		if ($loc && $str > $this->max)
		{
			$_SESSION['error'] = 'Слишком длинное сообщение ('.$str.') max. '.$this->max;
			move($this->link);
		}
		
		if(flooder($_IP) == true)
		{
			$_SESSION['error'] = 'Слишком часто добавляете сообщения';
			move($this->link);
		}
	
		$q = $db->query("SELECT * FROM `".$this->table."` WHERE `user` = '{$user}' AND `msg` = '{$msg}' AND `date` > '".($time-300)."' LIMIT 1");
		if ($db->num_rows($q) > 0)
		{
			$_SESSION['error'] = 'Сообщение повторяет предыдущее!';
			move($this->link);
		}
		
		$msg = substr($msg, 0, $this->max);

		$db->query("INSERT INTO `".$this->table."` SET `user` = '{$user}', `date` = '{$time}', `msg` = '{$msg}', `where` = '{$id}'");
		$db->query("INSERT INTO flood (id, ip) values ('$time', '$_IP')");

    	if ($update) $db->query(" UPDATE `".$this->ref."` SET `".$this->komm."`=`".$this->komm."`+1 WHERE `id` = '".$this->id."' LIMIT 1; ");
	
		$db->free();
		
		if($config['cache'] == '1')
		{
			clear_all();
		}

    	$_SESSION['echo'] = 'Сообщение успешно добавлено';
		move($this->link);
    }

    # вывод комментариев
    public function listing($link, $moder = false)
    {
    	global $db, $tpl, $config, $DIR_STYLE;
		
		$all = $db->super_query("SELECT COUNT(*) as count FROM `".$this->table."` WHERE `where` = ".$this->where."");
		$its_all = $all['count'];

		$page = new page($its_all, $config['page']);
	
		$comm_sql = $db->query("SELECT * FROM `".$this->table."` WHERE `where` = ".$this->where." ORDER BY id DESC LIMIT ".$page->go.", ".$config['page']."");
		
		$tpl_comm = new tpls ();
		$tpl_comm->dir = TEMPLATE_DIR;

		while ($row = $db->get_row($comm_sql))
		{
			$ank = $db->super_query('SELECT * FROM `users` WHERE `user_id` = '.$row['user'].' LIMIT 1');
			
			$tpl_comm->load_tpl('comments.tpl');
			$tpl_comm->set_block("'\\[comments_add\\](.*?)\\[/comments_add\\]'si", "");
			$tpl_comm->set ('[comments]', '');
			$tpl_comm->set ('[/comments]', '');
		
			$tpl_comm->set('{user_name}', $ank['name']);
			$tpl_comm->set('{online}', online($ank['name']));
			$tpl_comm->set('{avatar}', $PHP_SELF.'/uploads/avatars/'.$ank['avatar']);	
			$tpl_comm->set('{post_date}', iTime($row['date']));
			$tpl_comm->set('{post_text}', iPost($row['msg']));
			$tpl_comm->compile('content');
			$tpl_comm->clear();
		}
		$tpl->result['content'] .= $tpl_comm->result['content'];
		$tpl->result['content'] .= $page->listing($this->link);

    }
	public function add_form($id)
	{
		global $config, $tpl, $PHP_SELF, $login, $DIR_STYLE;
		$tpl_comm = new tpls ();
		$tpl_comm->dir = TEMPLATE_DIR;
		if($login)
		{
			$tpl_comm->load_tpl('comments.tpl');
			$tpl_comm->set_block("'\\[comments\\](.*?)\\[/comments\\]'si", "");
			$tpl_comm->set ('[comments_add]', '<form method="post" action="' . $this->link . '">');
			$tpl_comm->set ('[/comments_add]', '</form>');
		} else {
			$tpl_comm->copy_tpl = '';
		}
		$tpl_comm->compile('content');
		$tpl_comm->clear();
		$tpl->result['content'] .= $tpl_comm->result['content'];
	}

    # собственно удаление
    public function del($id, $update = false)
    {
    global $db, $config;

    	$db->query("DELETE FROM `".$this->table."` WHERE `id` = '{$id}' and `where` = '".$this->where."' LIMIT 1");
    	$db->query("OPTIMIZE TABLE `".$this->table."`");
        if ($update) $db->query("UPDATE `".$this->ref."` SET `".$this->komm."`=`".$this->komm."`-1 WHERE `id` = '".$this->id."' LIMIT 1");

		if($config['cache'] == '1')
		{
			clear_all();
		}
		
		$_SESSION['echo'] = 'Сообщение успешно удалено';
		move($this->link);
    }

}
?>