<?php 
defined('C5_EXECUTE') or die('Access Denied.');

$nh = Core::make('helper/number');

echo Core::make('helper/concrete/ui')->tabs($tabs, false);
?>

<div style="margin-top: 20px;">
    <table class="table table-striped table-bordered">
        <tbody>
            <tr><td style="width: 180px;"><?php  echo t("Total files:") ?></td><td><?php  echo $files_total ?></td></tr>
            <tr><td><?php  echo t("Total file size:") ?></td><td><?php  echo $nh->formatSize($file_size_total) ?></td></tr>
            <tr><td><?php  echo t("Total file sets:") ?></td><td><?php  echo $file_sets_total ?></td></tr>
        </tbody>
    </table>

    <br />

    <?php 
    if (count($file_sets)) {
        ?>
        <h3><?php  echo t("Files per File Set") ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <th style="width: 180px"><?php  echo t("Files") ?></th>
                <th><a href="<?php  echo URL::to('/dashboard/files/sets') ?>"><?php  echo t("File Set") ?></a></th>
            </thead>
            <tbody>
                <?php 
                foreach ($file_sets as $fs) {
                    ?>
                    <tr>
                        <td><?php  echo $fs['num_files'] ?></td>
                        <td>
                            <?php 
                            if ($fs['fsName']) {
                                echo '<a href="' . URL::to('dashboard/files/sets/view_detail/', $fs['fsID']) . '">' . $fs['fsName'] . '</a>';
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
    if (count($most_downloaded_files)) {
        ?>
        <h3><?php  echo t("Top %d downloaded files", 10); ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
            <th style="width: 180px"><?php  echo t("Downloaded") ?></th>
            <th><?php  echo t("File Name") ?></th>
            </thead>
            <tbody>
            <?php 
            foreach ($most_downloaded_files as $row) {
                $f = File::getByID($row['fID']);
                ?>
                <tr>
                    <td><?php  echo $row['num_downloads'] ?></td>
                    <td>
                        <?php 
                        $title = ($f->getTitle()) ? $f->getTitle() : t("Unknown");
                        echo '<a target="_blank" href="' . $f->getURL() . '">' . $title . '</a>';
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
    if (count($largest_files)) {
        ?>
        <h3><?php  echo t("Top %d largest files", 10); ?></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <th style="width: 180px"><?php  echo t("File Size") ?></th>
                <th><?php  echo t("File Name") ?></th>
            </thead>
            <tbody>
            <?php 
            foreach ($largest_files as $fv) {
                ?>
                <tr>
                    <td><?php  echo $fv->getSize() ?></td>
                    <td>
                        <?php 
                        if ($fv->getTitle()) {
                            echo '<a target="_blank" href="' . $fv->getURL() . '">' . $fv->getTitle() . '</a>';
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
</div>
