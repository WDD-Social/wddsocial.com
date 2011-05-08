<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Resizer {
	public static function image($upload, $name, $extra, $dest, $new_width = 800, $new_height = 600, $crop = false){
		if(move_uploaded_file($upload['tmp_name'],"$dest/$name")){
			$image = "$dest/$name";
		}else if(file_exists("$dest/$name")){
			$image = "$dest/$name";
		}
		if($image != NULL){
			$size = getimagesize($image);
			$width = $size[0];
			$height = $size[1];
			switch ($upload['type']) {
				case 'image/jpeg':
					$original_image = imagecreatefromjpeg($image);
					break;
				case 'image/png':
					$original_image = imagecreatefrompng($image);
					break;
				case 'image/gif':
					$original_image = imagecreatefromgif($image);
					break;
			}
			$resize_width = $width;
			$resize_height = $height;
			if($crop){
				$thumb_width = $new_width;
				$thumb_height = $new_height;
				$final_image = imagecreatetruecolor($new_width,$new_height);
				$bg = imagecolorallocate($final_image, 255, 255, 255);
				imagefill($final_image, 0, 0, $bg);
				if($resize_height > $resize_width){
					$thumb_height = ($resize_height/$resize_width)*$thumb_width;
					$thumb = imagecreatetruecolor($thumb_width,$thumb_height);
					$bg = imagecolorallocate($thumb, 255, 255, 255);
					imagefill($thumb, 0, 0, $bg);
				}else if($resize_width > $resize_height){
					$thumb_width = ($resize_width/$resize_height)*$thumb_height;
					$thumb = imagecreatetruecolor($thumb_height,$thumb_width);
					$bg = imagecolorallocate($thumb, 255, 255, 255);
					imagefill($thumb, 0, 0, $bg);
				}
				if(!isset($thumb)){
					$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
					$bg = imagecolorallocate($thumb, 255, 255, 255);
					imagefill($thumb, 0, 0, $bg);
				}
				imagecopyresampled($thumb,$original_image,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
				imagecopy($final_image,$thumb,0,0,0,0,$new_width,$new_height);
			}else{
				if($resize_width > $new_width){
					$resize_width = $new_width;
					$resize_height = ($height/$width)*$resize_width;
					$final_image = imagecreatetruecolor($resize_width, $resize_height);
					$bg = imagecolorallocate($final_image, 255, 255, 255);
					imagefill($final_image, 0, 0, $bg);
				}
				if($resize_height > $new_height){
					$resize_height = $new_height;
					$resize_width = ($width/$height)*$resize_height;
					$final_image = imagecreatetruecolor($resize_width, $resize_height);
					$bg = imagecolorallocate($final_image, 255, 255, 255);
					imagefill($final_image, 0, 0, $bg);
				}
				if(!isset($final_image)){
					$final_image = imagecreatetruecolor($resize_width, $resize_height);
					$bg = imagecolorallocate($final_image, 255, 255, 255);
					imagefill($final_image, 0, 0, $bg);
				}
				imagecopyresampled($final_image,$original_image,0,0,0,0,$resize_width,$resize_height,$width,$height);
			}
			imagejpeg($final_image,"$image"."$extra.jpg");
			imagedestroy($final_image);
			imagedestroy($original_image);
		}else{
			// RESIZE PHOTOS
		}
	}
}