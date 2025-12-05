<?php

namespace App\DTO;

use Illuminate\Support\Collection;

class InvitationRsvpStatsDto
{
    public function __construct(
        public readonly int $total,        // всего ответов
        public readonly int $guestsTotal,  // суммарное количество гостей
        public readonly array $byStatus,   // статистика по статусам
    ) {
    }

    public static function fromCollection(Collection $rows): self
    {
        $total = $rows->count();
        $guestsTotal = (int) $rows->sum('guests_count');

        // Пример структуры:
        // 'by_status' => [
        //   'yes' => ['count' => 10, 'guests' => 25],
        //   'no'  => ['count' => 3,  'guests' => 3],
        //   ...
        // ]
        $byStatus = $rows
            ->groupBy('status')
            ->map(function (Collection $group) {
                return [
                    'count'  => $group->count(),
                    'guests' => (int) $group->sum('guests_count'),
                ];
            })
            ->toArray();

        return new self(
            total:       $total,
            guestsTotal: $guestsTotal,
            byStatus:    $byStatus,
        );
    }

    public function toArray(): array
    {
        return [
            'total'        => $this->total,
            'guests_total' => $this->guestsTotal,
            'by_status'    => $this->byStatus,
        ];
    }
}
