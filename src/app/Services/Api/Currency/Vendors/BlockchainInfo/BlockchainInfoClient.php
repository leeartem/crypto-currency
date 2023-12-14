<?php

namespace App\Services\Api\Currency\Vendors\BlockchainInfo;

use App\Services\Api\Currency\Vendors\AbstractCurrencyClient;
use App\Services\Api\Currency\Vendors\BlockchainInfo\Exceptions\ApiBadResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class BlockchainInfoClient extends AbstractCurrencyClient
{
    public function __construct(
        private Client $client,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @return array
     * @throws ApiBadResponse
     * @throws GuzzleException
     */
    public function getRates(): array
    {
        $response = $this->client->get(
            '/ticker'
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $this->logger->error(
                'Bad response from blockchain.info',
                [
                    'code' => $response->getStatusCode(),
                    'content' => $response->getBody()->getContents()
                ]
            );

            throw new ApiBadResponse('Bad response from blockchain.info exceptions');
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
