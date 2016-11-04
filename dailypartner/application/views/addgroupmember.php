<!--friend List에서 Group Member로 추가할 때-->
<?php
if ($MemberInfo) {
    foreach ($MemberInfo as $member) { ?>
        <tr id="friend<?= $member->UID; ?>">
            <td class="col-sm-1" style="vertical-align: middle">
                <div class="row-picture">
                    <img class="circle" src="/public/img/NULL.png" alt="icon" style="opacity: 0.5;">
                </div>
            </td>
            <td class="col-lg-8 col-sm-8">
                <p style="padding: 5px 8px; margin: 0;"><?= $member->name . " ( " . $member->ID . " ) "; ?>
                </p>
                <p style="padding: 5px 8px; margin: 0;"><i class="fa fa-phone-square" aria-hidden="true"></i>
                    &nbsp;<?= $member->Tel; ?>
                </p>
            </td>
            <td class="col-lg-3 col-sm-3" style="margin-right: 8px; vertical-align: middle;">
                <a title="<?= $member->UID; ?>" class="btn btn-fab"
                   style="height: 40px; min-width: 40px; width: 40px; float:right;">
                    <i class="material-icons" style="font-weight: bolder">remove</i></a>
            </td>
        </tr>
    <?php }
} else null; ?>
