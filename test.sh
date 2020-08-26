#!/bin/bash

php file/findFiles.php |grep '.php' |awk '{print $3}' |uniq |wc -l

find *.php . |grep php  |wc -l