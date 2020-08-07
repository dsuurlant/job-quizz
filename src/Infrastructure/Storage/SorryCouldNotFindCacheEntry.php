<?php
declare(strict_types=1);

namespace App\Infrastructure\Storage;


final class SorryCouldNotFindCacheEntry extends \RuntimeException
{
    public static function becauseItDoesNotExist(): self
    {
        return new self('Could not find the cache entry because it does not exist.');
    }
}