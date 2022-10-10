<?php
/**
 *
 * @package templates/default
 *
 */
defined('ABSPATH') || defined('DUPXABSPATH') || exit;
?>
<!DOCTYPE html>
<html>
    <?php dupxTplRender('pages-parts/boot-error/header'); ?>
    <body id="page-boot-error">
        <div>
            <h1>DUPLICATOR PRO: ISSUE</h1>
            Problem on duplicator init.<br>
            Message: <b><?php echo htmlspecialchars($message); ?></b>
        </div>
    </body>
</html>