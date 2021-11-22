<?php


$i=1;
$j=1;
$k=1;
$l=1;

for ($i = 3; $i <= 8; $i++) {
    $j=1;
    $k=1;
    $l=1;
    //echo 05-01-001-01-2
    //        $i  $j $k $l

    if($i==7){
        $j=2;
    }elseif($i==8){
        $j=4;
    }

    while($j <= 9){
        $k=1;
        $l=1;

        $max_k=6;

        if($i==7){
            $max_k=5;
        }elseif($i==8){
            if($j<=5){
                $max_k=9;
            }else{
                $max_k=7;
            }

        }

        while($k <= $max_k){
            $l=1;
            $max_l=3;

            if($j==2){
                $max_l=2;
            }


            while($l <= $max_l){
                echo "<br> 05-".str_pad($i, 2, '0', STR_PAD_LEFT)."-".str_pad($j, 3, '0', STR_PAD_LEFT)."-".str_pad($k, 2, '0', STR_PAD_LEFT)."-".$l;
//                //05-01-001-01-2
                $l++;
            }
            $k++;
        }
        $j++;
    }
}

?>