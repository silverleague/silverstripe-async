<?php

namespace SilverLeague\Async;

use AsyncPHP\Process\PosixHandler;
use LogicException;
use SilverStripe\CronTask\Interfaces\CronTask;

class DaemonCronTask implements CronTask
{
    /**
     * @inheritdoc
     */
    public function getSchedule() : string
    {
        return "* * * * *";
    }

    /**
     * @inheritdoc
     */
    public function process()
    {
        $daemon = new Daemon(new PosixHandler());
        $daemon->start($this->getPath());
    }

    /**
     * Get application base path.
     */
    private function getPath(): string
    {
        // ./silverstripe-async
        if (file_exists(__DIR__ . "/../../composer.json")) {
            return realpath(__DIR__ . "/../../");
        }

        // ./vendor/silverleague/silverstripe-async
        if (file_exists(__DIR__ . "/../../../../composer.json")) {
            return realpath(__DIR__ . "/../../../../");
        }

        // custom path (development)
        if ($path = getenv("SILVERSTRIPE_ASYNC_BASE_PATH")) {
            return realpath($path);
        }

        throw new LogicException(
            file_get_contents(SILVERSTRIPE_ASYNC_MODULE_PATH . "/exceptions/path-not-found.txt")
        );
    }
}
