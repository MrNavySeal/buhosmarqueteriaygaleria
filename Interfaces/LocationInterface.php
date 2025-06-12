<?php
    interface LocationInterface{
        function getLocation(string $ip,string $format,array $fields=[]); 
    }
?>