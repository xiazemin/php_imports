<?php
$exts = array(
        '*',
    );
$dir = __DIR__;
$file = 'phpimport.phar';
$phar = new Phar(__DIR__ . '/' . $file, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, $file);
$phar->startBuffering();
foreach ($exts as $ext) {
$phar->buildFromDirectory($dir, '/\.' . $ext . '$/');
}
$phar->delete('compress.php');
$phar->setStub("#!/usr/bin/env php\n".$phar->createDefaultStub('bin/phpimport.php'));
$phar->stopBuffering();
echo "打包完成".PHP_EOL;
