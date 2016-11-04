<style type="text/css">
    @import url(/JHShop/css/mainCss.css);

</style>
<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-06
 * Time: 오후 2:18
 */
session_start();

$loginID = isset($_SESSION['loginID']) ? $_SESSION['loginID'] : null;
$userLevel = isset($_SESSION['userLevel']) ? $_SESSION['userLevel'] : 0;


$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 0;

$MainMenuNum = intval($action / 100);
$value = $MainMenuNum * 100;
$SubMenuNum = intval($action % 100);


?>