<?php

declare(strict_types=1);

use Framework\Application;
use Framework\ExceptionHandling\ExceptionHandler;
use Framework\ExceptionHandling\ExceptionHandlerInterface;
use Framework\Http\Emitter\EmitterInterface;
use Framework\Http\Emitter\SapiEmitter;
use Framework\LoggerFactory\LoggerFactoryInterface;
use Framework\LoggerFactory\MonologLoggerFactory;
use Framework\Routing\LeagueRouterInterfaceAdapter;
use Framework\Routing\RouterInterface;
use Framework\ViewFactory\SmartyViewFactoryAdapter;
use Framework\ViewFactory\ViewFactoryInterface;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use function DI\autowire;
use function DI\factory;
use function DI\get;

return [
    Application::class => get(Application::class),
    Psr17Factory::class => autowire(Psr17Factory::class),
    RequestFactoryInterface::class => get(Psr17Factory::class),
    ResponseFactoryInterface::class => get(Psr17Factory::class),
    ServerRequestFactoryInterface::class => get(Psr17Factory::class),
    StreamFactoryInterface::class => get(Psr17Factory::class),
    UploadedFileFactoryInterface::class => get(Psr17Factory::class),
    UriFactoryInterface::class => get(Psr17Factory::class),
    EmitterInterface::class => autowire(SapiEmitter::class),
    RouterInterface::class => get(LeagueRouterInterfaceAdapter::class),
    ExceptionHandlerInterface::class => autowire(ExceptionHandler::class),
    LoggerInterface::class => factory([LoggerFactoryInterface::class, 'create'])
        ->parameter('driver', get('boot.logDriver'))
        ->parameter('level', get('boot.logLevel')),
    ViewFactoryInterface::class => autowire(SmartyViewFactoryAdapter::class)
        ->constructorParameter('templateDir', get('boot.viewDir'))
        ->constructorParameter('cacheDir', get('boot.cacheDir')),
    LoggerFactoryInterface::class => autowire(MonologLoggerFactory::class)
        ->constructorParameter('logsDir', get('boot.logsDir')),
    PDO::class => factory(static fn(string $host, string $database, string $username, string $password): PDO => new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC],
    ))
        ->parameter('host', get('db.host'))
        ->parameter('database', get('db.name'))
        ->parameter('username', get('db.user'))
        ->parameter('password', get('db.password')),
    Router::class => static function (ContainerInterface $container): Router {
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);
        $router = new Router();
        $router->setStrategy($strategy);

        return $router;
    },
];
