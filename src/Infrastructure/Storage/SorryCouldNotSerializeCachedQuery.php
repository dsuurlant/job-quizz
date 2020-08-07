<?php
declare(strict_types=1);

namespace App\Infrastructure\Storage;


final class SorryCouldNotSerializeCachedQuery extends \RuntimeException
{
    public static function becauseWeWereUnableToEncodeToJson(): self
    {
        return new self('Unable to serialize cached query to json.');
    }
}