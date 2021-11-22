<?php

function cree_stl($nom,$x_1,$y_1,$z_1){

    $nom = htmlentities($nom, ENT_NOQUOTES, 'utf-8');
    $nom = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $nom);
    $nom = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $nom);
    $nom = preg_replace('#&[^;]+;#', '', $nom);
    $nom = str_replace(array(":"," ","!",".","?",'"','/'), "_", $nom);
    $nom = str_replace(array("__",), "_", $nom);
    $nom = str_replace(array("__",), "_", $nom);
    $nom = str_replace(array("__",), "_", $nom);

    //$a_1=28;
    //$b_1=1;
    //$c_1=228;

    $array_dim = array("x" => round($x_1,0),"y" => round($y_1,0),"z" => round($z_1,0));
    asort($array_dim);
    $i=0;
    foreach ($array_dim as $key => $value){
        if($i==0){
            $b=sprintf("%.e", $value);
        }elseif($i==1){
            $c=sprintf("%.e", $value);
        }elseif($i==2){
            //$array_dim[$key]=10;
            $a=sprintf("%.e", 10);
        }
        $i++;
    }
    //$array_dim[2] = 10;
    /*$fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");
    asort($fruits);
    var_dump($fruits);*/
    //var_dump($array_dim);


    //$a=sprintf("%.e", $array_dim["z"]);
    //$b=sprintf("%.e", $array_dim["x"]);
    //$c=sprintf("%.e", $array_dim["y"]);

    //var_dump($c);

    $data = null;
    $data .= "solid ASCII\n";
    $data .= "  facet normal 0.000000e+00 0.000000e+00 -".$a."\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   ".$a." 0.000000e+00 -".$c."\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 -".$c."\n";
    $data .= "      vertex   ".$a." ".$b." -".$c."\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal -0.000000e+00 0.000000e+00 -".$a."\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   ".$a." ".$b." -".$c."\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 -".$c."\n";
    $data .= "      vertex   0.000000e+00 ".$b." -".$c."\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal ".$a." -0.000000e+00 0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   ".$a." 0.000000e+00 0.000000e+00\n";
    $data .= "      vertex   ".$a." 0.000000e+00 -".$c."\n";
    $data .= "      vertex   ".$a." ".$b." 0.000000e+00\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal ".$a." 0.000000e+00 0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   ".$a." ".$b." 0.000000e+00\n";
    $data .= "      vertex   ".$a." 0.000000e+00 -".$c."\n";
    $data .= "      vertex   ".$a." ".$b." -".$c."\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal 0.000000e+00 0.000000e+00 ".$a."\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 0.000000e+00\n";
    $data .= "      vertex   ".$a." 0.000000e+00 0.000000e+00\n";
    $data .= "      vertex   0.000000e+00 ".$b." 0.000000e+00\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal -0.000000e+00 0.000000e+00 ".$a."\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   0.000000e+00 ".$b." 0.000000e+00\n";
    $data .= "      vertex   ".$a." 0.000000e+00 0.000000e+00\n";
    $data .= "      vertex   ".$a." ".$b." 0.000000e+00\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal -".$a." 0.000000e+00 0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 -".$c."\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 0.000000e+00\n";
    $data .= "      vertex   0.000000e+00 ".$b." -".$c."\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal -".$a." 0.000000e+00 0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   0.000000e+00 ".$b." -".$c."\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 0.000000e+00\n";
    $data .= "      vertex   0.000000e+00 ".$b." 0.000000e+00\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal 0.000000e+00 ".$a." -0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   0.000000e+00 ".$b." -".$c."\n";
    $data .= "      vertex   0.000000e+00 ".$b." 0.000000e+00\n";
    $data .= "      vertex   ".$a." ".$b." -".$c."\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal 0.000000e+00 ".$a." 0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   ".$a." ".$b." -".$c."\n";
    $data .= "      vertex   0.000000e+00 ".$b." 0.000000e+00\n";
    $data .= "      vertex   ".$a." ".$b." 0.000000e+00\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal 0.000000e+00 -".$a." 0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 -".$c."\n";
    $data .= "      vertex   ".$a." 0.000000e+00 -".$c."\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 0.000000e+00\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "  facet normal 0.000000e+00 -".$a." 0.000000e+00\n";
    $data .= "    outer loop\n";
    $data .= "      vertex   0.000000e+00 0.000000e+00 0.000000e+00\n";
    $data .= "      vertex   ".$a." 0.000000e+00 -".$c."\n";
    $data .= "      vertex   ".$a." 0.000000e+00 0.000000e+00\n";
    $data .= "    endloop\n";
    $data .= "  endfacet\n";
    $data .= "endsolid\n";

    file_put_contents('../STL/'.$nom.'.stl', $data);

    return $nom." (".$array_dim["x"].",".$array_dim["y"].",".$array_dim["z"]."): ok %";
    //return $nom." (".$a.",".$c.",".$b."): ok";
    //return $nom." : ok";


}

//var_dump(cree_stl("test",5,4,3));
