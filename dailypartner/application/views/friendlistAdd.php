<?php
foreach ($friendInfo as $friend) { ?>
    <tr id=friend<?= $friend->UID ?>>
        <td class="col-sm-1" style="vertical-align: middle">
            <div class="row-picture">
                <img class="circle" src="/public/img/NULL.png" alt="icon">
                <!-- src="" -->
            </div>
        </td>
        <td class="col-lg-8 col-sm-8">
            <p style="padding: 5px 8px; margin: 0;"><?= $friend->name . " ( " . $friend->ID . " ) "; ?>
            </p>

            <p style="padding: 5px 8px; margin: 0;"><i class="fa fa-phone-square"
                                                       aria-hidden="true"></i>&nbsp;<?= $friend->Tel; ?>

        </td>
        <td class="col-lg-3 col-sm-3" style="margin-right: 8px; vertical-align: middle;">
            <a onclick="add_friend_in_group('<?= $friend->UID; ?>')" title="<?= $friend->UID; ?>"
               class="btn btn-fab" style="height: 40px; min-width: 40px; width: 40px; float:right;"><i
                    class="material-icons" style="font-weight: bolder">add</i></a>
        </td>
    </tr>
<?php } ?>