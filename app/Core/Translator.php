<?php

namespace App\Core;

class Translator
{
    private static $instance;
    private $languageManager;

    private function __construct()
    {
        $this->languageManager = new LanguageManager();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Translate a string
     * Usage: trans('messages.welcome') or trans('messages.greeting', 'default value')
     */
    public function translate($key, $default = '')
    {
        [$file, $key] = $this->parseKey($key);
        return $this->languageManager->get($file, $key, $default);
    }

    /**
     * Parse translation key (file.key format)
     */
    private function parseKey($key)
    {
        if (strpos($key, '.') === false) {
            return ['main', $key];
        }

        $parts = explode('.', $key, 2);
        return $parts;
    }

    /**
     * Get current language
     */
    public function getCurrentLanguage()
    {
        return $this->languageManager->getCurrentLanguage();
    }

    /**
     * Set language
     */
    public function setLanguage($language)
    {
        return $this->languageManager->setLanguage($language);
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages()
    {
        return $this->languageManager->getSupportedLanguages();
    }

    /**
     * Get language info
     */
    public function getLanguageInfo($language = null)
    {
        return $this->languageManager->getLanguageInfo($language);
    }
}
