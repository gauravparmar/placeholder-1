<?php
/* -----------------------------------------------------
PHP image placeholder script
https://github.com/marceloguzman/placeholder
By Marcelo Guzman (https://github.com/marceloguzman, www.marceloguzman.com )

 ----------------------------------------------------- */

function curPageURL() {
 $pageURL = 'http';
// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
 
 function rojo($color) { //gets the red value from a html hex color
	return base_convert(substr($color, 0, 2), 16, 10);
 }
 
 function verde($color) { //gets the green value from a html hex color
	return base_convert(substr($color, 2, 2), 16, 10);
 }
 
 function azul($color) { //gets the blue value from a html hex color
	return base_convert(substr($color, 4, 2), 16, 10);
 }
 
function create_image($width, $height, $bg_color, $txt_color, $line_color ) {

$image = ImageCreate($width, $height);     
	
	
// Getting the color grom hex values -----------------------------------------------------------------------------------
	
	$bg_color =	ImageColorAllocate($image, rojo($bg_color), verde($bg_color), azul($bg_color));
	$txt_color = ImageColorAllocate($image,rojo($txt_color), verde($txt_color), azul($txt_color));
	$line_color = ImageColorAllocate($image,rojo($line_color), verde($line_color), azul($line_color));

	
// Settings  -----------------------------------------------------------------------------------
    
	imagesetthickness ( $image, 4 ); 	// Setting border thickness
    ImageFill($image, 0, 0, $bg_color); // Setting the background color 
	$pto = 10; 							// Setting padding for the inner box
	$text = $width . "x" . $height;     	// Setting text (width and height of the image)
	$font_file = 'coolvetica.ttf'; 	
	
	if (($width<=150)||($height<=150)) {  // fix for small sizes
		$pto = 5;
		imagesetthickness ( $image, 2 );
	}
	
	$factor = 0.13;
	if ($width>=180) $factor = 0.20;
	
	if ($width>$height) {
		$fontsize = $height * $factor;
	} else {
		$fontsize = $width * $factor;
	}
	
	
	
	
	// Retrieve bounding box:
	$type_space = imagettfbbox($fontsize, 0, $font_file, $text);

	// Determine image width and height,
	$image_width = abs($type_space[4] - $type_space[0]) ;
	$image_height = abs($type_space[5] - $type_space[1]);
	$txt_width = ($width - $image_width)/2;
	$txt_height = ($height + $image_height)/2;
	
	
// drawing the image -----------------------------------------------------------------------------------
   
	imageline($image, $pto,$pto , $width-$pto, $height-$pto, $line_color);			// diagonal line
	imageline($image, $pto, $height-$pto , $width-$pto, $pto, $line_color); 		// diagonal line
	imageline($image, $pto,$pto , $width-$pto, $pto, $line_color);		 			// top line
	imageline($image, $pto, $height-$pto , $width-$pto, $height-$pto, $line_color);// bottom line
	imageline($image, $pto, $pto , $pto, $height-$pto, $line_color); 				// left line
	imageline($image, $width-$pto, $pto , $width-$pto, $height-$pto, $line_color); // right line
	
	// imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
	imagettftext($image,$fontsize, 0, $txt_width, $txt_height , $txt_color, $font_file , $text);

 
 
// writing the image to the browser ----------------------------------------------------------------------------------
    
    header("Content-Type: image/png"); 
    imagepng($image); 
    ImageDestroy($image); 

} // end of create_image ----------------------------------------------------------------------------------------------------










// Main -----------------------------------------------------------------------------------

if(!empty($_GET['image'])) {

	$parameters = explode('X',strtoupper($_GET['image'])); 
	if (!is_array($parameters) || count($parameters) != 2) //If something goes wrong
        {
            $link=dirname(curPageURL())  . "/600x350";
			die("There is a error. try this example <br> <a href='". $link."'>" . $link. "</a>");
        }
		
	$parameters[0]= preg_replace("/[^0-9]/", "",$parameters[0]); // cleaning the number (if there are any character typo)
	$parameters[1]= preg_replace("/[^0-9]/", "",$parameters[1]); // cleaning the number (if there are any character typo)
	create_image($parameters[0], $parameters[1], "dddddd", "969696", "d5d5d5");

} else {

	$link=curPageURL() . "600x350";
	echo "There is a error. try this example <br> <a href='". $link."'>" . $link. "</a>" ;  

}


?>
