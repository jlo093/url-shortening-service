<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class UrlShorteningService
{
    protected string $cacheKey = 'url:%s';

    public function encodeUrl(string $url): string
    {
        // Purposefully include uniqid to assure different code even for same URL sent again
        $shortUrlCode = substr(md5($url . uniqid()), 0, 8);

        Redis::set(
            sprintf($this->cacheKey, $shortUrlCode),
            $url
        );

        return $shortUrlCode;
    }

    public function decodeUrl(string $url): ?string
    {
        $code = $this->extractCodeFromUrl($url);
        if (!$code) {
            return null;
        }

        return Redis::get(
            sprintf($this->cacheKey, $code)
        ) ?? null;
    }

    public function extractCodeFromUrl(string $url): ?string
    {
        $urlParts = parse_url($url);
        if (!empty($urlParts['path'])) {
            return str_replace('/', '', $urlParts['path']);
        }
        return null;
    }
}
