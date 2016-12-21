<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
   $image = "../../../../" . $_POST['data_url'];
   
   if(file_exists($image))
   {
       $path_parts = pathinfo($image);
       $dirname = $path_parts['dirname'];
       $name_image = basename($image);
       
       $image_original = $image;
       $image_thumb = $dirname . '/thumbnail/' . $name_image;
       $image_web = $dirname .'/'. substr($path_parts['filename'],0,strlen($path_parts['filename'])-5) .'_web_'. substr($path_parts['filename'],-4) .'.'. $path_parts['extension'];

        unlink($image_original);
        if(file_exists($image_thumb))
            unlink($image_thumb);
        if(file_exists($image_web))
            unlink($image_web);
        echo '1';
   }
   else
   {
        echo '0';
   }
}
