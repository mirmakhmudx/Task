<?php

namespace App;

use Illuminate\Support\Facades\App;
use RuntimeException;

class CommandBus
{
    private static array $map = [];

    public static function register(array $map): void
    {
        self::$map = array_merge(self::$map, $map);
    }

    public function handle(object $command): mixed
    {
        $commandClass = get_class($command);

        $handlerClass = isset(self::$map[$commandClass])
            ? self::$map[$commandClass]
            : str_replace('Command', 'Handler', $commandClass);

        if (!class_exists($handlerClass)) {
            throw new RuntimeException("Handler topilmadi: {$handlerClass}");
        }

        $handler = App::make($handlerClass);

        return $handler->handle($command);
    }
}
