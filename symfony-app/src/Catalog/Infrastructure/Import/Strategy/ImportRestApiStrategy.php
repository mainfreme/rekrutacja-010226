<?php

namespace App\Catalog\Infrastructure\Import\Strategy;

use App\Catalog\Domain\Exception\ExternalServiceUnavailableException;
use App\Catalog\Domain\Service\Import\ImportStrategyInterface;
use App\Catalog\Domain\Exception\NotFoundUserTokenException;
use App\Identity\Domain\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Catalog\Domain\Exception\AccessDeniedException;

class ImportRestApiStrategy implements ImportStrategyInterface
{
    private string $baseUri;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        #[Autowire(env: 'PHOTO_API_URL')] string $baseUri
    ) {
        $this->baseUri = $baseUri;
    }

    /**
     * @param User $user
     * @return array
     * @throws AccessDeniedException
     * @throws NotFoundUserTokenException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function import(User $user): array
    {
        if (NULL === $user->getToken()) {
            throw new NotFoundUserTokenException();
        }

        try {
            $response = $this->httpClient->request('GET', $this->baseUri, [
                'headers' => [
                    'Host' => 'http://phoenix',
                    'Accept' => 'application/json',
                    'access-token' => $user->getToken(),
                ],
            ]);
        } catch (TransportExceptionInterface $e) {
            throw new ExternalServiceUnavailableException('PhotosAPI', $e);
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode === 403 || $statusCode === 401) {
            throw new AccessDeniedException('API odrzuciło Twój token.');
        }

        if ($statusCode !== 200) {
            throw new \RuntimeException('Serwis zewnętrzny zwrócił błąd: ' . $statusCode);
        }

        return $response->toArray();
    }

    public function supports(string $source): bool
    {
        return $source === 'PhoenixApi_json_api';
    }
}
