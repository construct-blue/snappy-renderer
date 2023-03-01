<?php

declare(strict_types=1);

namespace SnappyRenderer\Exception;

use Exception;
use Throwable;

class RenderException extends Exception
{
    public const CODE_MAX_NESTING_LEVEL = 510;

    /**
     * @param mixed $element
     * @return RenderException
     */
    public static function forInvalidElement($element): RenderException
    {
        return new RenderException(
            sprintf(
                'Element of type %s could not be rendered',
                self::getType($element),
            )
        );
    }


    private static function getType($element): string
    {
        $type = gettype($element);
        if ($type === 'object') {
            $type = get_class($element);
        }
        return $type;
    }

    /**
     * @param Throwable $throwable
     * @param $element
     * @return RenderException
     */
    public static function forThrowableInElement(Throwable $throwable, $element): RenderException
    {
        if ($throwable instanceof self) {
            return $throwable;
        }
        $type = self::getType($element);
        $error = get_class($throwable);
        return new RenderException("$error in $type: " . $throwable->getMessage(), 0, $throwable);
    }
}