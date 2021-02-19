<?php 
defined('C5_EXECUTE') or die('Access Denied.');

echo Core::make('helper/concrete/ui')->tabs($tabs, false);
?>
<div style="margin-top: 20px;">
    <table class="table table-striped table-bordered">
        <tbody>
            <tr><td style="width: 180px;"><a href="<?php  echo URL::to('/dashboard/sitemap/full') ?>"><?php  echo t("Pages:") ?></a></td><td><?php  echo $pages_total ?></td></tr>
            <tr><td><a href="<?php  echo URL::to('/dashboard/users/search') ?>"><?php  echo t("Users:") ?></a></td><td><?php  echo $users_total ?></td></tr>
            <tr><td><a href="<?php  echo URL::to('/dashboard/files/search') ?>"><?php  echo t("Files:") ?></td><td><?php  echo $files_total ?></a></td></tr>
            <tr><td><a href="<?php  echo URL::to('/dashboard/files/sets') ?>"><?php  echo t("File sets:") ?></td><td><?php  echo $file_sets_total ?></a></td></tr>
            <tr><td><a href="<?php  echo URL::to('/dashboard/blocks/stacks') ?>"><?php  echo t("Stacks:") ?></td><td><?php  echo $stacks_total ?></a></td></tr>
            <tr><td><a href="<?php  echo URL::to('/dashboard/blocks/types') ?>"><?php  echo t("Blocks:") ?></td><td><?php  echo $blocks_total ?></a></td></tr>
            <tr><td><a href="<?php  echo URL::to('/dashboard/reports/logs') ?>"><?php  echo t("Log entries:") ?></td><td><?php  echo $logs_total ?></a></td></tr>
        </tbody>
    </table>
</div>
