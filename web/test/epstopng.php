<?php
$path = '6_mak_pattern_02.eps'; //eps file
$path2 = 'Motif_5 (1).ai'; // ai fo;e
$save_path_for_eps = 'eps.svg';
$save_path_for_ai = 'ai.svg';
//for eps to png
$image = new Imagick();
$image->readimage($path);
$image->setImageFormat("svg");
$image->writeImage($save_path_for_eps);
//for ai to png
$image1 = new Imagick();
$image1->readimage($path2);
$image1->setImageFormat("svg");
$image1->writeImage($save_path_for_ai);
echo '<img src="' . $save_path_for_eps . '"/>' . '<br>';
echo '<img src="' . $save_path_for_ai . '"/>' . '<br>';
?>
