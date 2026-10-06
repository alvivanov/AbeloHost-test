<?php

declare(strict_types=1);

namespace Framework\Http\Emitter;

use Psr\Http\Message\ResponseInterface;

class SapiEmitter implements EmitterInterface
{
    public function emit(ResponseInterface $response): void
    {
        if (!headers_sent()) {
            header(sprintf(
                'HTTP/%s %d %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase(),
            ));

            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header("{$name}: {$value}", false);
                }
            }
        }

        $body = $response->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        while (!$body->eof()) {
            echo $body->read(8192);
        }
    }
}
