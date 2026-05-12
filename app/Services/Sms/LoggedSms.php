<?php

namespace App\Services\Sms;

use Psr\Log\LoggerInterface;

class LoggedSms implements SmsSender
{
    private SmsSender       $next;
    private LoggerInterface $logger;

    public function __construct(SmsSender $next, LoggerInterface $logger)
    {
        $this->next   = $next;
        $this->logger = $logger;
    }

    public function send(string $number, string $text): void
    {
        $this->logger->info($text, ['number' => $number]);
        $this->next->send($number, $text);
    }
}
