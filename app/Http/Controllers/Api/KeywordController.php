<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Keyword;

class KeywordController extends Controller
{
    function index() {
        $keywords = Keyword::withCount('questions')->get();
        return [
            'count' => count($keywords),
            'keywords' => $keywords,
            'api' => [
                'with_category' => url("/api/keywords/{category}")
            ]
        ];
    }

    function with_category($category) {
        $keywords = Keyword::withCount('questions')
            ->where('category', 'LIKE', "%$category%")
            ->get();

        return [
            'count' => count($keywords),
            'keywords' => $keywords,
            'api' => [
                'all_keywords' => url("/api/keywords")
            ]
        ];
    }
}
