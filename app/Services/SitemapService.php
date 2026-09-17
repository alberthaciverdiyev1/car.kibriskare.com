<?php

namespace App\Services;

use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Blog\Models\Blog;
use Illuminate\Support\Facades\File;

class SitemapService
{
    public const CHUNK_SIZE = 10000;

    public function generate(): array
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $locales = ['tr', 'az', 'en', 'ru'];
        $urls = [];

        // 1. Static pages for each locale
        $staticPaths = [
            '',
            '/ilanlar',
            '/arabalar',
            '/avtosalonlar',
            '/blog',
            '/hakkimizda',
            '/iletisim',
            '/sikca-sorulan-sorular',
            '/kullanici-sozlesmesi',
            '/gizlilik-politikasi',
            '/kullanim-kosullari',
            '/favoriler',
            '/karsilastir',
        ];

        foreach ($locales as $locale) {
            foreach ($staticPaths as $path) {
                $urls[] = [
                    'loc' => $baseUrl . '/' . $locale . $path,
                    'lastmod' => now()->toIso8601String(),
                    'changefreq' => 'daily',
                    'priority' => ($path === '' ? '1.0' : '0.8'),
                ];
            }
        }

        // 2. Cars (Active)
        Car::where('status', \App\Modules\Car\Enums\CarStatus::Active)->orderBy('id', 'desc')->chunk(500, function ($cars) use ($baseUrl, $locales, &$urls) {
            foreach ($cars as $car) {
                $lastmod = ($car->updated_at ?? $car->created_at ?? now())->toIso8601String();
                foreach ($locales as $locale) {
                    $urls[] = [
                        'loc' => $baseUrl . '/' . $locale . '/araba/' . $car->slug,
                        'lastmod' => $lastmod,
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            }
        });

        // 3. Autosalons (Active)
        Autosalon::where('is_active', true)->orderBy('id', 'desc')->chunk(100, function ($salons) use ($baseUrl, $locales, &$urls) {
            foreach ($salons as $salon) {
                $lastmod = ($salon->updated_at ?? $salon->created_at ?? now())->toIso8601String();
                $slug = $salon->slug ?? $salon->id;
                foreach ($locales as $locale) {
                    $urls[] = [
                        'loc' => $baseUrl . '/' . $locale . '/avtosalon/' . $slug,
                        'lastmod' => $lastmod,
                        'changefreq' => 'monthly',
                        'priority' => '0.6',
                    ];
                }
            }
        });

        // 4. Blog articles (Published)
        if (class_exists(Blog::class)) {
            Blog::published()->orderBy('id', 'desc')->chunk(100, function ($blogs) use ($baseUrl, $locales, &$urls) {
                foreach ($blogs as $blog) {
                    $lastmod = ($blog->updated_at ?? $blog->published_at ?? now())->toIso8601String();
                    foreach ($locales as $locale) {
                        $urls[] = [
                            'loc' => $baseUrl . '/' . $locale . '/blog/' . $blog->slug,
                            'lastmod' => $lastmod,
                            'changefreq' => 'weekly',
                            'priority' => '0.6',
                        ];
                    }
                }
            });
        }

        $totalUrls = count($urls);
        $generatedFiles = [];
        $publicPath = public_path();

        // Clean up old sitemap files
        $oldFiles = File::glob($publicPath . '/sitemap*.xml');
        foreach ($oldFiles as $oldFile) {
            @unlink($oldFile);
        }

        if ($totalUrls <= self::CHUNK_SIZE) {
            // Single sitemap file
            $filePath = $publicPath . '/sitemap.xml';
            $this->writeSitemapFile($filePath, $urls);
            $generatedFiles[] = [
                'name' => 'sitemap.xml',
                'url' => $baseUrl . '/sitemap.xml',
                'count' => $totalUrls,
            ];
        } else {
            // Split into chunks
            $chunks = array_chunk($urls, self::CHUNK_SIZE);
            $sitemaps = [];

            foreach ($chunks as $index => $chunkUrls) {
                $pageNum = $index + 1;
                $fileName = "sitemap_{$pageNum}.xml";
                $filePath = $publicPath . '/' . $fileName;
                $this->writeSitemapFile($filePath, $chunkUrls);

                $sitemaps[] = [
                    'loc' => $baseUrl . '/' . $fileName,
                    'lastmod' => now()->toIso8601String(),
                ];

                $generatedFiles[] = [
                    'name' => $fileName,
                    'url' => $baseUrl . '/' . $fileName,
                    'count' => count($chunkUrls),
                ];
            }

            // Write index sitemap
            $indexPath = $publicPath . '/sitemap.xml';
            $this->writeSitemapIndexFile($indexPath, $sitemaps);

            array_unshift($generatedFiles, [
                'name' => 'sitemap.xml (Sitemap İndeksi)',
                'url' => $baseUrl . '/sitemap.xml',
                'count' => $totalUrls,
            ]);
        }

        return $generatedFiles;
    }

    private function writeSitemapFile(string $path, array $urls): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '        <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        File::put($path, $xml);
    }

    private function writeSitemapIndexFile(string $path, array $sitemaps): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($sitemaps as $sitemap) {
            $xml .= '    <sitemap>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($sitemap['loc']) . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $sitemap['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    </sitemap>' . PHP_EOL;
        }

        $xml .= '</sitemapindex>';

        File::put($path, $xml);
    }
}
