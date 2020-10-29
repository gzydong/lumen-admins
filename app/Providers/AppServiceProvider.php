<?php

namespace App\Providers;

use App\Services\Service;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * 设定所有的单例模式容器绑定的对应关系
     *
     * @var array
     */
    public $singletons = [
        // App 服务基类
        Service::class => Service::class,
    ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias(Service::class, 'services');
    }
}
