<?php 
defined('C5_EXECUTE') or die('Access Denied.');

echo Core::make('helper/concrete/ui')->tabs($tabs, false);
?>

<div style="margin-top: 20px;">
    <table class="table table-striped table-bordered">
        <tbody>
            <tr><td style="width: 180px;"><?php  echo t("Total users:") ?></td><td><?php  echo $users_total ?></td></tr>
            <tr><td><?php  echo t("Active users:") ?></td><td><?php  echo $users_active ?></td></tr>
            <tr><td><?php  echo t("Inactive users:") ?></td><td><?php  echo $users_inactive ?></td></tr>
            <tr><td><?php  echo t("Validated users:") ?></td><td><?php  echo $users_validated ?></td></tr>
            <tr><td><?php  echo t("Not validated users:") ?></td><td><?php  echo $users_not_validated ?></td></tr>
        </tbody>
    </table>

    <br />

    <?php 
    if (count($groups)) {
        ?>
        <h3><?php  echo t("User Groups") ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <th style="width: 180px"><?php  echo t("Users") ?></th>
                <th><a href="<?php  echo URL::to('/dashboard/users/groups') ?>"><?php  echo t("Group") ?></a></th>
            </thead>
            <tbody>
            <?php 
            if (count($groups)) {
                foreach ($groups as $group) {
                    ?>
                    <tr>
                        <td><?php  echo $group['num_users'] ?></td>
                        <td>
                            <?php 
                            echo ($group['gName']) ? $group['gName'] : t("Unknown");
                            ?>
                        </td>
                    </tr>
                    <?php 
                }
            }
            ?>
            <tr><td>1</td><td><?php  echo t("Super User") ?></td></tr>
            </tbody>
        </table>
        <?php 
    }
    ?>
</div>