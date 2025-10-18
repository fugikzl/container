<?php

declare(strict_types=1);

namespace Fugikzl\Container\Internal\Exception;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

final class UnableToResolveException extends Exception implements ContainerExceptionInterface
{
    public function __construct(?string $id = null, ?string $details = null, ?Throwable $previous = null)
    {
        $msg = $id === null
            ? 'Unable to resolve entry.'
            : sprintf('Unable to resolve entry "%s."', $id);

        if ($details) {
            $msg .= $details;
        }

        parent::__construct(
            message: $msg,
            previous: $previous
        );
    }
}
