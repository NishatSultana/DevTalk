<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Question;

class RouteServiceProvider extends ServiceProvider
{

    protected $namespace = 'App\Http\Controllers';


    public const HOME = '/home';


    public function boot()
    {
        Route::bind('slug', function ($slug){
            return Question::with('answers.user')->where('slug', $slug)->first() ?? abort(404);
        });

        parent::boot();
    }


    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }


    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }


    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
