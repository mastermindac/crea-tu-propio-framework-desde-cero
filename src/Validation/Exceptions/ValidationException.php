<?php

namespace Lune\Validation\Exceptions;

use Lune\Exceptions\LuneException;

class ValidationException extends LuneException {
    public function __construct(protected array $errors) {
        $this->errors = $errors;
    }

    public function errors(): array {
        return $this->errors;
    }
}
