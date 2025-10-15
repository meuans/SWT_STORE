<?php

declare(strict_types=1);

namespace App\Domain;

final class Item
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $thumbnailUrl,
        public readonly string $area,
        public readonly int $priceCents,
        public readonly ?string $category = null,
    ) {}

    /**
     * Transform MealDB record (filter endpoint) into store item with synthetic price
     */
    public static function fromMeal(array $meal, string $area): self
    {
        $id = (string)($meal['idMeal'] ?? '');
        $name = (string)($meal['strMeal'] ?? '');
        $thumb = $meal['strMealThumb'] ?? null;
        // Simple deterministic synthetic price based on ID
        $hash = crc32($id);
        $priceCents = (int)(500 + ($hash % 2500)); // 5.00 - 30.00
        return new self($id, $name, is_string($thumb) ? $thumb : null, $area, $priceCents);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnailUrl' => $this->thumbnailUrl,
            'area' => $this->area,
            'priceCents' => $this->priceCents,
            'price' => number_format($this->priceCents / 100, 2, '.', ''),
            'category' => $this->category,
        ];
    }
}


