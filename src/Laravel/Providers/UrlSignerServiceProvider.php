<?php
/**
 * Created by PhpStorm.
 * User: s.bahador
 * Date: 10/24/2019
 * Time: 5:22 PM
 */

namespace SaliBhdr\UrlSigner\Laravel\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use SaliBhdr\UrlSigner\Laravel\Commands\SignerKeyGenerate;
use SaliBhdr\UrlSigner\Laravel\LaravelUrlSigner;
use SaliBhdr\UrlSigner\UrlSigner;
use Laravel\Lumen\Application as LumenApplication;

class UrlSignerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;


    /**
     * Register the service provider.
     * @throws \SaliBhdr\UrlSigner\Exceptions\SignerNotFoundException
     */
    public function register()
    {
        $this->setupConfig();

        $this->commands([
            SignerKeyGenerate::class,
        ]);

        $urlSigner = new LaravelUrlSigner();

        $this->app->singleton(UrlSigner::class, function ($app) use ($urlSigner){
            return $urlSigner->getUrlSigner();
        });

        $this->app->alias(UrlSigner::class, 'typhoonUrlSigner');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['typhoonUrlSigner', UrlSigner::class];
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $this->addConfig();

        $this->mergeConfigFrom($this->getConfigFile(), 'urlSigner');
    }

    /**
     * published config file
     */
    protected function addConfig()
    {
        if($this->app instanceof LumenApplication){
            $this->app->configure('urlSigner');
        }else{
            $this->publishes([$this->getConfigFile() => config_path('urlSigner.php')],'typhoonUrlSigner');
        }
    }

    /**
     * gets config file
     *
     * @return string
     */
    protected function getConfigFile()
    {
        return __DIR__ . '/../config/urlSigner.php';
    }
}