<?php

namespace App\Services\Sms;

class ArraySender implements SmsSender
{
    private array $log = [];

    public function send(string $number, string $text): void
    {
        $this->log[] = [
            'number' => $number,
            'text' => $text,
        ];
    }

    public function getLog(): array
    {
        return $this->log;
    }


}
