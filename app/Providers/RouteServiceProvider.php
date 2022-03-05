<?php

namespace MMC\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'MMC\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapGeneralRoutes();
        $this->mapAdminRoutes();
        $this->mapOperatorRoutes();
        $this->mapReportRoutes();
        $this->mapExportRoutes();
        $this->mapAccountantRoutes();
        $this->mapApiRoutes();
    }

    protected function mapGeneralRoutes()
    {
        Route::group([
            'namespace' => $this->namespace
        ], function ($router) {
            require base_path('routes/general.php');
        });
    }

    protected function mapAdminRoutes()
    {
        Route::group([
            'middleware' => ['role:admin|administrator'],
            'namespace' => $this->namespace.'\Admin',
            'prefix' => '/admin'
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }

    protected function mapOperatorRoutes()
    {
        Route::group([
            'middleware' => ['role:admin|administrator|managertm|managertmsn|managerbg|cashier|accountant|chief.accountant|managermu|managermusn|business.manager|business.managerbg'],
            'namespace' => $this->namespace.'\Operator',
            'prefix' => '/operator'
        ], function ($router) {
            require base_path('routes/operator.php');
        });
    }

    protected function mapReportRoutes()
    {
        Route::group([
            'middleware' => ['role:admin|administrator|managermu|managermusn|managertm|managerbg|managertmsn|cashier|accountant|chief.accountant|business.manager|business.managerbg'],
            'namespace' => $this->namespace.'\Operator\Report',
            'prefix' => '/operator/report'
        ], function ($router) {
            require base_path('routes/report.php');
        });
    }

    protected function mapExportRoutes()
    {
        Route::group([
            'middleware' => ['role:admin|administrator|managermu|managermusn|managertm|managerbg|managertmsn|cashier|accountant|chief.accountant|business.manager|business.managerbg'],
            'namespace' => $this->namespace.'\Operator\Export',
            'prefix' => '/operator/export'
        ], function ($router) {
            require base_path('routes/export.php');
        });
    }

    protected function mapAccountantRoutes()
    {
        Route::group([
            'middleware' => ['role:managermu|managermusn|managertm|managertmsn|managerbg|administrator|accountant|chief.accountant|business.managerbg'],
            'namespace' => $this->namespace.'\Accountant',
            'prefix' => '/operator/accountant'
        ], function ($router) {
            require base_path('routes/accountant.php');
        });
    }

    protected function mapApiRoutes()
    {
        Route::group([
            'namespace' => $this->namespace.'\Api',
            'prefix' => '/api'
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
