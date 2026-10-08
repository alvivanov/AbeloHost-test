<?php

declare(strict_types=1);

use Framework\Application;
use Framework\ContainerBuilder\PhpDiContainerBuilderInterfaceAdapter;

require_once "../vendor/autoload.php";

$container = new PhpDiContainerBuilderInterfaceAdapter()->build([
    "../framework/configs/boot.php",
    "../framework/configs/dependencies.php",
    "../configs/dependencies.php",
    "../configs/db.php",
    "../configs/params.php",
]);

Application::create($container)->start();
