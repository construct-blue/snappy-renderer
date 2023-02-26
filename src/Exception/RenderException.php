<?php

declare(strict_types=1);

namespace SnappyRenderer\Exception;

use Exception;

class RenderException extends Exception
{
    public static function forInvalidElement($element): RenderException
    {
        return new RenderException(
            sprintf(
                'Element of type %s could not be rendered',
                get_debug_type($element),
            )
        );
    }
}