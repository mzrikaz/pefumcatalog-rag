<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Casts a pgvector column to/from a plain array of floats.
 *
 * @implements CastsAttributes<array<int, float>|null, array<int, float>|null>
 */
class AsVector implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying pgvector text representation.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<int, float>|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if ($value === null) {
            return null;
        }

        return json_decode((string) $value, true);
    }

    /**
     * Transform the attribute into the textual representation pgvector accepts.
     *
     * @param  array<int, float>|null  $value
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode($value);
    }
}
