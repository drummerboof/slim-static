<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <title><?php echo $title; ?> | My Site</title>
    </head>

    <body>

        <?php echo $this->partial('navigation.php', array('title' => 'Test')); ?>

        <div>
            <?php echo $_content; ?>
        </div>

        <?php echo $title; ?>

    </body>
</html>
