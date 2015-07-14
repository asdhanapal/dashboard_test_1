<?php
function  sectohr($secs)
{
    $min=$secs/60;
    //$secs_remain=$secs%60;
    $hr=  floor($min/60);
    $remain_min=$min%60;
    return sprintf("%00d",$hr).":".sprintf("%02d",$remain_min).":00";//.$secs_remain;
}

function h2m($hours) 
{ 
     $t = explode(":", $hours); 
     $h = $t[0]; 
     if(ISSET($t[1]))
        $m = $t[1]; 
     else
        $m = "00"; 
 
     $mm = ($h * 60)+$m; 
     return $mm; 
}
?>