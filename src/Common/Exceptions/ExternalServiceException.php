<?php
namespace Wigilabs\Common\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Custom exception for errors in communications with external services.
 */
class ExternalServiceException extends RuntimeException {
    public function __construct(
        string $message = "External service error",
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Generate a secure message to display to the customer (without technical details).
     */
    public function getSafeMessage(): string {
        return "An error occurred while communicating with the external service. Please try again later.";
    }
}