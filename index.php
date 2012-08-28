<?php

//TODO : Gestion du texte
if (isset($_GET)) {
    $imagedata = explode('-',$_GET['data']);
    $text = (string) $_GET['text'];

    if (empty($_GET['width']) || empty($_GET['height'])) {
        die('...');
    }

    createImg(
        $_GET['height'],
        $_GET['width'],
        $_GET['color'],
        $_GET['txtcolor'],
        $text
    );

    exit;
}


function createImg($width, $height, $bgColor, $txtColor, $text = null)
{
    if (null == $bgColor) {
        $bgColor = 'c0c0c0';
    }

    if (null == $txtColor) {
        $txtColor = '838383';
    }

    if (null == $text) {
        $text = "$width X $height";
    }

    //Create the image resource
    $image = ImageCreate($width, $height);

    //Making of colors, we are changing HEX to RGB
    $rgbBgColor = ImageColorAllocate(
        $image,
        base_convert(substr($bgColor, 0, 2), 16, 10),
        base_convert(substr($bgColor, 2, 2), 16, 10),
        base_convert(substr($bgColor, 4, 2), 16, 10)
    );

    $rgbTxtColor = ImageColorAllocate(
        $image,
        base_convert(substr($txtColor, 0, 2), 16, 10),
        base_convert(substr($txtColor, 2, 2), 16, 10),
        base_convert(substr($txtColor, 4, 2), 16, 10)
    );

    //Fill the background color
    ImageFill($image, 0, 0, $rgbBgColor);

    //Calculating font size
    $fontsize = ($width > $height) ? ($height / 10) : ($width / 10) ;

    //Inserting Text
    imagettftext(
        $image,
        $fontsize,
        0,
        ($width/2) - ($fontsize * 2.75),
        ($height/2) + ($fontsize* 0.2),
        $rgbTxtColor,
        '/usr/share/fonts/truetype/msttcorefonts/arial.ttf',
        $text
    );

    //Tell the browser what kind of file is come in
    header("Content-Type: image/png");

    //Save & Output the newly created image in png format
    $name = './data/' . $width . '-' . $height;

    if ($bgColor != 'c0c0c0') {
        $name .= '-' . $bgColor;
    }
    if ($txtColor != '838383') {
        $name .= '-' . $txtColor;
    }

    imagepng($image, $name . '.png');
    imagepng($image);

    //Free up resources
    ImageDestroy($image);
}

?>