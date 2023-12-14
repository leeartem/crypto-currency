<?php

namespace App\Providers;

use App\Services\Api\Currency\Vendors\BlockchainInfo\BlockchainInfoClient;
use GuzzleHttp\Client;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CurrencyServicesProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function register(): void
    {
        $this->app->when(BlockchainInfoClient::class)
            ->needs(Client::class)
            ->give(function () {
                return new Client(
                    [
                        'base_uri'        => config('clients.blockchain_info.base_uri'),
                        'timeout'         => config('clients.blockchain_info.timeout'),
                        'connect_timeout' => config('clients.blockchain_info.connect_timeout'),
                        'http_errors'     => false,
                        'headers'         => [
                            'Content-type' => 'application/json',
                            'Accept'       => 'application/json',
                        ],
                    ]
                );
            });

//        $this->app->when(BlockchainInfoClient::class)
//            ->needs(Client::class)
//            ->give(function ($app) {
//                return $app->make(
//                    Client::class,
//                    [
//                        'config' => [
//                            'base_uri'        => config('clients.blockchain_info.base_uri'),
//                            'timeout'         => config('clients.blockchain_info.timeout'),
//                            'connect_timeout' => config('clients.blockchain_info.connect_timeout'),
//                            'http_errors'     => false,
//                            'headers'         => [
//                                'Content-type' => 'application/json',
//                                'Accept'       => 'application/json',
//                            ],
//                        ]
//                    ]);
//            });
    }
    public function provides()
    {
        return [
            BlockchainInfoClient::class,
        ];
    }

}
