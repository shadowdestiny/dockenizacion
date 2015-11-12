<?php
$argv[1] = 'Doctrine';
$argv[2] = 'getEntityManager';

include 'apps/cli.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
