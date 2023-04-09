<?php

declare(strict_types=1);

namespace SnappyRenderer\Exception;

use Exception;
use Throwable;

class RenderException extends Exception
{
    /**
     * @param mixed $view
     * @return RenderException
     */
    public static function forInvalidView($view): RenderException
    {
        return new RenderException(
            sprintf(
                'View of type "%s" could not be rendered.',
                self::getType($view),
            )
        );
    }

    public static function forMaxNestingLevel($view, $level): RenderException
    {
        return new RenderException(
            sprintf(
                'Max nesting level of %s reached in view "%s".',
                $level,
                self::getType($view),
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