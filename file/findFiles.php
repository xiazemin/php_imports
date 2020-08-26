<?php
/**
 * Created by PhpStorm.
 * Date: 20/8/26
 * Time: 10:55
 * @category Category
 * @package FileDirFileName
 * @author xiazemin <xiazemin@didichuxing.com>
 * @link ${link}
 */

function getDirContents($dir){
    $aResults = array();
    $aFiles = scandir($dir);

    foreach($aFiles as $key => $value){
        //echo $key ."=>".$value;
        if(!is_dir($dir. DIRECTORY_SEPARATOR .$value)){
            $aResults[] = $dir. DIRECTORY_SEPARATOR .$value;
        } else if(is_dir($dir. DIRECTORY_SEPARATOR .$value)) {
            if($value != "." && $value != "..") {
                //$aResults[] = $dir. DIRECTORY_SEPARATOR .$value;
                $aRet=getDirContents($dir . DIRECTORY_SEPARATOR . $value);
                foreach ($aRet as $f){
                    $aResults[]=$f;
                }
            }
            //echo $dir. DIRECTORY_SEPARATOR .$value."\n";
        }
    }
    return $aResults;
}

//print_r(getDirContents('/Users/didi/PhpstormProjects/php/hermesAPI/DuKangHermes'));