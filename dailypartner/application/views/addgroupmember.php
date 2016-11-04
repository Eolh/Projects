<?php
if($MemberInfo!=null) {
    foreach ($MemberInfo as $member) { ?>
        <tr id=friend<?= $member->UID ?>>
            <td class="col-sm-1" style="vertical-align: middle">
                <div class="row-picture">
                    <img class="circle" src="http://lorempixel.com/56/56/people/1" alt="icon">
                    <!-- src="" -->
                </div>
            </td>
            <td class="col-lg-8 col-sm-8">
                <p style="padding: 5px 8px; margin: 0;"><?= $member->name . " ( " . $member->ID . " ) "; ?>
                </p>

                <p style="padding: 5px 8px; margin: 0;"><i class="fa fa-phone-square"
                                                           aria-hidden="true"></i>&nbsp;<?= $member->Tel; ?>

            </td>
        </tr>
    <?php }
}
else null; ?>