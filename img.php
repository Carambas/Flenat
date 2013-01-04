<?php 
// ////////// Закодированное изображение точки
switch ($_GET['pic']) {
    case "ball" :
        $pict = 'R0lGODlhBwAHAJkAAAAAAKCgoMDAwAAAACH5BAEAAAAALAEAAQAFAAUAAAIIhCIWe8ueTioAOw==';
        header ("Content-type: image/gif");
        echo base64_decode($pict);
        break; 
		
    case "logo" :
        $pict = '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';
        header ("Content-type: image/gif");
        echo base64_decode($pict);
        break; 
    // ////////// Выводит каптчу
    default :
        @session_start();
        @error_reporting(E_ALL ^ E_NOTICE);
        @ini_set('display_errors', true);
        @ini_set('html_errors', false);
        @ini_set('error_reporting', E_ALL ^ E_NOTICE);
        @include ('config.php');
        $code_img = rand(1111, 9999);
        $_SESSION['sec_code'] = $code_img;
        Header('Content-type: image/gif');
        $im = @imagecreate (43, 17)
        or die ("Cannot Initialize new GD image stream");
        $img_color_back = explode(', ', $config['captcha_back']);//ФОН
        $img_color_text = explode(', ', $config['captcha_text']);//ТЕКСТ
        $img_color_line = explode(', ', $config['captcha_line']);//РАМКА
        $background_color = imagecolorallocate ($im, $img_color_back[0], $img_color_back[1], $img_color_back[2]);
        $text_color = imagecolorallocate ($im, $img_color_text[0], $img_color_text[1], $img_color_text[2]);
        $red = imagecolorallocate($im, $img_color_line[0], $img_color_line[1], $img_color_line[2]);
        imageline($im, 0, 0, 43, 0, $red);
        imageline($im, 0, 0, 0, 17, $red);
        imageline($im, 0, 16, 43, 16, $red);
        imageline($im, 42, 0, 42, 17, $red);
        settype($code, 'string');
        imageString($im, 3, 8, 2, $code_img, $text_color);
        imagegif ($im);
} 

?>