<?php
    require_once('src/connect.php');
    require_once('src/twig.php');
    require_once('src/close.php');

    echo $twig->render('template.twig');
?>