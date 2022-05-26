<?php

declare(strict_types=1);

namespace app\components;

class Grade
{
    public string $letter;
    public float $equivalent;
    public string $range;

    public function getData(): array
    {
        return [
            'letter' => $this->letter,
            'equivalent' => $this->equivalent,
            'range' => $this->range
        ];
    }

    public function inRange(int $mark): bool
    {
        list($begin, $end) = array_map('intval', explode('-', $this->range));

        return $begin <= $mark && $mark <= $end;
    }
}