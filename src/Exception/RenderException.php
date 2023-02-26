<?php

declare(strict_types=1);

namespace SnappyRenderer\Exception;

use Exception;

class RenderException extends Exception
{
    /**
     * @param mixed $element
     * @return RenderException
     */
    public static function forInvalidElement($element): RenderException
    {
        $type = gettype($element);
        if ($type === 'object') {
            $type = get_class($element);
        }
        return new RenderException(
            sprintf(
                'Element of type %s could not be rendered',
                $type,
            )
        );
    }
}