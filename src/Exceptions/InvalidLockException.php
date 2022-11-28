<?php

class InvalidLockException extends \Exception implements AutodiscoveryException
{
    private function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function lockIsInvalid(): self
    {
        return new self('The autodiscovery lock file is not valid JSON.');
    }
}
