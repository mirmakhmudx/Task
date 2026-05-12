<?php

namespace App\Services\Sms;

class ArraySender implements SmsSender
{
    private array $messages = [];

    public function send(string $number, string $text): bool
    {
        $this->messages[] = compact('number', 'text');
        return true;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
