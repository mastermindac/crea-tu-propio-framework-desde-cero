<?php

namespace App\Providers;

use Lune\Providers\ServiceProvider;
use Lune\Validation\Rule;

class RuleServiceProvider implements ServiceProvider {
    public function registerServices() {
        Rule::loadDefaultRules();
    }
}
