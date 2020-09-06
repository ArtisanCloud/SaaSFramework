<?php

namespace ArtisanCloud\SaaSFramework\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotExists implements Rule
{

    public $table = '';
    public $column = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column)
    {
//        dump($table, $column);
        $this->table = $table;
        $this->column = $column;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
//        dd($attribute, $value);
        $bResult = \DB::table($this->table)->where($this->column, $value)->exists();
//        dd($bResult);

        return !$bResult;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Note exist in .';
    }
}
