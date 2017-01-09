<?php

namespace SilverLeague\Async;

use AsyncPHP\Process\Handler;

class Daemon
{
    /**
     * @var Handler
     */
    private $handler;

    /**
     * @var string
     */
    private $id = "SILVERSTRIPE_ASYNC_DAEMON";

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function start(string $basePath)
    {
        if ($this->handler->running($this->id)) {
            return;
        }

        $binary = PHP_BINARY;

        $modulePath = SILVERSTRIPE_ASYNC_MODULE_PATH;

        $this->handler->start($this->id, "{$binary} {$basePath}/vendor/bin/aerys -d -c {$modulePath}/server.php");
    }

    public function running()
    {
        return $this->handler->running($this->id);
    }

    public function stop()
    {
        $this->handler->stop($this->id);
    }
}
