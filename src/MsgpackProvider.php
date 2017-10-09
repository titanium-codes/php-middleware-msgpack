<?php

declare(strict_types=1);

namespace TitaniumCodes\Middleware;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class MsgpackProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container['msgpack_middleware'] = $this->setMsgpack();
    }

    /**
     * Return callable of Msgpack middleware.
     *
     * @return callable
     */
    protected function setMsgpack(): callable
    {
        return function ($c) {
            $config = $c['config']('msgpack');

            return \TitaniumCodes\Middleware\Msgpack($config['body'], $config['content-type']);
        };
    }
}
