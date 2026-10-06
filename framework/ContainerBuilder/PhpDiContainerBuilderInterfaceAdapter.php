<?php

declare(strict_types=1);

namespace Framework\ContainerBuilder;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

final class PhpDiContainerBuilderInterfaceAdapter implements ContainerBuilderInterface
{
    public function build(array $definitionFiles = []): ContainerInterface
    {
        $builder = new ContainerBuilder();

        foreach ($definitionFiles as $definitionFile) {
            if (file_exists($definitionFile)) {
                $builder->addDefinitions($definitionFile);
            }
        }

        return $builder->build();
    }
}
