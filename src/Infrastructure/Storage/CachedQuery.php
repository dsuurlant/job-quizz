<?php
declare(strict_types=1);

namespace App\Infrastructure\Storage;


final class CachedQuery
{
    private string $query;
    private array $params;

    private function __construct(string $query, array $params)
    {
        $this->query = $query;
        $this->params = $params;
    }

    public static function create(string $query, array $params): self
    {
        return new CachedQuery($query, $params);
    }

    public function serialize(): string
    {
        try {
            return json_encode([
                'query' => $this->query,
                'params' => $this->params
            ], JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw SorryCouldNotSerializeCachedQuery::becauseWeWereUnableToEncodeToJson();
        }
    }
}