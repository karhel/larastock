<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueCompositeKeys implements Rule
{
    private $column;
    private $table;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column)
    {
        $this->table    = $table;
        $this->column   = $column;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !\DB::table($this->table)
            ->where($attribute, $value)
            ->where($this->column, request($this->column))
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique_composite_keys', [
            'column' => $this->column
        ]);
    }
}
