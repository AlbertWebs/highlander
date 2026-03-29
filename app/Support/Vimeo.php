<?php

namespace App\Support;

final class Vimeo
{
    public static function idFromUrl(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        if (preg_match('#(?:player\.)?vimeo\.com/video/(\d+)#', $url, $m)) {
            return $m[1];
        }

        if (preg_match('#vimeo\.com/(\d+)(?:\?|/|$)#', $url, $m)) {
            return $m[1];
        }

        return null;
    }
}
