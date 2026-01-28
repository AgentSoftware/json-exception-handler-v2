<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Traits;

use Illuminate\Contracts\Support\Arrayable;

trait NotNullArrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [];
        foreach (get_object_vars($this) as $attribute => $value) {
            if (! is_null($value)) {
                $array[$attribute] = $value instanceof Arrayable ? $value->toArray() : $value;
            }
        }

        return $array;
    }
}
