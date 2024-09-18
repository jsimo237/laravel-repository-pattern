<?php

namespace Jsimo\LaravelRepositoryPattern;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
//use Illuminate\Database\Schema\Blueprint;

class ServiceProvider extends BaseServiceProvider {

    public function register(){
        $this->mergeConfigFrom(__DIR__.'/../config/repository-pattern.php', 'repository-pattern');
    }

    public function boot(){

        $this->registerConsoleCommands();

        if ($this->app->runningInConsole()) {
            $this->configurePublishing();
            $this->registerMigrationsMacros();
        }
    }

    private function configurePublishing(){
        $this->publishes([
            __DIR__.'/../config/repository-pattern.php' => config_path('repository-pattern.php'),
        ], 'config');
    }


    protected function registerConsoleCommands(): void{
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Commands\MakeRepositoryCommand::class,
           // Commands\MakeBasicRepositoryCommand::class,
          //  Commands\CreateModelCommand::class,
        ]);
    }

    private function registerMigrationsMacros(){
//        Blueprint::macro('addAuthorableColumns', function ($useBigInteger = false, $usersTableName = null) {
//            MigrationsMacros::addColumns($this, $useBigInteger, $usersTableName);
//        });
//
//        Blueprint::macro('dropAuthorableColumns', function () {
//            MigrationsMacros::dropColumns($this);
//        });
    }
}
