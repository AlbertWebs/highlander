<?php

namespace App\Support;

use ZipArchive;

/**
 * Extract plain-text paragraphs from a .docx (OOXML) without extra Composer packages.
 */
final class DocxPlainText
{
    /**
     * @return list<string> Non-empty trimmed paragraphs in document order.
     */
    public static function paragraphs(string $absolutePath): array
    {
        if (! is_readable($absolutePath)) {
            return [];
        }

        $zip = new ZipArchive;
        if ($zip->open($absolutePath) !== true) {
            return [];
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false || $xml === '') {
            return [];
        }

        $xml = preg_replace('/<w:tab[^>]*\/>/iu', "\t", $xml) ?? $xml;
        $xml = preg_replace('/<\/w:p>/iu', "\n", $xml) ?? $xml;
        $text = strip_tags($xml);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $line = preg_replace('/[ \t]+/u', ' ', trim($line)) ?? '';
            if ($line !== '') {
                $out[] = $line;
            }
        }

        return $out;
    }
}
