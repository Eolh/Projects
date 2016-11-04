<!-- 2016-07-26 getGroupList -->
<?php foreach ($groupList as $group) { ?>
    <li role="presentation" id="<?=$group->gnum?>">
        <a href="#group<?=$group->gnum?>" aria-controls="group" role="tab" data-toggle="tab" onclick="$('#clicker').trigger('click')">
            <?=$group->gname?>
        </a>
    </li>
<?php } ?>