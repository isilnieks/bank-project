<?php

namespace App\Tests\Service;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use App\Service\ExchangeRateService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class ExchangeRateServiceTest extends TestCase
{
    private $httpClient;
    private $cache;
    private $apiKey;
    private $apiUrl;
    private $service;

    protected function setUp(): void
    {
        $this->httpClient = HttpClient::create();
        $this->cache = new ArrayAdapter();
        $this->apiKey = '91a6a93ff4f744ad85ecca15e6c9939e';
        $this->apiUrl = 'https://api.currencyfreaks.com/v2.0/rates/latest';
        $this->service = new ExchangeRateService(
            $this->httpClient,
            $this->cache,
            $this->apiKey,
            $this->apiUrl
        );
    }

    public function testGetExchangeRate()
    {
        $result = $this->service->getExchangeRate('USD', 'EUR');
        $this->assertIsFloat($result);
    }

    public function testNormalizeRates()
    {
        $reflectionClass = new ReflectionClass(ExchangeRateService::class);
        $method = $reflectionClass->getMethod('normalizeRates');
        $method->setAccessible(true);

        $sampleRates = [
            'eur' => '0.85',
            'gbp' => '0.75',
            'jpy' => 110
        ];

        $normalized = $method->invoke($this->service, $sampleRates);

        $this->assertEquals(0.85, $normalized['EUR']);
        $this->assertEquals(0.75, $normalized['GBP']);
        $this->assertEquals(110.0, $normalized['JPY']);
    }

    public function testFetchAndCacheRates()
    {
        $reflectionClass = new ReflectionClass(ExchangeRateService::class);
        $method = $reflectionClass->getMethod('fetchAndCacheRates');
        $method->setAccessible(true);

        $cacheItem = $this->cache->getItem('exchange_rates');
        $rates = $method->invoke($this->service, $cacheItem);

        $this->assertIsArray($rates);
        $this->assertNotEmpty($rates);
    }
}
