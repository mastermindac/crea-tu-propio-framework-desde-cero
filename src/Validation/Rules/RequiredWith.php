<?php

namespace Lune\Validation\Rules;

class RequiredWith implements ValidationRule {
    protected string $withField;

    public function __construct(string $withField) {
        $this->withField = $withField;
    }

    public function message(): string {
        return "This field is required when {$this->withField} is present";
    }

    public function isValid(string $field, array $data): bool {
        if (isset($data[$this->withField]) && $data[$this->withField] != "") {
            return isset($data[$field]) && $data[$field] != "";
        }

        return true;
    }
}
