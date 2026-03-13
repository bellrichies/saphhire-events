<?php

namespace App\Core;

class MachineTranslator
{
    private string $defaultLanguage;
    private bool $enabled;
    private string $apiKey;
    private string $cacheDirectory;

    public function __construct()
    {
        $config = require CONFIG_PATH . '/languages.php';
        $this->defaultLanguage = (string) ($config['default'] ?? 'en');
        $googleConfig = $config['google_translate'] ?? [];
        $this->enabled = (bool) ($googleConfig['enabled'] ?? false);
        $this->apiKey = trim((string) ($googleConfig['api_key'] ?? ''));
        $this->cacheDirectory = STORAGE_PATH . '/translations-cache';
    }

    public function shouldTranslate(string $targetLanguage): bool
    {
        return $targetLanguage !== '' && $targetLanguage !== $this->defaultLanguage;
    }

    public function isAvailable(): bool
    {
        return $this->enabled && $this->apiKey !== '';
    }

    /**
     * @return array{deleted:int, failed:int}
     */
    public function clearCache(?string $language = null): array
    {
        $deleted = 0;
        $failed = 0;

        if (!is_dir($this->cacheDirectory)) {
            return ['deleted' => 0, 'failed' => 0];
        }

        if ($language !== null && trim($language) !== '') {
            $target = $this->cacheDirectory . '/' . strtolower(trim($language)) . '.json';
            if (is_file($target)) {
                if (@unlink($target)) {
                    $deleted++;
                } else {
                    $failed++;
                }
            }

            return ['deleted' => $deleted, 'failed' => $failed];
        }

        $files = glob($this->cacheDirectory . '/*.json') ?: [];
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            if (@unlink($file)) {
                $deleted++;
            } else {
                $failed++;
            }
        }

        return ['deleted' => $deleted, 'failed' => $failed];
    }

    /**
     * @param array<int, string> $texts
     * @return array<string, string>
     */
    public function translateBatch(array $texts, string $targetLanguage, string $sourceLanguage = ''): array
    {
        $targetLanguage = strtolower(trim($targetLanguage));
        $sourceLanguage = strtolower(trim($sourceLanguage));

        $normalized = [];
        foreach ($texts as $text) {
            $text = (string) $text;
            if (trim($text) === '') {
                continue;
            }
            $normalized[$text] = $text;
        }

        if (!$this->shouldTranslate($targetLanguage) || empty($normalized)) {
            return $normalized;
        }

        $cache = $this->loadLanguageCache($targetLanguage);
        $result = [];
        $missing = [];

        foreach ($normalized as $original) {
            $key = $this->cacheKey($original);
            if (
                isset($cache[$key])
                && is_string($cache[$key])
                && trim($cache[$key]) !== ''
            ) {
                $result[$original] = $cache[$key];
                continue;
            }
            $missing[] = $original;
        }

        if (!empty($missing) && $this->isAvailable()) {
            $translated = $this->translateMissing($missing, $targetLanguage, $sourceLanguage);
            foreach ($translated as $original => $value) {
                $result[$original] = $value;
                $cache[$this->cacheKey($original)] = $value;
            }
            $this->saveLanguageCache($targetLanguage, $cache);
        }

        foreach ($missing as $original) {
            if (!isset($result[$original])) {
                $result[$original] = $original;
            }
        }

        return $result;
    }

    private function cacheKey(string $text): string
    {
        return hash('sha256', $text);
    }

    /**
     * @return array<string, string>
     */
    private function loadLanguageCache(string $language): array
    {
        if (!is_dir($this->cacheDirectory) && !mkdir($this->cacheDirectory, 0775, true) && !is_dir($this->cacheDirectory)) {
            return [];
        }

        $path = $this->cacheDirectory . '/' . $language . '.json';
        if (!file_exists($path)) {
            return [];
        }

        $raw = file_get_contents($path);
        if ($raw === false || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param array<string, string> $cache
     */
    private function saveLanguageCache(string $language, array $cache): void
    {
        if (!is_dir($this->cacheDirectory) && !mkdir($this->cacheDirectory, 0775, true) && !is_dir($this->cacheDirectory)) {
            return;
        }

        $path = $this->cacheDirectory . '/' . $language . '.json';
        $encoded = json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            return;
        }

        file_put_contents($path, $encoded, LOCK_EX);
    }

    /**
     * @param array<int, string> $missing
     * @return array<string, string>
     */
    private function translateMissing(array $missing, string $targetLanguage, string $sourceLanguage): array
    {
        $result = [];
        $chunks = array_chunk($missing, 64);

        foreach ($chunks as $chunk) {
            $payload = [
                'q' => array_values($chunk),
                'target' => $targetLanguage,
                'format' => 'text',
            ];
            if ($sourceLanguage !== '') {
                $payload['source'] = $sourceLanguage;
            }

            $response = $this->requestGoogleTranslation($payload);
            if ($response === null) {
                continue;
            }

            $translations = $response['data']['translations'] ?? null;
            if (!is_array($translations)) {
                continue;
            }

            foreach ($chunk as $index => $original) {
                $translated = $translations[$index]['translatedText'] ?? null;
                if (!is_string($translated) || $translated === '') {
                    continue;
                }
                $result[$original] = html_entity_decode($translated, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>|null
     */
    private function requestGoogleTranslation(array $payload): ?array
    {
        $endpoint = 'https://translation.googleapis.com/language/translate/v2?key=' . rawurlencode($this->apiKey);
        $body = http_build_query($payload);

        if (function_exists('curl_init')) {
            $ch = curl_init($endpoint);
            if ($ch === false) {
                return null;
            }

            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            ]);

            $raw = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!is_string($raw) || $raw === '' || $status >= 400) {
                return null;
            }

            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : null;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $body,
                'timeout' => 20,
            ],
        ]);

        $raw = @file_get_contents($endpoint, false, $context);
        if (!is_string($raw) || $raw === '') {
            return null;
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }
}
