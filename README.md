使用
./phpimport.phar  /Users/didi/php/srcDir ../ignore.json

其中ignore是顶级namespace白名单
cat ../ignore.json
["LibA","LibB","JsonSerializable","ArrayObject","Exception"]

比如
use LibA\C|ClassD;
就可以命中白名单