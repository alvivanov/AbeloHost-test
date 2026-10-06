<?php

declare(strict_types=1);

namespace Framework\ContainerBuilder;

use Psr\Container\ContainerInterface;

interface ContainerBuilderInterface
{
    /**
     * @param string[] $definitionFiles
     */
    public function build(array $definitionFiles = []): ContainerInterface;
}
