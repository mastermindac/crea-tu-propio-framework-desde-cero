<?php

use Lune\Session\Session;

function session(): Session {
    return app()->session;
}
