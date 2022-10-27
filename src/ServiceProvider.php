<?php

namespace GhofurGiovany\Pipeline;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    public function boot()
    {
        if (class_exists('\Illuminate\Http\Client\Request')) {
            \Illuminate\Http\Client\Request::macro('pipe', function () {
                return Pipeline::make()->send($this);
            });
        }
    }
}
