<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Importante para paginação
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Correção para bancos de dados mais antigos (MySQL 5.7 / MariaDB)
        Schema::defaultStringLength(191);
        
        // Forçar paginação com Tailwind (já que você está usando)
        Paginator::useTailwind(); 
    }
}