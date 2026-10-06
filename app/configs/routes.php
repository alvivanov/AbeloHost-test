<?php

declare(strict_types=1);

use Framework\Routing\RouterInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

return static function (RouterInterface $router): void {
    $router->get('/', static fn(ServerRequestInterface $request, array $vars): Response => new Response(
        200,
        [],
        'test',
    ));
};
