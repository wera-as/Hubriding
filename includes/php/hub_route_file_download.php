<?php

require_once __DIR__ . '/resources/bytes_to_human.php';
require_once __DIR__ . '/resources/get_filesize.php';

function hub_route_file_download()
{

    $content = NULL;

    $file = get_field('rute_fil');

    $file_icon = get_field('option_gpx_ikon', 'option');

    $file_name = "Rute " . get_field('rutenummer') . " | " . get_field('rutenavn');

    $ext        =   pathinfo($file, PATHINFO_EXTENSION);
    $filename   =   pathinfo($file, PATHINFO_FILENAME);

    $size   =   Get_Filesize($file);

    $filesize = FileSizeConvert($size);
	
	$content .= "<section class='route-file'>";
	$content .= "	<div class='route-file-container'>";
	$content .= "		<img src='$file_icon'>";
	$content .= "		<div class='route-file-meta'>";
	$content .= "			<p>$file_name</p>";
	$content .= "			<div>";
	$content .= "				<span><i class='fa-solid fa-database'></i>&ensp;" . $filename . "." . $ext . "</span>";
    $content .= "				<span><i class='fa-solid fa-file'></i>&ensp;$filesize</span>";
	$content .= "			</div>";
	$content .= "		</div>";
	$content .= "	</div>";
	$content .= "	<a href='$file' target='_blank'>Last ned</a>";
	$content .= "</section>";

    return $content;
}
add_shortcode('hub_route_file_download', 'hub_route_file_download');
