<?php
/**
 * Created by PhpStorm.
 * Date: 20/8/26
 * Time: 12:01
 * @category Category
 * @package FileDirFileName
 * @author xiazemin <xiazemin@didichuxing.com>
 * @link ${link}
 */

/**
 * 多个连续空格只保留一个
 *
 * @param string $string 待转换的字符串
 * @return string $string 转换后的字符串
 */
function merge_spaces($string){
    return preg_replace("/\s(?=\s)/","\\1",$string);
}