<?php
/**
 * Created by PhpStorm.
 * Date: 20/8/26
 * Time: 11:23
 * @category Category
 * @package FileDirFileName
 * @author xiazemin <xiazemin@didichuxing.com>
 * @link ${link}
 */

function getGlobalImportMap($sDir){
    $sDir=trim(rtrim($sDir,"\\"));

    $aFiles=getDirContents($sDir);
    $aNsMap=[];
    foreach ($aFiles as $f){
        if(preg_match('/^.*\.php$/',$f)){
            $aNs=getNs($f);
            //print_r($aGlobalImport);
            //print_r($aNs);
            foreach ($aNs as $ns){
                //多个文件相同ns
                $aNsMap[$ns][]=$f;

            }
        }else{
            //echo "\n not php file:$f \n";
        }
    }

    //print_r($aNsMap);
    $aFinalNsMap=[];
    foreach ($aNsMap as $ns=>$aFiles){
        foreach ($aFiles as $f){
            list($aUse,$aGlobalImport)=fileCheck($f,$aNsMap);
            $aFinalNsMap[$ns][]=[
                'file'=> $f,
                'use'=>$aUse,
                'global_import'=>$aGlobalImport,
            ];
        }
    }
    return $aFinalNsMap;
}

function nsCheck($sDir,$aWhiteList){
    $aNsMap=getGlobalImportMap($sDir);
   foreach ($aNsMap as $ns=> $aDataList){
       foreach ($aDataList as $aData) {
           //print_r([$ns,$aData]);
           if (!empty($aData['use'])) {
               foreach ($aData['use'] as $short => $full) {
                   $aFull=explode("\\",ltrim(trim($full),"\\"));
                   //print_r([$aFull,$aFull[0],$aWhiteList,in_array($aFull[0],$aWhiteList)]);
                   if(!in_array($aFull[0],$aWhiteList)){
                       $file=$aData["file"];
                       if (empty($aNsMap[$full])) {
                           echo "\033[34m \n import outside namespace $full => $file \n \033[0m";
                           //echo "\033[34m \n $full not fund in $sDir\n \033[0m";
                       }

                   }
               }
           }
           if (!empty($aData['global_import'])) {
               foreach ($aData['global_import'] as $full) {
                   $aFull=explode("\\",ltrim(trim($full),"\\"));
                   if(count($aFull)>1) {
                       unset($aFull[count($aFull) - 1]);
                       $sNamespace = implode("\\", $aFull);
                   }else{
                       $sNamespace=$full;
                   }
                   //print_r($aFull);
                   if (empty($aNsMap[$sNamespace])&& !in_array($aFull[0],$aWhiteList)) {
                       echo "\033[34m \n global import $full not fund in $sDir\n \033[0m";
                   }
               }
           }
       }
   }
}