<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
class tpls {
    var $dir = '.';
    var $template = null;
    var $copy_tpl = null;
    var $data = array ();
    var $block_data = array ();
    var $result = array ('info' => '', 'stats' => '', 'content' => '');
    var $allow_php_include = 0;

    var $template_parse_time = 0;

    function set($name, $var)
    {
        if (is_array($var) && count($var)) {
            foreach ($var as $key => $key_var) {
                $this->set($key, $key_var);
            } 
        } else
            $this->data[$name] = $var;
    } 

    function set_block($name, $var)
    {
        if (is_array($var) && count($var)) {
            foreach ($var as $key => $key_var) {
                $this->set_block($key, $key_var);
            } 
        } else
            $this->block_data[$name] = $var;
    } 

    function load_tpl($tpl_name)
    {
        $time_before = $this->get_real_time();

        if ($tpl_name == '' || ! file_exists($this->dir . DIRECTORY_SEPARATOR . $tpl_name)) {
            die("Невозможно загрузить шаблон: " . $tpl_name);
            return false;
        } 

        $this->template = file_get_contents($this->dir . DIRECTORY_SEPARATOR . $tpl_name);

        if (strpos ($this->template, "[on=") !== false) {
            $this->template = preg_replace ("#\\[on=(.+?)\\](.*?)\\[/on\\]#ies", "\$this->check_module('\\1', '\\2')", $this->template);
        } 

        if (strpos ($this->template, "[off=") !== false) {
            $this->template = preg_replace ("#\\[off=(.+?)\\](.*?)\\[/off\\]#ies", "\$this->check_module('\\1', '\\2', false)", $this->template);
        } 

        $this->copy_tpl = $this->template;

        $this->template_parse_time += $this->get_real_time() - $time_before;
        return true;
    } 

    function check_module($aviable, $block, $action = true)
    {
        global $it_do;

        $aviable = explode('|', $aviable);

        $block = str_replace('\"', '"', $block);

        if ($action) {
            if (! (in_array($it_do, $aviable)) and ($aviable[0] != "global")) return "";
            else return $block;
        } else {
            if ((in_array($it_do, $aviable))) return "";
            else return $block;
        } 
    } 

    function _clear()
    {
        $this->data = array ();
        $this->block_data = array ();
        $this->copy_tpl = $this->template;
    } 

    function clear()
    {
        $this->data = array ();
        $this->block_data = array ();
        $this->copy_tpl = null;
        $this->template = null;
    } 

    function all_clear()
    {
        $this->data = array ();
        $this->block_data = array ();
        $this->result = array ();
        $this->copy_tpl = null;
        $this->template = null;
    } 

    function compile($tpl)
    {
        $time_before = $this->get_real_time();

        if (count($this->block_data)) {
            foreach ($this->block_data as $key_find => $key_replace) {
                $find_preg[] = $key_find;
                $replace_preg[] = $key_replace;
            } 

            $this->copy_tpl = preg_replace($find_preg, $replace_preg, $this->copy_tpl);
        } 

        foreach ($this->data as $key_find => $key_replace) {
            $find[] = $key_find;
            $replace[] = $key_replace;
        } 

        $this->copy_tpl = str_replace($find, $replace, $this->copy_tpl);

        if (isset($this->result[$tpl])) $this->result[$tpl] .= $this->copy_tpl;
        else $this->result[$tpl] = $this->copy_tpl;

        $this->_clear();

        $this->template_parse_time += $this->get_real_time() - $time_before;
    } 

    function get_real_time()
    {
        list ($seconds, $microSeconds) = explode(' ', microtime());
        return ((float) $seconds + (float) $microSeconds);
    } 
} 

?>