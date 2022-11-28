<?php

class InvalidManifestException extends \Exception implements AutodiscoveryException
{
    private function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function autoDiscoveryIsEmpty(): self
    {
        return new self('The autodiscovery manifest is empty.');
    }
}
