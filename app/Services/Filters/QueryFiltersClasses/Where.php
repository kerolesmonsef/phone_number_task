<?php


namespace App\Services\Filters\QueryFiltersClasses;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class Where extends IFilter
{
    private string $operation;
    protected string $boolean;
    protected $value_or_closure;


    public function __construct(string $column, $ask_check, string $operation = '=', $value_or_closure = null, string $boolean = "AND")
    {
        parent::__construct($column, $ask_check);
        $this->operation = $operation;
        $this->boolean = $boolean;
        $this->value_or_closure = $value_or_closure ?? $ask_check;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function applyFilter($builder)
    {
        $funk = $this->value_or_closure;
        if (is_callable($funk)) {
            $value = $funk();
        } else {
            $value = $funk;
        }

        return $builder->where($this->column, $this->operation, $value, $this->boolean);
    }
}
