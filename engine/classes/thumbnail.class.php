<?php if (!defined('_ENGINE_')) die("Ошибка получения доступа.");
// ///////////////////////////////////////////////////////////////
// //
// //
// ///////////////////////////////////////////////////////////////
class thumbnail {
    var $img;
    var $watermark_image_light;
    var $watermark_image_dark;

    function thumbnail($imgfile)
    {
        $info = @getimagesize($imgfile);

        if ($info[2] == 2) {
            $this->img['format'] = "JPEG";
            $this->img['src'] = @imagecreatefromjpeg($imgfile);
        } elseif ($info[2] == 3) {
            $this->img['format'] = "PNG";
            $this->img['src'] = @imagecreatefrompng($imgfile);
        } elseif ($info[2] == 1) {
            $this->img['format'] = "GIF";
            $this->img['src'] = @imagecreatefromgif($imgfile);
        } else {
            echo('Изображение не корректно! Изображение не соответствует своему формату. Такое бывает если картинка была переименована в другой формат.<br>'); 
            // @unlink($imgfile);
             exit();
        } 

        if (!$this->img['src']) {
            echo('Изображение не корректно! Изображение не соответствует своему формату. Такое бывает если картинка была переименована в другой формат.<br>'); 
            // @unlink($imgfile);
             exit();
        } 

        $this->img['lebar'] = @imagesx($this->img['src']);
        $this->img['tinggi'] = @imagesy($this->img['src']);
        $this->img['lebar_thumb'] = $this->img['lebar'];
        $this->img['tinggi_thumb'] = $this->img['tinggi'];
        $this->img['quality'] = 90;
    } 

    function size_auto($size = 100, $site = 0)
    {
        global $gd_version, $img_profile;

        $site = intval($site);

        if ($this->img['lebar'] <= $size and $this->img['tinggi'] <= $size) {
            $this->img['lebar_thumb'] = $this->img['lebar'];
            $this->img['tinggi_thumb'] = $this->img['tinggi'];
            return 0;
        } 

        switch ($site) {
            case "1" :
                if ($this->img['lebar'] <= $size) {
                    $this->img['lebar_thumb'] = $this->img['lebar'];
                    $this->img['tinggi_thumb'] = $this->img['tinggi'];
                    return 0;
                } else {
                    $this->img['lebar_thumb'] = $size;
                    if ($img_profile != true) {
                        $this->img['tinggi_thumb'] = ($this->img['lebar_thumb'] / $this->img['lebar']) * $this->img['tinggi'];
                    } else {
                        $this->img['tinggi_thumb'] = $size;
                    } 
                } 

                break;

            case "2" :
                if ($this->img['tinggi'] <= $size) {
                    $this->img['lebar_thumb'] = $this->img['lebar'];
                    $this->img['tinggi_thumb'] = $this->img['tinggi'];
                    return 0;
                } else {
                    $this->img['tinggi_thumb'] = $size;
                    if ($img_profile != true) {
                        $this->img['lebar_thumb'] = ($this->img['tinggi_thumb'] / $this->img['tinggi']) * $this->img['lebar'];
                    } else {
                        $this->img['lebar_thumb'] = $size;
                    } 
                } 

                break;

            default :

                if ($this->img['lebar'] >= $this->img['tinggi']) {
                    $this->img['lebar_thumb'] = $size;
                    if ($img_profile != true) {
                        $this->img['tinggi_thumb'] = ($this->img['lebar_thumb'] / $this->img['lebar']) * $this->img['tinggi'];
                    } else {
                        $this->img['tinggi_thumb'] = $size;
                    } 
                } else {
                    $this->img['tinggi_thumb'] = $size;
                    if ($img_profile != true) {
                        $this->img['lebar_thumb'] = ($this->img['tinggi_thumb'] / $this->img['tinggi']) * $this->img['lebar'];
                    } else {
                        $this->img['lebar_thumb'] = $size;
                    } 
                } 

                break;
        } 

        $this->img['des'] = imagecreatetruecolor($this->img['lebar_thumb'], $this->img['tinggi_thumb']);
        @imagecopyresampled($this->img['des'], $this->img['src'], 0, 0, 0, 0, $this->img['lebar_thumb'], $this->img['tinggi_thumb'], $this->img['lebar'], $this->img['tinggi']);

        $this->img['src'] = $this->img['des'];
        return 1;
    } 

    function jpeg_quality($quality = 90)
    {
        $this->img['quality'] = $quality;
    } 

    function save($save = "")
    {
        if ($this->img['format'] == "JPG" || $this->img['format'] == "JPEG") {
            imagejpeg($this->img['src'], $save, $this->img['quality']);
        } elseif ($this->img['format'] == "PNG") {
            imagepng($this->img['src'], $save);
        } elseif ($this->img['format'] == "GIF") {
            imagegif($this->img['src'], $save);
        } 

        imagedestroy($this->img['src']);
    } 

    function show()
    {
        if ($this->img['format'] == "JPG" || $this->img['format'] == "JPEG") {
            imageJPEG($this->img['src'], "", $this->img['quality']);
        } elseif ($this->img['format'] == "PNG") {
            imagePNG($this->img['src']);
        } elseif ($this->img['format'] == "GIF") {
            imageGIF($this->img['src']);
        } 

        imagedestroy($this->img['src']);
    } 
    function insert_watermark($min_image)
    {
        global $config;
        $margin = 7;

        $this->watermark_image_light = TEMPLATE_DIR . '/watermark_light.png';
        $this->watermark_image_dark = TEMPLATE_DIR . '/watermark_dark.png';

        $image_width = imagesx($this->img['src']);
        $image_height = imagesy($this->img['src']);

        list ($watermark_width, $watermark_height) = getimagesize($this->watermark_image_light);

        $watermark_x = $image_width - $margin - $watermark_width;
        $watermark_y = $image_height - $margin - $watermark_height;

        $watermark_x2 = $watermark_x + $watermark_width;
        $watermark_y2 = $watermark_y + $watermark_height;

        if ($watermark_x < 0 or $watermark_y < 0 or $watermark_x2 > $image_width or $watermark_y2 > $image_height or $image_width < $min_image or $image_height < $min_image) {
            return;
        } 

        $test = imagecreatetruecolor(1, 1);
        imagecopyresampled($test, $this->img['src'], 0, 0, $watermark_x, $watermark_y, 1, 1, $watermark_width, $watermark_height);
        $rgb = imagecolorat($test, 0, 0);

        $r = ($rgb >> 16) &0xFF;
        $g = ($rgb >> 8) &0xFF;
        $b = $rgb &0xFF;

        $max = min($r, $g, $b);
        $min = max($r, $g, $b);
        $lightness = (double) (($max + $min) / 510.0);
        imagedestroy($test);

        $watermark_image = ($lightness < 0.5) ? $this->watermark_image_light : $this->watermark_image_dark;

        $watermark = imagecreatefrompng($watermark_image);

        imagealphablending($this->img['src'], true);
        imagealphablending($watermark, true);

        imagecopy($this->img['src'], $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);

        imagedestroy($watermark);
    } 
} 

?>