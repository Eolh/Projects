<table>

    <?php
    /**
     * Created by PhpStorm.
     * User: JH
     * Date: 2015-11-06
     * Time: 오후 2:14
     */

    for ($i = 1; $i <=6; $i++) {
        $codeNum = $i * 100;
        if ($value == $codeNum) {
            ?>
            <tr>
                <td><img src="../../img/imgmenu2/menu<?= $i ?>c.jpg" width="90px" height="30px"></a></td>
            </tr>
        <?php } else { ?>
            <tr>
                <td><a id="#" href="../controller/MainCTL.php?action=<?= $codeNum ?>"><img id="m_memu"
                                                                                           src="../../img/imgmenu/menu<?= $i ?>.jpg"
                                                                                           width="90px"
                                                                                           height="30px"></a></td>
            </tr>
            
        <?php }
    } ?>

</table>