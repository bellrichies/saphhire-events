<?php

namespace App\Core;

class LanguageManager
{
    private $currentLanguage;
    private $supportedLanguages;
    private $translations = [];
    private $defaultLanguage;

    public function __construct()
    {
        $config = require CONFIG_PATH . '/languages.php';
        $this->supportedLanguages = $config['supported'];
        $this->defaultLanguage = $config['default'];
        $this->currentLanguage = $this->detectLanguage();
    }

    /**
     * Detect the current language from session, cookie, or default
     */
    private function detectLanguage()
    {
        // URL parameter should always win for explicit user selection
        if (isset($_GET['lang'])) {
            $lang = strtolower(trim((string) $_GET['lang']));
            if ($this->isSupported($lang)) {
                $this->setLanguage($lang);
                return $lang;
            }
        }

        // Check session first
        if (isset($_SESSION['language']) && $this->isSupported($_SESSION['language'])) {
            return $_SESSION['language'];
        }

        // Check cookie
        if (isset($_COOKIE['language']) && $this->isSupported($_COOKIE['language'])) {
            return $_COOKIE['language'];
        }

        // Check browser language
        $browserLang = $this->getBrowserLanguage();
        if ($browserLang && $this->isSupported($browserLang)) {
            return $browserLang;
        }

        return $this->defaultLanguage;
    }

    /**
     * Get browser language from Accept-Language header
     */
    private function getBrowserLanguage()
    {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return null;
        }

        $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($languages as $lang) {
            $lang = trim(explode(';', $lang)[0]);
            $langCode = explode('-', $lang)[0];
            if ($this->isSupported($langCode)) {
                return $langCode;
            }
        }

        return null;
    }

    /**
     * Check if a language is supported
     */
    public function isSupported($language)
    {
        return isset($this->supportedLanguages[$language]);
    }

    /**
     * Set the current language
     */
    public function setLanguage($language)
    {
        $language = strtolower(trim((string) $language));

        if ($this->isSupported($language)) {
            $this->currentLanguage = $language;
            $_SESSION['language'] = $language;
            setcookie('language', $language, [
                'expires' => time() + (365 * 24 * 60 * 60),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            return true;
        }
        return false;
    }

    /**
     * Get the current language code
     */
    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }

    /**
     * Get all supported languages
     */
    public function getSupportedLanguages()
    {
        return $this->supportedLanguages;
    }

    /**
     * Get language metadata
     */
    public function getLanguageInfo($language = null)
    {
        $language = $language ?? $this->currentLanguage;
        return $this->supportedLanguages[$language] ?? null;
    }

    /**
     * Load translations for the current language
     */
    public function loadTranslations($file)
    {
        $filePath = CONFIG_PATH . '/translations/' . $this->currentLanguage . '/' . $file . '.php';
        
        if (file_exists($filePath)) {
            $this->translations[$file] = require $filePath;
        } else {
            // Fallback to default language
            $defaultPath = CONFIG_PATH . '/translations/' . $this->defaultLanguage . '/' . $file . '.php';
            if (file_exists($defaultPath)) {
                $this->translations[$file] = require $defaultPath;
            } else {
                $this->translations[$file] = [];
            }
        }

        return $this->translations[$file];
    }

    /**
     * Get a translation string
     */
    public function get($file, $key, $default = '')
    {
        if (!isset($this->translations[$file])) {
            $this->loadTranslations($file);
        }

        $keys = explode('.', $key);
        $value = $this->translations[$file];

        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default ?: $key;
            }
        }

        return $value;
    }

    /**
     * Get language code for Google Translate
     */
    public function getGoogleTranslateCode($language = null)
    {
        $language = $language ?? $this->currentLanguage;
        $info = $this->getLanguageInfo($language);
        return $info['iso'] ?? $language;
    }
}
