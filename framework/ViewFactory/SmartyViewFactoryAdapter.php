<?php

namespace Framework\ViewFactory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Smarty\Smarty;

final class SmartyViewFactoryAdapter implements ViewFactoryInterface
{
    public function __construct(
        private Smarty                   $smarty,
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface   $streamFactory,
        string                           $templateDir,
        string                           $cacheDir
    )
    {
        $this->smarty->setCacheDir($cacheDir);
        $this->smarty->setTemplateDir($templateDir);
        $this->smarty->setCompileDir($cacheDir);
        $this->smarty->setConfigDir($cacheDir);
    }

    public function create(string $view, array $params): ResponseInterface
    {
        foreach ($params as $name => $value) {
            $this->smarty->assign($name, $value);
        }

        $renderedView = $this->smarty->fetch($view);
        $this->smarty->clearAllAssign();

        return $this
            ->responseFactory
            ->createResponse()
            ->withBody($this->streamFactory->createStream($renderedView));
    }
}