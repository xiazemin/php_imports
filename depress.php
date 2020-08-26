<?php
define('ROOT',__DIR__);
$dir = ROOT.'/extract';
if(!is_dir($dir)){
@mkdir($dir);
}
$phar = new Phar(ROOT."/phpimport.phar");
$phar->extractTo($dir,null,true);
echo "解压完成".PHP_EOL;
