<?php
    
    class UploadImages extends Controllers{
        public function __construct(){
            session_start();
            parent::__construct();
        }
        public function UploadImages(){
            if($_SESSION['permit'][5]['r'] || $_SESSION['permit'][4]['r'] ){
                $imageFolder ='Assets/images/tinyImg/'; 
                $name= "tinyImg_".bin2hex(random_bytes(6)).".jpg";
                $filetowrite = $imageFolder.$name; 
                reset ($_FILES); 
                $temp = current($_FILES); 
                $name= "tinyImg_".bin2hex(random_bytes(6)).".jpg";
                // Accept upload if there was no origin, or if it is an accepted origin 
                $filetowrite = $imageFolder.$name; 
                if(move_uploaded_file($temp['tmp_name'], $filetowrite)){ 
                    // Determine the base URL 
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://"; 
                    $baseurl = BASE_URL."/".$filetowrite; 
                    echo json_encode(array('location' => $baseurl)); 
                }else{ 
                    header("HTTP/1.1 400 Upload failed."); 
                    return; 
                }
            }
        }
    }
?>