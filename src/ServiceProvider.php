<?php

namespace GhofurGiovany\Pipeline;

use Illuminate\Http\Client\Request;
use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    public function boot()
    {
        Request::macro('pipe', function () {
            return Pipeline::make()->send($this);
        });
    }
}
