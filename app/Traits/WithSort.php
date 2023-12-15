<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait WithSort
{
    public function validateSort(array $columns)
    {
        request()->validate([
            'column' => ['in:'. Arr::join($columns, ',')],
            'direction' => ['in:ASC,DESC,PAID,PARTIALLY PAID,UNPAID'],
            'archived' => ['in:true,false']
        ]);
    }

    public function sortByColumns(array $lookUpTable, array $except, $query)
    {
        $this->withArchived($query);

        if (request()->has(['column', 'direction'])) {
            $column = $lookUpTable[request('column')] ?? request('column');

            if (!in_array($column, $except))
                $query->orderBy($column, request('direction'));

            return $column;
        }
    }

    private function withArchived($query)
    {
        if (request()->has('archived'))
            $query->withTrashed();
    }

    public function appendAttributes(array $attributes, $query)
    {
        $items = $query->get();
        $items->each(function ($item) use ($attributes) {
            $item->append($attributes);
        });
        return $items;
    }

    public function sortByAttributes($items, $column = null)
    {
        if (request()->has(['column', 'direction'])) {
            if (is_null($column)) $column = request('column');
            return $items->sortBy(function ($invoice) use ($column) {
                if ($column == 'payment_status') {
                    return $invoice->$column == request('direction') ? -1 : 1;
                }
                else
                    return $invoice->$column;
            }, SORT_REGULAR, request('direction') === 'DESC' ? true : false);
        }

        return $items;
    }
}