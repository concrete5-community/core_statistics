<?php 
defined('C5_EXECUTE') or die('Access Denied.');

echo Core::make('helper/concrete/ui')->tabs($tabs, false);
?>

<div style="margin-top: 20px;">
    <table class="table table-striped table-bordered">
        <tbody>
        <tr><td style="width: 180px;"><?php  echo t("Total pages:") ?></td><td><?php  echo $pages_total ?></td></tr>
        <tr><td><?php  echo t("Pages approved:") ?></td><td><?php  echo $pages_approved ?></td></tr>
        <tr><td><?php  echo t("Pages unapproved:") ?></td><td><?php  echo count($pages_unapproved) ?></td></tr>
        <tr><td><?php  echo t("Pages in trash:") ?></td><td><?php  echo $pages_in_trash ?></td></tr>
        <tr><td><?php  echo t("Pages in draft:") ?></td><td><?php  echo $pages_in_drafts ?></td></tr>
        </tbody>
    </table>

    <br />

    <?php 
    if (count($page_types)) {
        ?>
        <h3><?php  echo t("Page Types") ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <th style="width: 180px"><?php  echo t("Pages") ?></th>
                <th><a href="<?php  echo URL::to('/dashboard/pages/types') ?>"><?php  echo t("Page Type") ?></a></th>
            </thead>
            <tbody>
            <?php 
            foreach ($page_types as $pt) {
                ?>
                <tr>
                    <td><?php  echo $pt['num_pages'] ?></td>
                    <td>
                        <?php 
                        echo ($pt['ptName']) ? $pt['ptName'] : t("Unknown");
                        ?>
                    </td>
                </tr>
                <?php 
            }
            ?>
            </tbody>
        </table>
        <br />
        <?php 
    }
    ?>


    <?php 
    if (count($page_templates)) {
        ?>
        <h3><?php  echo t("Page Templates") ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <th style="width: 180px"><?php  echo t("Pages") ?></th>
                <th><a href="<?php  echo URL::to('/dashboard/pages/templates') ?>"><?php  echo t("Template") ?></a></th>
            </thead>
            <tbody>
            <?php 
            foreach ($page_templates as $pt) {
                ?>
                <tr>
                    <td><?php  echo $pt['num_pages'] ?></td>
                    <td>
                        <?php 
                        if ($pt['pTemplateName']) {
                            echo '<a href="'.URL::to('dashboard/pages/templates/edit/', $pt['pTemplateID']).'">'.$pt['pTemplateName'].'</a>';
                        } else {
                            echo t("Unknown");
                        }
                        ?>
                    </td>
                </tr>
                <?php 
            }
            ?>
            </tbody>
        </table>
        <br />
        <?php 
    }
    ?>

    <?php 
    if (count($pages_unapproved)) {
        ?>
        <h3><?php  echo t("Unapproved pages") ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
            <th style="width: 180px"><?php  echo t("Date modified") ?></th>
            <th><?php  echo t("Page") ?></th>
            </thead>
            <tbody>
            <?php 
            foreach ($pages_unapproved as $page) {
                ?>
                <tr>
                    <td><?php  echo $page['cDateModified'] ?></td>
                    <td>
                        <?php 
                        if ($page['name']) {
                            echo '<a target="_blank" href="'.URL::to('index.php?cID='.$page['cID']).'">'.$page['name'].'</a>';
                        } else {
                            echo t("Unknown");
                        }
                        ?>
                    </td>
                </tr>
                <?php 
            }
            ?>
            </tbody>
        </table>
        <br />
        <?php 
    }
    ?>


    <?php 
    if (count($pages_with_most_versions)) {
        ?>
        <h3><?php  echo t("Top %d pages with most versions", min(5, count($pages_with_most_versions))) ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <th style="width: 180px"><?php  echo t("Versions") ?></th>
                <th><?php  echo t("Page") ?></th>
            </thead>
            <tbody>
            <?php 
            foreach ($pages_with_most_versions as $page) {
                ?>
                <tr>
                    <td><?php  echo $page['num_versions'] ?></td>
                    <td>
                        <?php 
                        if ($page['cvName']) {
                            echo '<a href="'.URL::to('index.php?cID='.$page['cID']).'">'.$page['cvName'].'</a>';
                        } else {
                            echo t("Unknown");
                        }
                        ?>
                    </td>
                </tr>
                <?php 
            }
            ?>
            </tbody>
        </table>
        <?php 
    }
    ?>
</div>
