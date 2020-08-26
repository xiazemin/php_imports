<?php
/**
 * Created by PhpStorm.
 * Date: 20/8/26
 * Time: 11:21
 * @category Category
 * @package FileDirFileName
 * @author xiazemin <xiazemin@didichuxing.com>
 * @link ${link}
 */
require_once (__DIR__."/../util/strings.php");
require_once (__DIR__."/../file/findFiles.php");
require_once (__DIR__."/../namespace/fileCheck.php");
require_once (__DIR__."/../namespace/globalCheck.php");

$sDir= $argv[1];
$file=$argv[2];
$content=file_get_contents($file);
echo($content);
$aWhiteList=json_decode($content,true);
//var_dump($aWhiteList);
//die();

nsCheck($sDir, $aWhiteList);