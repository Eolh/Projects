<?php

class error_reporter
{
    public function __get($temp)
    {
        echo $temp."변수를 불러온다고??? 뭐하는 짓임???";
        echo "<br>";
    }

    public function __call($tempName, $tempArgs)
    {
        echo $tempName."메소드를 불러온다고??? 뭐하는 짓임???";
        echo "<pre> 보내는 메소드에 넣은 변수값들";
        var_dump($tempArgs);
        echo "</pre>";
    }
}

function getArgDump($dump)
{
    echo "<pre>";
    var_dump($dump);
    echo "</pre>";
}