<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Item;
use App\Http\Request;
use App\Http\Response;
use App\Services\MealDbClient;

final class ItemController
{
    public function __construct(private readonly MealDbClient $mealDb)
    {
    }

    public function list(Request $request): Response
    {
        $area = (string)($request->query['area'] ?? ($_ENV['DEFAULT_AREA'] ?? 'Chinese'));
        $q = isset($request->query['q']) ? trim((string)$request->query['q']) : '';
        $min = isset($request->query['minPrice']) ? (int)$request->query['minPrice'] : null;
        $max = isset($request->query['maxPrice']) ? (int)$request->query['maxPrice'] : null;

        $meals = $this->mealDb->listByArea($area);
        if (isset($meals['error'])) {
            return Response::json(['error' => $meals['error']], 502);
        }

        $items = array_map(fn($m) => Item::fromMeal($m, $area)->toArray(), $meals);

        if ($q !== '') {
            $items = array_values(array_filter($items, function (array $item) use ($q): bool {
                return stripos($item['name'], $q) !== false;
            }));
        }

        if ($min !== null) {
            $items = array_values(array_filter($items, fn($i) => $i['priceCents'] >= $min));
        }
        if ($max !== null) {
            $items = array_values(array_filter($items, fn($i) => $i['priceCents'] <= $max));
        }

        // Simple pagination
        $page = max(1, (int)($request->query['page'] ?? 1));
        $perPage = min(50, max(1, (int)($request->query['perPage'] ?? 12)));
        $total = count($items);
        $items = array_slice($items, ($page - 1) * $perPage, $perPage);

        return Response::json([
            'area' => $area,
            'q' => $q,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'items' => $items,
        ]);
    }
}


