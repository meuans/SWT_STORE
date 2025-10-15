<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class MealDbClient
{
    private Client $httpClient;
    private string $baseUrl;

    public function __construct(?Client $client = null, ?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? ($_ENV['MEALDB_BASE_URL'] ?? 'https://www.themealdb.com/api/json/v1/1');
        $this->httpClient = $client ?? new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 10,
        ]);
    }

    /**
     * Fetch meals by area (cuisine), optionally filter by name substring on client side
     * API: /filter.php?a=Chinese returns idMeal, strMeal, strMealThumb
     *
     * @return array<int, array<string, string|null>>
     */
    public function listByArea(string $area): array
    {
        try {
            $response = $this->httpClient->get('/filter.php', [
                'query' => ['a' => $area],
                'http_errors' => false,
            ]);
        } catch (GuzzleException $e) {
            return ['error' => 'Upstream error: ' . $e->getMessage()];
        }

        $data = json_decode((string)$response->getBody(), true) ?: [];
        return $data['meals'] ?? [];
    }

    /**
     * Lookup full meal details by ID (for price/enrichment demo we don't need all)
     */
    public function lookupById(string $id): array
    {
        try {
            $response = $this->httpClient->get('/lookup.php', [
                'query' => ['i' => $id],
                'http_errors' => false,
            ]);
        } catch (GuzzleException $e) {
            return ['error' => 'Upstream error: ' . $e->getMessage()];
        }

        $data = json_decode((string)$response->getBody(), true) ?: [];
        return ($data['meals'][0] ?? []) ?: [];
    }
}


