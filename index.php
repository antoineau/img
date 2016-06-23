<?php

if (isset($_GET)) {
    $category   = null;
    if (isset($_GET['category'])) {
        $category = filter_var($_GET['category'], FILTER_SANITIZE_STRING);
    }
    if (empty($_GET['width']) || empty($_GET['height'])) {
        die('...');
    }

    createImg($_GET['height'], $_GET['width'], $_GET['color'], $_GET['textcolor'], $category);

    exit;
}

function createImg($height, $width, $bgColor = '000000', $textColor = 'FFFFFF', $category = null)
{
    /**
     * The text
     */
    $text = $width . ' x ' . $height;
    $fontSize = ($width > $height) ? ($height / 10) : ($width / 10); //Calculating font size
    $widthRatio = $width > 1000 ? 7.8 : 6.3; //Ratio of the background text
    $textImg = imagecreate($widthRatio * $fontSize, $fontSize + 2);
    $textImgPath = 'data/text.jpg';

    //Color of the text background
    $rgbBgColor = ImageColorAllocate(
        $textImg,
        base_convert(substr($bgColor, 0, 2), 16, 10),
        base_convert(substr($bgColor, 2, 2), 16, 10),
        base_convert(substr($bgColor, 4, 2), 16, 10)
    );

    //Color of the text
    $rgbTextColor = ImageColorAllocate(
        $textImg,
        base_convert(substr($textColor, 0, 2), 16, 10),
        base_convert(substr($textColor, 2, 2), 16, 10),
        base_convert(substr($textColor, 4, 2), 16, 10)
    );

    ImageFill($textImg, 0, 0, $rgbBgColor);

    //Inserting Text
    imagettftext(
        $textImg,
        $fontSize,
        0,
        $fontSize/10*2,
        $fontSize-1,
        $rgbTextColor,
        'font/arial.ttf',
        $text
    );

    //Saving the text image
    imagejpeg($textImg, $textImgPath);


    /**
     * The background
     */
    $display    = 'imagejpeg';
    $ext        = '.jpg';
    $function   = 'imagecreatefromjpeg';
    if (!is_null($category)) {

        //The sample directory
        $dir = 'sample/' . $category;

        if (is_dir($dir)) {
            //Read the folder
            $dh = opendir($dir);
            $files = array();
            while (false !== ($filename = readdir($dh))) {
                if ('.' != $filename && '..' != $filename) {
                    $files[] = $filename;
                }
            }

            //Pick a random file from the folder
            $sourceImg = $files[mt_rand(0, count($files) - 1)];
        }

        //Init the source image
        $imagePath = $dir . '/' . $sourceImg;

        if ('' !== $sourceImg) {
            $function = 'imagejpeg';
            $exif = exif_imagetype($imagePath);
            if ($exif === IMAGETYPE_GIF) {
                $function = 'imagecreatefromgif';
                $display  = 'imagegif';
                $ext      = '.gif';
            } elseif ($exif === IMAGETYPE_JPEG) {
                $function = 'imagecreatefromjpeg';
                $display  = 'imagejpeg';
                $ext      = '.jpg';
            } elseif ($exif === IMAGETYPE_PNG) {
                $function = 'imagecreatefrompng';
                $display  = 'imagepng';
                $ext      = '.png';
            }
            $backgroundSource = $function($imagePath);
        }

    } else {
        $backgroundSource = imagecreate($width, $height);
        imagecolorallocate($backgroundSource, 128, 128, 128);
    }

    $backgroundImg = imagecreatetruecolor($width, $height); //The resized panel
    $backgroundImgPath = 'data/resampled.jpg';

    //Retrieving widths and heights
    $backgroundSourceWidth  = imagesx($backgroundSource);
    $backgroundSourceHeight = imagesy($backgroundSource);
    $backgroundImgWidth     = imagesx($backgroundImg);
    $backgroundImgHeight    = imagesy($backgroundImg);

    //Resampled the background image
    imagecopyresampled($backgroundImg, $backgroundSource, 0, 0, 0, 0, $backgroundImgWidth, $backgroundImgHeight, $backgroundSourceWidth, $backgroundSourceHeight);

    //Saving the resampled image
    imagejpeg($backgroundImg, $backgroundImgPath);


    /**
     * Displaying
     */
    //Set the correct header
    $fileInfoOption = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfoOption, $backgroundImgPath);
    header("Content-type: " . $mimeType);

    //Loading the images
    $source = imagecreatefromjpeg($textImgPath); //The text
    $final = $function($backgroundImgPath); //The background

    //Width et Height of the text
    $sourceWitdh = imagesx($source);
    $sourceHeight = imagesy($source);

    //The text will be placed in the top left corner
    $finalX = 10;
    $finalY = 10;

    //Merging the two images
    imagecopymerge($final, $source, $finalX, $finalY, 0, 0, $sourceWitdh, $sourceHeight, 100);

    //Display the result
    $display($final, 'data/' . $category .'-'. $width . '-' . $height . '-' . $bgColor . '-' . $textColor . $ext);
    $display($final);

    //Free up resources
    ImageDestroy($final);
}