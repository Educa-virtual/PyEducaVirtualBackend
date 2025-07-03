<?php

namespace App\Http\Controllers;

class WordController extends Controller
{
    public static function  sanitizeHtml($html): string
    {
        return preg_replace('/<img([^>]+)(?<!\/)>/', '<img$1 />', $html);
    }
}
