<?php foreach($autoList as $autoLister) { ?>
    <tr>
        <td><?= $autoLister->phoneNum ?></td>
        <td><?= $autoLister->calName ?></td>
        <td><?= $autoLister->keyword ?></td>
    </tr>
<?php } ?>