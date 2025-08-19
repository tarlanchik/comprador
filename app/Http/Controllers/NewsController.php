<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController
{
    public function index()
    {
        $articles = News::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('news.index', compact('articles'));
    }

    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $articles = News::with('author')
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit(6)
            ->get();

        return response()->json(['articles' => $articles]);
    }
}
