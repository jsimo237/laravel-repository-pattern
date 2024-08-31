<?php

namespace Jsimo237\LaravelRepositoryPattern\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class LaravelRepositoryPatternFacade extends IlluminateFacade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'SpxModel';
    }


}
