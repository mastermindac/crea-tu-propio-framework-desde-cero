<?php

namespace Lune\Validation;

use Lune\Validation\Rules\Email;
use Lune\Validation\Rules\Required;
use Lune\Validation\Rules\RequiredWith;
use Lune\Validation\Rules\ValidationRule;

class Rule {
    public static function email(): ValidationRule {
        return new Email();
    }

    public static function required(): ValidationRule {
        return new Required();
    }

    public static function requiredWith(string $withField): ValidationRule {
        return new RequiredWith($withField);
    }
}
