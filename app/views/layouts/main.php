<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <title><?php echo $this->page()->title(); ?> | Slim Static</title>
    </head>

    <body>

        <?php echo $this->partial('navigation.php', array('title' => 'Test')); ?>

        <div>
            <?php echo $_content; ?>
        </div>

        <?php echo $this->page()->title(); ?>

    </body>
</html>
