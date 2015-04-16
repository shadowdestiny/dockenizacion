<?php
$argv[1] = 'Doctrine';
$argv[2] = 'getEntityManager';

$em = include 'app/cli.php';
var_dump($em);