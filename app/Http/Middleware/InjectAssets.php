<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectAssets
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();

            // CSS & JS paths
            $styles = [
                asset('css/styles.css'),
                // secure_asset('css/styles.css'),

            ];
            $scripts = [
                asset('js/app.js'),
                // secure_asset('js/app.js'),

            ];

            // Inject CSS & JS
            $styleTags = implode("\n", array_map(fn($href) => "<link rel='stylesheet' href='$href'>", $styles));
            $scriptTags = implode("\n", array_map(fn($src) => "<script src='$src'></script>", $scripts));

            $content = str_replace('</head>', $styleTags . "\n</head>", $content);
            $content = str_replace('</body>', $scriptTags . "\n</body>", $content);

            $response->setContent($content);
        }

        return $response;
    }
}
