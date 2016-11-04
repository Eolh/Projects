<tbody class="tab-content" id = "group_list">
<!-- Group Member -->
<div role="tabpanel" class="tab-pane row">
    <?php
    if ($groupMemberList) {
        foreach ($groupMemberList as $groupMemberInfo) {
            for ($i = 0; $i < count($groupMemberInfo); $i++) { ?>
                <tr>
                    <td class="col-sm-1" style="vertical-align: middle">
                        <div class="row-picture">
                            <img class="circle" src="/public/img/NULL.png" alt="icon" style="opacity: 0.5;">
                        </div>
                    </td>
                    <td class="col-lg-8 col-sm-8">
                        <p style="padding: 5px 8px; margin: 0;"><?= $groupMemberInfo[$i]->name . " ( " . $groupMemberInfo[$i]->ID . " ) "; ?>
                        </p>
                        <p style="padding: 5px 8px; margin: 0;"><i class="fa fa-phone-square" aria-hidden="true"></i>
                            &nbsp;<?= $groupMemberInfo[$i]->Tel; ?>
                        </p>
                    </td>
                    <td class="col-lg-3 col-sm-3" style="margin-right: 8px; vertical-align: middle;">
                        <a title="<?= $groupMemberInfo[$i]->UID; ?>" class="btn btn-fab" style="height: 40px; min-width: 40px; width: 40px; float:right;">
                            <i class="material-icons" style="font-weight: bolder">remove</i></a>
                    </td>
                </tr>
            <?php }
        }
    } else { ?>
        <tr>
            <td style="text-align: center" colspan="3"><h4>Not Member</h4></td>
        </tr>
    <?php } ?>
</div>

</tbody>