<?php

use GhofurGiovany\Pipeline\Pipeline;

if (!function_exists('pipe')) {
    function pipe(mixed $passable)
    {
        return Pipeline::make()->send($passable);
    }
}
