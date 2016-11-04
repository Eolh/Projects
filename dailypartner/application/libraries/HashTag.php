<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *
 * 태그 관리시에 사용할 예정
 *
 * */

class HashTag
{
    function convertHashTags($str)
    {
        $regex = "/#+([a-zA-Z0-9_]+)/";
        $str = preg_replace($regex, '<a href="$1">$0</a>', $str);

        return($str);
    }

    function convertFileTags($str, $url = tagURL)
    {
        //$regex = "/([a-zA-Z0-9_!@#$%^*()\-_=+\\\|\[\]{}:\",.<>\/?']+;)/";
        $regex = "/.+?;/";
        $str = preg_replace($regex, '<a href="{URL}$0">$0</a>', $str);
        $str = str_replace(';', ' ', $str);
        $str = str_replace("{URL}", $url, $str);

        return ($str);
    }

    function convertFileTagsMask($str, $url = tagURL)
    {
        $regex = "/.+?;/";
        $str = preg_replace($regex, '<mark><a href="{URL}$0">#$0</a></mark>&nbsp&nbsp&nbsp', $str);
        $str = str_replace(';', '', $str);
        $str = str_replace("{URL}", $url, $str);

        return ($str);
    }

    function convertHashTagsTo_ATAG($str, $url = URL)
    {
        $regex = "/@+([a-zA-Z0-9_]+)/";
        $str = preg_replace($regex, '<a href="{URL}$1">$0</a>', $str);

        $str = str_replace('{URL}', $url, $str);

        return($str);
    }
}