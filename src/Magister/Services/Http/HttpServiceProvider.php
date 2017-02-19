<?php

namespace Magister\Services\Http;

use GuzzleHttp\Client;
use Magister\Services\Support\ServiceProvider;
use GuzzleHttp\Subscriber\Cache\CacheSubscriber;

/**
 * Class HttpServiceProvider.
 */
class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGuzzle();
    }

    /**
     * Register the Guzzle driver.
     *
     * @return void
     */
    protected function registerGuzzle()
    {
        $this->app->singleton('http', function ($app) {
            $client = new Client(['base_url' => "https://{$app['school']}.magister.net/api/"]);

            $client->setDefaultOption('exceptions', false);

            $client->setDefaultOption('headers', [
                'X-API-Client-ID' => function_exists('env') ? env('MAGISTER_API_KEY', '12D8') : '12D8',
            ]);

            $client->setDefaultOption('cookies', new SessionCookieJar($app['cookie']));

            CacheSubscriber::attach($client);

            return $client;
        });
    }
}
