<?php

namespace App\Rules\ValidatePhone;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        $pattern = '/^\+?[0-9-\s]+$/';

        return preg_match($pattern, $value);
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return 'Wrong phone format';
    }
}
