<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class HomeController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('Главная страница');
        SEOTools::setDescription('Описание для SEO');
        SEOTools::metatags()->setKeywords(['laravel', 'seo', 'оптимизация']);
        return view('layouts.site');
    }
}
