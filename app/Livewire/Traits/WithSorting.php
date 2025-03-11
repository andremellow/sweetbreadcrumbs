<?php

namespace App\Livewire\Traits;

use App\Enums\SortDirection;
use Livewire\Attributes\Url;

trait WithSorting
{
    #[Url()]
    public $sortBy = '';

    #[Url()]
    public SortDirection $sortDirection = SortDirection::ASC;

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === SortDirection::ASC ? SortDirection::DESC : SortDirection::ASC;
        } else {
            $this->sortBy = $column;
            $this->sortDirection = SortDirection::ASC;
        }
    }
}
