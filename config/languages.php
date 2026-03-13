<?php

/**
 * Language Configuration
 * Supported languages and their metadata
 */

return [
    'default' => 'en',
    'fallback' => 'en',
    
    'supported' => [
        'en' => [
            'name' => 'English',
            'iso' => 'en',
            'flag' => '🇬🇧',
            'native' => 'English',
        ],
        'et' => [
            'name' => 'Estonian',
            'iso' => 'et',
            'flag' => '🇪🇪',
            'native' => 'Eesti',
        ],
        'fi' => [
            'name' => 'Finnish',
            'iso' => 'fi',
            'flag' => '🇫🇮',
            'native' => 'Suomi',
        ],
        'ru' => [
            'name' => 'Russian',
            'iso' => 'ru',
            'flag' => '🇷🇺',
            'native' => 'Русский',
        ],
    ],

    // Google Translate configuration (optional, for server-side translation)
    'google_translate' => [
        'enabled' => !empty($_ENV['GOOGLE_TRANSLATE_API_KEY'] ?? ''),
        'api_key' => $_ENV['GOOGLE_TRANSLATE_API_KEY'] ?? '',
        'translate_rendered_html' => false,
    ],
];
