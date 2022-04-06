<?php


namespace App\Services\Filters\QueryFiltersClasses;


use App\Services\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class WherePhoneIsValid extends IFilter
{
    private $value;

    public function __construct($ask_check, $value = null)
    {
        $this->value = $value ?? $ask_check;
        parent::__construct("", $ask_check);
    }

    /**
     * @inheritDoc
     */
    public function applyFilter($builder)
    {

        if ($this->value == "yes") {
            return $builder->whereRaw("REGEXP(countries.regex,phones.phone) = 1");
        }

        elseif ($this->value == "no")
            return $builder->whereRaw("REGEXP(countries.regex,phones.phone) = 0");

        return $builder;

    }
}
