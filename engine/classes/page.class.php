<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");

class page
{
	var $page = 1; # сейчас
    var $k_pages = 1; # всего

	var $go = 0;

	# начало для вывода постриничной навигации
	public function __construct($k = 0, $t = 5)
	{
		$this->start($k, $t);

		if (!defined('kPAGE')) define('kPAGE', $this->k_pages);
		if (!defined('PAGE')) define('PAGE', $this->page);
		if (!defined('Go')) define('Go', $this->go);
	}

    # параметр для LIMIT
	public function start($k, $t = 5)
	{
		if ($k < 1) $k = 1;
		$this->k_pages = (int) ceil($k / $t);
		if (IsSet($_GET['page']))
		{
			$this->page = ($_GET['page'] == 'end') ? $this->k_pages : abs(intval($_GET['page']));
		}
		if ($this->page < 1 || $this->page > $this->k_pages) $this->page = 1;

		$this->go = $this->page * $t - $t;

	return $this->go;
	}

	# Ф-я вывода постраничной навигации
	public function listing($a)
	{
		global $tpl;
		if ($this->k_pages > 1)
		{
			$tpl_navigation = new tpls ();
			$tpl_navigation->dir = TEMPLATE_DIR;
			$tpl_navigation->load_tpl('navigation.tpl');
			
			if ($this->page <> 1)
			{
				$pages .= '<a href="'.$a.'&page=1">1</a>';
			} else {
				$pages .= '<span>1</span>';
			}

			for ($i = -2; $i <= 2; $i++)
			{
			$z = $this->page + $i;
				if ($z > 1 && $z < $this->k_pages)
				{
					if ($i == -2 && $z > 2) $pages .= '&nbsp;&nbsp;...&nbsp;&nbsp;';

					if ($i <> 0) $pages .= '<a href="'.$a.'&page='.$z.'">'.$z.'</a>';
					else $pages .= '<span>'.$z.'</span>';

					if ($i == 2 && $z < $this->k_pages - 1) $pages .= '&nbsp;&nbsp;...&nbsp;&nbsp;';
				}
			}

			if ($this->page <> $this->k_pages) $pages .= '<a href="'.$a.'&page='.$this->k_pages.'">'.$this->k_pages.'</a>';
			elseif ($this->k_pages > 1) $pages .= '<span>'.$this->k_pages.'</span>';
			$tpl_navigation->set('{pages}', $pages);
   			$tpl_navigation->compile('content');
			$tpl_navigation->clear();
			$tpl->result['content'] .= $tpl_navigation->result['content'];
		}
	}

	public function __toString()
	{
        return $this->go;
	}

}

?>