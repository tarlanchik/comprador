<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        private SitemapService $sitemapService
    ) {}

    public function index(): Response
    {
        return $this->sitemapService->generateSitemap();
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /profile/\n";
        $content .= "Disallow: /cart\n";
        $content .= "\n";
        $content .= "Sitemap: " . route('sitemap') . "\n";

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
