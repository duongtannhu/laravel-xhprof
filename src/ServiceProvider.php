<?php

namespace NhuDuong\Xhprof;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $configPath = __DIR__ . '/../config/xhprof.php';
        $this->mergeConfigFrom($configPath, 'xhprof');
    }
    
    public function boot()
    {
        $configPath = __DIR__ . '/../config/xhprof.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');
        
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware(Xhprof::class);
    }
    
    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('xhprof.php');
    }

}