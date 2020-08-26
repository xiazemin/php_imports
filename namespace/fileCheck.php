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

function getNs($file){
    $aNs=[];
    exec("cat $file |grep 'namespace' |awk '{print $2}'",$aNs);
    $aRet=[];
    foreach ($aNs as $n){
        $aN=explode(";",$n);
        $aRet[]=$aN[0];
    }
    return $aRet;
}

function getImport($file){
    $aUse=[];
    //cat $file |grep 'use ' |awk '{print $2}' |uniq |sort
    //"cat $file |grep 'use ' |grep -vE \"[a-zA-z]+use\" |awk -F';' '{print $1}' |uniq |sort"
    exec("cat $file |grep 'use ' |grep -vE \"[a-zA-z]+use \" |awk -F';' '{print $1}' |uniq |sort",$aUse);
    $aUseRet=[];
    foreach ($aUse as $k){
        $aFull=explode(' ',merge_spaces(trim($k)));
        //print_r($aFull);
        if(count($aFull)==2){
          $aLast=explode("\\",$aFull[1]);
          //print_r($aLast);
          $aUseRet[$aLast[count($aLast)-1]]=$aFull[1];
        }elseif (count($aFull)==4){
            $aUseRet[$aFull[3]]=$aFull[1];
            //print_r($aFull);
        }else if($aFull[0]=='function'){
            //闭包
        } else{
            echo "\n 异常import $k \n";
           // print_r($aFull);
        }

    }
    //print_r($aUseRet);
    return $aUseRet;
}


function getCall($file){
    $aFullNs=[];
    $aShortNs=[];
    $sCmdFullNs="cat $file |grep -v 'use ' |grep -v 'namespace' |grep -oE \"[a-z0-9A-Z_\\\]*\\\\\\+[a-z0-9A-z_\\\]*\" |uniq |sort";
   // echo $sCmdFullNs;
    exec($sCmdFullNs,$aFullNs);
    exec("cat $file |grep -oE \"[a-z0-9A-Z_\\]*::\" |awk -F':' '{print $1}' |uniq |sort",$aShortNs);
    return [$aFullNs,$aShortNs];
}


function findClass($aFiles,$sNs){
    //print_r($aFiles);
    foreach ($aFiles as $file) {
        $aClass = [];
        $sCmd="cat $file |grep -E \"class[ ]+$sNs\"";
        exec($sCmd, $aClass);
        //print_r([$sCmd,$aClass,$sNs,$file]);
        if (!empty($aClass)) {
            return true;
        }
    }
    return false;
}
/**
echo -e "\033[31m 红色字 \033[0m"

echo -e "\033[34m 黄色字 \033[0m"

echo -e "\033[41;33m 红底黄字 \033[0m"

echo -e "\033[41;37m 红底白字 \033[0m"
 */
function fileCheck($file,$aNsMap){
    $aUse= getImport($file);
    list($aFullNs,$aShortNs)=getCall($file);
    //print_r($aUse);
    //print_r($aFullNs);
    //print_r($aShortNs);
    $aGlobalImport=[];

    $aSelfNs=getNs($file);
    foreach ($aShortNs as $sNs){
        if($sNs=="self" || $sNs=='parent'){
            continue;
        }
        if(empty($aUse[$sNs])){
            $aPartialNs=explode("\\",$sNs);
            $bFind=false;
            foreach ($aPartialNs as $pNs){
                if(!empty($aUse[$pNs])){
                    $bFind=true;
                    break;
                }
            }
            if($bFind){
                continue;
            }
            if($sNs[0]=="\\"){
                $aGlobalImport[]=$sNs;
            }else {
                foreach ($aSelfNs as $selfNs) {
                    if (!empty($aNsMap) && !isset($aNsMap[$selfNs])) {
                        echo "\033[31m \n need import $sNs in file $file \n \033[0m";
                    } else if (empty($aNsMap)) {
                        echo "\033[31m \n need import $sNs in file $file \n \033[0m";
                    } else if (!findClass($aNsMap[$selfNs], $sNs)) {
                        echo "\033[31m \n need import $sNs in file $file \n \033[0m";
                    }
                }
            }
        }
    }

    //$aFullNs=["abc\dfc"];
    foreach ($aFullNs as $sNs){
        $aFull=explode("\\",trim($sNs,"\\"));
        if(empty($aUse[$aFull[0]])){
            //echo "\033[31m \n need import $sNs in file $file \n \033[0m";
            $aGlobalImport[]=$sNs;
        }
    }
    return [$aUse,$aGlobalImport];
}

//fileCheck("/Users/didi/PhpstormProjects/php/hermesAPI/DuKangHermes/Transport/Base/DPullOrderBaseTransport.php");