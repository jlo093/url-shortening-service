<?php

namespace Tests\Unit;

use App\Services\UrlShorteningService;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class UrlShorteningServiceTest extends TestCase
{
    const TEST_URL = 'https://www.atarim.io/i-am-a-test-url?foo=bar#anchor';

    public function testUrlCodeExtraction()
    {
        $urlShorteningService = new UrlShorteningService();

        $this->assertEquals('foobar', $urlShorteningService->extractCodeFromUrl('http://localhost/foobar'));
        $this->assertEquals('foobar', $urlShorteningService->extractCodeFromUrl('http://localhost/foobar?param'));
        $this->assertEquals('foobar', $urlShorteningService->extractCodeFromUrl('http://localhost/foobar?param#anchor'));
        $this->assertEquals('foobar', $urlShorteningService->extractCodeFromUrl('foobar'));
        $this->assertEquals(null, $urlShorteningService->extractCodeFromUrl('http://localhost'));

        // Acceptable / does not break code, returns 'invalid or expired code' as it should
        $this->assertEquals('foobarxyz', $urlShorteningService->extractCodeFromUrl('http://localhost/foobar/xyz'));
    }

    public function testSuccessfulEncodeUrl()
    {
        Redis::shouldReceive('set')->once();

        $response = $this->postJson('/api/encode', ['url' => 'https://www.atarim.io/i-am-a-test-url?foo=bar#anchor']);

        $response->assertStatus(200)
            ->assertJsonStructure(['short_url']);
    }

    public function testIncompleteEncodeUrl()
    {
        $response = $this->postJson('/api/encode');

        $response->assertStatus(422)
            ->assertJsonStructure(['message']);
    }

    public function testDecodeUrl()
    {
        Redis::shouldReceive('set')->once();

        $response = $this->postJson('/api/encode', ['url' => self::TEST_URL]);

        $response->assertStatus(200)
            ->assertJsonStructure(['short_url']);

        $shortUrlCode = (new UrlShorteningService())->extractCodeFromUrl($response->json('short_url'));

        Redis::shouldReceive('get')->with("url:" . $shortUrlCode)->andReturn(self::TEST_URL);

        $response = $this->postJson('/api/decode/', ['url' => $response->json('short_url')]);

        $response->assertStatus(200)
            ->assertJson(['original_url' => self::TEST_URL]);
    }

    public function testDecodeUrlNotFound()
    {
        Redis::shouldReceive('get')->with("url:foobar")->andReturn(null);

        $response = $this->postJson('/api/decode', ['url' => 'http://localhost/foobar']);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Invalid or expired URL code']);
    }
}
