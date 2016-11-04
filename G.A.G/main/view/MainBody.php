<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-06
 * Time: 오후 2:15
 */

if ($MainMenuNum != 0 && $MainMenuNum <= 6)
    include "body/BodyPage100.php";
else
    include "body/Bodypage{$value}.php";
?>