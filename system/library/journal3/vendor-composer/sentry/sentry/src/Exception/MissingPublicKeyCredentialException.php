<?php

declare(strict_types=1);

namespace Sentry\Exception;

use Throwable;

@trigger_error(sprintf('The %s class is deprecated since version 2.4 and will be removed in 3.0.', MissingPublicKeyCredentialException::class), \E_USER_DEPRECATED);

/**
 * This exception is thrown during the sending of an event when the public key
 * is not provided in the DSN.
 *
 * @deprecated since version 2.4, to be removed in 3.0
 */
final class MissingPublicKeyCredentialException extends \RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('The public key of the DSN is required to authenticate with the Sentry server.', 0, $previous);
    }
}
