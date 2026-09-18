<?php

namespace App\Modules\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['az', 'en', 'ru', 'tr'];
        $segment = strtolower((string) $request->segment(1));

        // Filament Admin & Agency panelləri üçün sessiyadakı dili (default: tr) təyin et
        if ($request->is('admin*') || $request->is('agency*')) {
            $panelLocale = session('lang', config('app.locale', 'tr'));
            if (!in_array($panelLocale, $supportedLocales, true)) {
                $panelLocale = 'tr';
            }
            app()->setLocale($panelLocale);

            return $next($request);
        }

        // Skip non-public / internal / asset paths & any requests with file extensions
        $path = ltrim($request->path(), '/');
        if (
            $request->is('api*') ||
            $request->is('livewire*') ||
            $request->is('lang*') ||
            $request->is('currency*') ||
            $request->is('build*') ||
            $request->is('vendor*') ||
            $request->is('storage*') ||
            $request->is('css*') ||
            $request->is('js*') ||
            $request->is('images*') ||
            $request->is('fonts*') ||
            $request->is('sm*') ||
            $request->is('favicon.ico') ||
            $request->is('robots.txt') ||
            $request->is('sitemap*.xml') ||
            $request->is('*reveal-phone*') ||
            preg_match('/\.(map|js|css|png|jpg|jpeg|gif|ico|svg|webp|woff|woff2|ttf|eot|xml|txt|json)$/i', $path)
        ) {
            return $next($request);
        }

        // Prevent repeated/chained locale prefix loops (e.g. /tr/tr/..., /tr/az/..., etc.)
        if (preg_match('#^(az|en|ru|tr)/(az|en|ru|tr)(/|$)#i', $path)) {
            abort(404);
        }

        // If URL starts with a valid language prefix (az, en, ru, tr)
        if (in_array($segment, $supportedLocales, true)) {
            $locale = $segment;
            session(['lang' => $locale]);
            app()->setLocale($locale);
            URL::defaults(['locale' => $locale]);

            return $next($request);
        }

        // Non-GET/HEAD requests without language prefix should not be redirected blindly
        if (!$request->isMethodSafe()) {
            return $next($request);
        }

        // If URL has NO language prefix, detect active/session language (default: tr) and redirect to /<locale>/...
        $locale = session('lang', config('app.locale', 'tr'));
        if (!in_array($locale, $supportedLocales, true)) {
            $locale = 'tr';
        }
        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        $targetPath = '/' . $locale . ($path === '' ? '' : '/' . $path);
        $query = $request->getQueryString() ? '?' . $request->getQueryString() : '';

        return redirect()->to($targetPath . $query);
    }
}
