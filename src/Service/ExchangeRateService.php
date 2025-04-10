<?php

namespace App\Service;

use Throwable;
use RuntimeException;
use UnexpectedValueException;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ExchangeRateService
{
    private const CACHE_KEY = 'exchange_rates';
    private const CACHE_TTL = 3600;
    private const BASE_CURRENCY = 'USD';

    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheItemPoolInterface $cache,
        private string $apiKey,
        private string $apiUrl
    ) {}

    public function getExchangeRate(string $fromCurrency, string $toCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        $rates = $this->getRates();

        if ($fromCurrency === self::BASE_CURRENCY) {
            return $rates[$toCurrency] ?? throw new UnexpectedValueException("Missing exchange rate for {$toCurrency}.");
        }

        if ($toCurrency === self::BASE_CURRENCY) {
            $fromRate = $rates[$fromCurrency] ?? throw new UnexpectedValueException("Missing exchange rate for {$fromCurrency}.");
            return 1 / $fromRate;
        }

        $fromRate = $rates[$fromCurrency] ?? throw new UnexpectedValueException("Missing exchange rate for {$fromCurrency}.");
        $toRate = $rates[$toCurrency] ?? throw new UnexpectedValueException("Missing exchange rate for {$toCurrency}.");

        return $toRate / $fromRate;
    }

    protected function getRates(): array
    {
        $cacheItem = $this->cache->getItem(self::CACHE_KEY);

        return $cacheItem->isHit()
            ? $cacheItem->get()
            : $this->fetchAndCacheRates($cacheItem);
    }

    protected function fetchAndCacheRates(CacheItemInterface $cacheItem): array
    {
        try {
            $rates = $this->fetchRatesFromApi();
            $normalizedRates = $this->normalizeRates($rates);

            $cacheItem
                ->set($normalizedRates)
                ->expiresAfter(self::CACHE_TTL);

            $this->cache->save($cacheItem);

            return $normalizedRates;
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to fetch exchange rates.', $e->getCode(), $e);
        }
    }

    protected function fetchRatesFromApi(): array
    {
        $response = $this->httpClient->request('GET', $this->apiUrl, [
            'query' => [
                'apikey' => $this->apiKey,
                'base' => self::BASE_CURRENCY,
            ],
        ]);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new RuntimeException();
        }

        $data = $response->toArray();
        return $data['rates'] ?? [];
    }

    protected function normalizeRates(array $rates): array
    {
        $normalizedRates = [];
        foreach ($rates as $currency => $rate) {
            $normalizedRates[strtoupper($currency)] = (float) $rate;
        }
        return $normalizedRates;
    }
}
