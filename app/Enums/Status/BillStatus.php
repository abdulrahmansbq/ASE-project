<?php

namespace App\Enums\Status;

enum BillStatus: int
{
    case UNPAID = 1;

    case PAID = 2;

    case PARTIALLY_PAID = 3;

    case CANCELLED = 4;

    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    public function isUnpaid(): bool
    {
        return $this === self::UNPAID;
    }

    public function isPartiallyPaid(): bool
    {
        return $this === self::PARTIALLY_PAID;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function name(): string
    {
        return match ($this) {
            self::UNPAID => 'Unpaid',
            self::PAID => 'Paid',
            self::PARTIALLY_PAID => 'Partially Paid',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNPAID => 'danger',
            self::PAID => 'success',
            self::PARTIALLY_PAID => 'warning',
            self::CANCELLED => 'gray',
        };
    }

    public static function getOptions(): array
    {
        return [
            self::UNPAID->value => self::UNPAID->name(),
            self::PAID->value => self::PAID->name(),
            self::PARTIALLY_PAID->value => self::PARTIALLY_PAID->name(),
            self::CANCELLED->value => self::CANCELLED->name(),
        ];
    }
}
