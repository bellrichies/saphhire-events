<?php

namespace App\Core;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class HtmlContentTranslator
{
    /**
     * Translate rendered HTML for the currently selected language.
     */
    public static function translateForCurrentLanguage(string $html): string
    {
        $translator = Translator::getInstance();
        $targetLanguage = (string) $translator->getCurrentLanguage();
        $config = require CONFIG_PATH . '/languages.php';
        $defaultLanguage = (string) ($config['default'] ?? 'en');
        $googleConfig = $config['google_translate'] ?? [];
        $shouldTranslateRenderedHtml = (bool) ($googleConfig['translate_rendered_html'] ?? false);

        $machineTranslator = new MachineTranslator();
        if (
            !$shouldTranslateRenderedHtml
            || !$machineTranslator->shouldTranslate($targetLanguage)
            || !$machineTranslator->isAvailable()
        ) {
            return $html;
        }

        return self::translateHtml($html, $machineTranslator, $targetLanguage, $defaultLanguage);
    }

    private static function translateHtml(
        string $html,
        MachineTranslator $machineTranslator,
        string $targetLanguage,
        string $sourceLanguage
    ): string {
        if (trim($html) === '') {
            return $html;
        }

        libxml_use_internal_errors(true);

        $document = new DOMDocument('1.0', 'UTF-8');
        $wrapped = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body><div id="app-translation-root">'
            . $html
            . '</div></body></html>';
        $loadOk = $document->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        if (!$loadOk) {
            libxml_clear_errors();
            return $html;
        }

        $root = $document->getElementById('app-translation-root');
        if (!$root instanceof DOMElement) {
            libxml_clear_errors();
            return $html;
        }

        $textNodes = [];
        self::collectTextNodes($root, $textNodes);

        $attributeNodes = [];
        self::collectAttributeNodes($root, $attributeNodes);

        $texts = [];
        foreach ($textNodes as $node) {
            $texts[] = $node->nodeValue ?? '';
        }
        foreach ($attributeNodes as $item) {
            $texts[] = (string) $item['value'];
        }

        $translations = $machineTranslator->translateBatch($texts, $targetLanguage, $sourceLanguage);

        foreach ($textNodes as $node) {
            $original = $node->nodeValue ?? '';
            if (isset($translations[$original])) {
                $node->nodeValue = $translations[$original];
            }
        }

        foreach ($attributeNodes as $item) {
            /** @var DOMElement $element */
            $element = $item['element'];
            $name = (string) $item['name'];
            $value = (string) $item['value'];
            if (isset($translations[$value])) {
                $element->setAttribute($name, $translations[$value]);
            }
        }

        $xpath = new DOMXPath($document);
        $translatedNodes = $xpath->query('//*[@id="app-translation-root"]');
        if ($translatedNodes === false || $translatedNodes->length === 0) {
            libxml_clear_errors();
            return $html;
        }

        $translatedRoot = $translatedNodes->item(0);
        if (!$translatedRoot instanceof DOMElement) {
            libxml_clear_errors();
            return $html;
        }

        $output = '';
        foreach ($translatedRoot->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        libxml_clear_errors();
        return $output !== '' ? $output : $html;
    }

    /**
     * @param array<int, DOMNode> $nodes
     */
    private static function collectTextNodes(DOMNode $node, array &$nodes): void
    {
        if (self::shouldSkipNode($node)) {
            return;
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            $value = $node->nodeValue ?? '';
            if (self::isTranslatableText($value)) {
                $nodes[] = $node;
            }
            return;
        }

        foreach ($node->childNodes as $child) {
            self::collectTextNodes($child, $nodes);
        }
    }

    /**
     * @param array<int, array{element: DOMElement, name: string, value: string}> $nodes
     */
    private static function collectAttributeNodes(DOMElement $root, array &$nodes): void
    {
        $attributes = ['placeholder', 'title', 'aria-label', 'alt'];
        $stack = [$root];

        while (!empty($stack)) {
            /** @var DOMElement $element */
            $element = array_pop($stack);
            if (self::shouldSkipNode($element)) {
                continue;
            }

            foreach ($attributes as $attribute) {
                if (!$element->hasAttribute($attribute)) {
                    continue;
                }

                $value = (string) $element->getAttribute($attribute);
                if (self::isTranslatableText($value)) {
                    $nodes[] = [
                        'element' => $element,
                        'name' => $attribute,
                        'value' => $value,
                    ];
                }
            }

            foreach ($element->childNodes as $child) {
                if ($child instanceof DOMElement) {
                    $stack[] = $child;
                }
            }
        }
    }

    private static function shouldSkipNode(DOMNode $node): bool
    {
        $current = $node instanceof DOMElement ? $node : $node->parentNode;

        while ($current instanceof DOMElement) {
            $tag = strtolower($current->tagName);
            if (in_array($tag, ['script', 'style', 'noscript', 'code', 'pre', 'svg'], true)) {
                return true;
            }

            $translateAttribute = strtolower((string) $current->getAttribute('translate'));
            if ($translateAttribute === 'no') {
                return true;
            }

            $className = strtolower((string) $current->getAttribute('class'));
            if ($className !== '' && str_contains($className, 'notranslate')) {
                return true;
            }

            $current = $current->parentNode instanceof DOMElement ? $current->parentNode : null;
        }

        return false;
    }

    private static function isTranslatableText(string $value): bool
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return false;
        }

        if (preg_match('/^[\d\W_]+$/u', $trimmed) === 1) {
            return false;
        }

        if (preg_match('/^(https?:\/\/|mailto:|tel:)/i', $trimmed) === 1) {
            return false;
        }

        return true;
    }
}
