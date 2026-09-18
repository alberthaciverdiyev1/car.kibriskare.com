<?php

namespace App\Modules\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $page_key
 * @property string $page_name
 * @property string|null $route_name
 * @property array|null $h1
 * @property array|null $title
 * @property array|null $description
 * @property array|null $keywords
 * @property string|null $canonical_url
 * @property string|null $og_image
 * @property int $sort_order
 */
class PageSeo extends Model
{
    protected $table = 'page_seos';

    protected $fillable = [
        'page_key',
        'page_name',
        'route_name',
        'h1',
        'title',
        'description',
        'keywords',
        'canonical_url',
        'og_image',
        'sort_order',
    ];

    protected $casts = [
        'h1' => 'array',
        'title' => 'array',
        'description' => 'array',
        'keywords' => 'array',
    ];

    public const CACHE_KEY_ALL = 'page_seos_all';

    protected static ?array $memoizedAll = null;
    protected static ?self $memoizedCurrent = null;

    protected static function booted(): void
    {
        static::saved(function () {
            static::$memoizedAll = null;
            static::$memoizedCurrent = null;
            Cache::forget(self::CACHE_KEY_ALL);
        });

        static::deleted(function () {
            static::$memoizedAll = null;
            static::$memoizedCurrent = null;
            Cache::forget(self::CACHE_KEY_ALL);
        });
    }

    /**
     * Bütün səhifə SEO parametrlərini açar üzrə assosiativ massiv kimi qaytarır
     */
    public static function allCached(): array
    {
        if (static::$memoizedAll !== null) {
            return static::$memoizedAll;
        }

        $cached = Cache::get(self::CACHE_KEY_ALL);
        if (is_array($cached) && !empty($cached) && reset($cached) instanceof self) {
            return static::$memoizedAll = $cached;
        }

        $all = self::orderBy('sort_order')->get()->keyBy('page_key')->all();
        if (empty($all)) {
            self::ensureDefaults();
            $all = self::orderBy('sort_order')->get()->keyBy('page_key')->all();
        }
        Cache::put(self::CACHE_KEY_ALL, $all, 86400);

        return static::$memoizedAll = $all;
    }

    protected static ?string $memoizedRequestKey = null;

    /**
     * Cari route və ya səhifə açarı üzrə PageSeo tapır
     */
    public static function findForCurrentRoute(?string $currentRoute = null): ?self
    {
        $requestKey = ($currentRoute ?: '') . '|' . request()->path();
        if ($currentRoute === null && static::$memoizedCurrent !== null && static::$memoizedRequestKey === $requestKey) {
            return static::$memoizedCurrent;
        }

        $all = self::allCached();
        $routeName = $currentRoute ?: request()->route()?->getName();
        $path = trim(request()->path(), '/');

        // Strip locale prefix if present (e.g. tr/hakkimizda -> hakkimizda)
        $segments = explode('/', $path);
        if (!empty($segments[0]) && in_array(strtolower($segments[0]), ['az', 'tr', 'en', 'ru'], true)) {
            array_shift($segments);
        }
        $cleanPath = implode('/', $segments);

        // 1. Home Page
        if ($cleanPath === '' || $cleanPath === '/') {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['home'] ?? null);
        }

        // 2. Specific static sections & modules (before wildcard routes)
        if (str_starts_with($cleanPath, 'avtosalonlar') || str_starts_with($cleanPath, 'autosalons') || str_starts_with($cleanPath, 'galeriler') || str_starts_with($cleanPath, 'agencies')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['autosalons'] ?? $all['agencies'] ?? null);
        }

        if (str_starts_with($cleanPath, 'blog') || str_starts_with($cleanPath, 'meqaleler') || str_starts_with($cleanPath, 'bloq')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['blog'] ?? null);
        }

        if (str_starts_with($cleanPath, 'contact') || str_starts_with($cleanPath, 'elaqe') || str_starts_with($cleanPath, 'iletisim')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['contact'] ?? null);
        }

        if (str_starts_with($cleanPath, 'about') || str_starts_with($cleanPath, 'haqqimizda') || str_starts_with($cleanPath, 'hakkimizda')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['about'] ?? null);
        }

        if (str_starts_with($cleanPath, 'faq') || str_starts_with($cleanPath, 'suallar') || str_starts_with($cleanPath, 'sss') || str_starts_with($cleanPath, 'sikca-sorulan-sorular')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['faq'] ?? null);
        }

        if (str_starts_with($cleanPath, 'compare') || str_starts_with($cleanPath, 'muqayise') || str_starts_with($cleanPath, 'karsilastir')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['compare'] ?? null);
        }

        if (str_starts_with($cleanPath, 'favorites') || str_starts_with($cleanPath, 'secilmisler') || str_starts_with($cleanPath, 'favoriler')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['favorites'] ?? null);
        }

        if (str_starts_with($cleanPath, 'add-car') || str_starts_with($cleanPath, 'elan-yerlesdir') || str_starts_with($cleanPath, 'araba-ekle') || str_starts_with($cleanPath, 'ilan-ver')) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = ($all['add_car'] ?? $all['add_property'] ?? null);
        }

        // 3. Deal type / Listing subpaths (Satılıq vs Kirayə vs Günlük)
        $first = request()->route('first') ?? ($segments[0] ?? null);
        $second = request()->route('second') ?? ($segments[1] ?? null);
        $dealType = request('deal_type');

        if (in_array($first, ['satilik', 'satis', 'satiq', 'sale'], true) || $dealType === 'sale') {
            if (isset($all['listing_sale'])) {
                static::$memoizedRequestKey = $requestKey;
                return static::$memoizedCurrent = $all['listing_sale'];
            }
        }

        if ($first === 'kira' || in_array($first, ['kiralik', 'kiraye', 'kiraya', 'rent'], true) || in_array($dealType, ['rent', 'rent_monthly', 'rent_daily'])) {
            if ($second === 'gunluk' || in_array($second, ['gundelik', 'daily'], true) || $dealType === 'rent_daily') {
                if (isset($all['listing_rent_daily'])) {
                    static::$memoizedRequestKey = $requestKey;
                    return static::$memoizedCurrent = $all['listing_rent_daily'];
                }
            }
            if ($second === 'ayliq' || in_array($second, ['aylik', 'monthly'], true) || $dealType === 'rent_monthly') {
                if (isset($all['listing_rent_monthly'])) {
                    static::$memoizedRequestKey = $requestKey;
                    return static::$memoizedCurrent = $all['listing_rent_monthly'];
                }
            }
        }

        // 4. Exact route name match (excluding generic wildcards)
        if ($routeName && !in_array($routeName, ['listing.path1', 'listing.path2', 'listing.path3'], true)) {
            foreach ($all as $pageSeo) {
                if ($pageSeo->route_name === $routeName) {
                    static::$memoizedRequestKey = $requestKey;
                    return static::$memoizedCurrent = $pageSeo;
                }
            }
        }

        // 5. Page key or URI fallback
        if (!empty($routeName) && isset($all[$routeName])) {
            static::$memoizedRequestKey = $requestKey;
            return static::$memoizedCurrent = $all[$routeName];
        }

        static::$memoizedRequestKey = $requestKey;
        return static::$memoizedCurrent = null;
    }

    /**
     * Çoxdilli sahədən cari dilə uyğun mətni qaytarır
     */
    public function getTrans(string $field, ?string $locale = null, string $default = ''): string
    {
        $locale = $locale ?: app()->getLocale();
        $values = $this->{$field};

        if (is_array($values)) {
            return $values[$locale] ?? $values['tr'] ?? $values['az'] ?? reset($values) ?: $default;
        }

        return (string) ($values ?: $default);
    }

    /**
     * Standart səhifələri avtomatik ilkinləşdirir
     */
    public static function ensureDefaults(): void
    {
        $defaultPages = [
            [
                'page_key' => 'home',
                'page_name' => 'Ana Səhifə',
                'route_name' => 'home',
                'sort_order' => 1,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Satılık ve Kiralık Araç İlanları, Oto Galeriler',
                    'az' => 'Şimali Kipr Satılıq və Kirayə Avtomobillər, Avtosalon Elanları',
                    'en' => 'Northern Cyprus Cars for Sale & Rent, Auto Salon Listings',
                    'ru' => 'Продажа и аренда авто на Северном Кипре, каталог автосалонов',
                ],
                'title' => [
                    'tr' => 'araba.kibriskare.com - KKTC Satılık ve Kiralık Araçlar, Oto Pazarı',
                    'az' => 'araba.kibriskare.com - Şimali Kipr Satılıq və Kirayə Maşınlar',
                    'en' => 'araba.kibriskare.com - North Cyprus Cars for Sale & Rent',
                    'ru' => 'araba.kibriskare.com - Автомобили на Северном Кипре',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs genelinde binlerce satılık ve günlük kiralık (rent a car) araç ilanları, güncel oto galeri kataloğu.',
                    'az' => 'Şimali Kipr üzrə minlərlə satılıq və günlük kirayə (rent a car) avtomobil elanları, aktual avtosalon kataloqu.',
                    'en' => 'Browse thousands of cars for sale and daily rentals (rent a car) across Northern Cyprus from verified dealers.',
                    'ru' => 'Тысячи предложений покупки и аренды автомобилей (rent a car) на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'listing_sale',
                'page_name' => 'Satılıq Avtomobillər',
                'route_name' => 'listing.path1',
                'sort_order' => 2,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Satılık 2. El ve Sıfır Araçlar',
                    'az' => 'Şimali Kiprdə Satılıq 2-ci Əl və Yeni Avtomobillər',
                    'en' => 'Used and New Cars for Sale in Northern Cyprus',
                    'ru' => 'Продажа новых и подержанных авто на Северном Кипре',
                ],
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Satılık Araçlar ve 2. El Otomobiller - KibrisKare',
                    'az' => 'Şimali Kipr Satılıq Avtomobillər və İkinci Əl Maşınlar - KibrisKare',
                    'en' => 'Cars For Sale in Northern Cyprus - Used & New Vehicles',
                    'ru' => 'Купить автомобиль на Северном Кипре - Авторынок KKTC',
                ],
                'description' => [
                    'tr' => 'Girne, Lefkoşa, Gazimağusa ve İskele genelinde en uygun fiyatlı satılık sedan, SUV, hatchback ve ticari araçlar.',
                    'az' => 'Girnə, Lefkoşa, Qazimağusa və İskele üzrə ən sərfəli qiymətə satılıq sedan, SUV, hetçbek və kommersiya avtomobilləri.',
                    'en' => 'Best priced sedans, SUVs, hatchbacks and commercial vehicles for sale across Northern Cyprus.',
                    'ru' => 'Седаны, внедорожники, хэтчбеки и коммерческий транспорт на продажу по всему Северному Кипру.',
                ],
            ],
            [
                'page_key' => 'listing_rent_daily',
                'page_name' => 'Kirayə Avtomobillər (Rent a Car)',
                'route_name' => 'listing.path2',
                'sort_order' => 3,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Günlük Kiralık Araçlar ve Rent a Car Fırsatları',
                    'az' => 'Şimali Kiprdə Günlük Kirayə Avtomobillər və Rent a Car',
                    'en' => 'Car Rental & Daily Hire in Northern Cyprus (Rent a Car)',
                    'ru' => 'Посуточная аренда автомобилей и Rent a Car на Кипре',
                ],
                'title' => [
                    'tr' => 'Kıbrıs Rent a Car - Günlük ve Dönemlik Kiralık Araçlar - KibrisKare',
                    'az' => 'Kipr Rent a Car - Günlük Kirayə Avtomobillər - KibrisKare',
                    'en' => 'Car Rental & Daily Hire in Northern Cyprus - KibrisKare',
                    'ru' => 'Аренда авто посуточно на Северном Кипре - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Ercan Havalimanı teslimatlı, kaskolu ve uygun fiyatlı günlük kiralık araçlar, rent a car filoları.',
                    'az' => 'Ercan Hava Limanında təhvil verilən, sığortalı və münasib qiymətə günlük kirayə avtomobillər.',
                    'en' => 'Affordable and insured daily rental cars with Ercan Airport delivery across Northern Cyprus.',
                    'ru' => 'Выгодная аренда автомобилей с подачей в аэропорт Эрджан на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'add_car',
                'page_name' => 'Yeni Elan Yerləşdir (Avtomobilini Sat / Kirayə Ver)',
                'route_name' => 'add-car',
                'sort_order' => 4,
                'h1' => [
                    'tr' => 'Ücretsiz Araç İlanı Ver, Arabanı Hızla Sat veya Kirala',
                    'az' => 'Pulsuz Avtomobil Elanı Yerləşdir, Maşınını Tez Sat və ya Kirayə Ver',
                    'en' => 'Post Free Car Listing in Northern Cyprus',
                    'ru' => 'Подать бесплатное объявление о продаже или аренде авто на Кипре',
                ],
                'title' => [
                    'tr' => 'Ücretsiz Araç İlanı Ver - Arabanı Sat veya Kirala - KibrisKare',
                    'az' => 'Pulsuz Avtomobil Elanı Yerləşdir - KibrisKare',
                    'en' => 'Post Free Vehicle Listing - Sell or Rent Cars in Cyprus',
                    'ru' => 'Подать бесплатное объявление о продаже авто - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Aracınızı binlerce alıcıya ve kiralayana kolayca ulaştırın. Hızlı ve ücretsiz ilan ekleme.',
                    'az' => 'Avtomobilinizi minlərlə alıcı və icarəçiyə asanlıqla çatdırın. Sürətli və pulsuz elan yerləşdirmə.',
                    'en' => 'Reach thousands of car buyers and renters across Northern Cyprus with quick free listing.',
                    'ru' => 'Разместите объявление об автомобиле и найдите покупателей быстро и удобно.',
                ],
            ],
            [
                'page_key' => 'autosalons',
                'page_name' => 'Oto Galerilər (Avtosalonlar)',
                'route_name' => 'autosalons.index',
                'sort_order' => 5,
                'h1' => [
                    'tr' => 'Kuzey Kıbrıs Yetkili Oto Galerileri ve Araç Satıcıları',
                    'az' => 'Şimali Kipr Avtosalonları və Rəsmi Qalereyalar',
                    'en' => 'Auto Salons and Authorized Car Dealerships in Northern Cyprus',
                    'ru' => 'Автосалоны и официальные дилеры Северного Кипра',
                ],
                'title' => [
                    'tr' => 'Kuzey Kıbrıs Güvenilir Oto Galerileri ve Satıcılar - KibrisKare',
                    'az' => 'Şimali Kipr Etibarlı Avtosalonları - KibrisKare',
                    'en' => 'Trusted Auto Salons & Dealers in Northern Cyprus - KibrisKare',
                    'ru' => 'Автосалоны и автодилеры Северного Кипра - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs genelinde hizmet veren kurumsal oto galerileri, rent a car şirketleri ve güncel araç stokları.',
                    'az' => 'Şimali Kipr üzrə fəaliyyət göstərən rəsmi avtosalonlar, rent a car şirkətləri və avtomobil parkları.',
                    'en' => 'Discover verified auto salons, licensed car dealerships and rental companies in Northern Cyprus.',
                    'ru' => 'Каталог проверенных автосалонов и прокатных компаний на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'blog',
                'page_name' => 'Bloq & Xəbərlər',
                'route_name' => 'blog.list',
                'sort_order' => 6,
                'h1' => [
                    'tr' => 'Kıbrıs Otomobil Dünyası, Araç İncelemeleri ve Sektör Haberleri',
                    'az' => 'Kipr Avtomobil Dünyası, Test-Drayv və Sektor Xəbərləri',
                    'en' => 'Cyprus Automotive News, Car Reviews and Driving Guides',
                    'ru' => 'Автомобильные новости, тест-драйвы и гид водителя на Кипре',
                ],
                'title' => [
                    'tr' => 'Kıbrıs Otomobil Rehberi, Araç İncelemeleri ve Haberler - Blog',
                    'az' => 'Kipr Avtomobil Bələdçisi, Xəbərlər və İcmallar - Bloq',
                    'en' => 'Cyprus Car Guide, Reviews & Automotive News - Blog',
                    'ru' => 'Автомобильный гид по Кипру, обзоры и новости - Блог',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs araç piyasası analizleri, model incelemeleri, sürüş ipuçları ve güncel otomotiv gelişmeleri.',
                    'az' => 'Şimali Kipr avtomobil bazarı analizləri, model icmalları və aktual xəbərlər.',
                    'en' => 'Northern Cyprus automotive market trends, car reviews and driving tips.',
                    'ru' => 'Обзоры автомобилей, советы водителям и новости авторынка Северного Кипра.',
                ],
            ],
            [
                'page_key' => 'contact',
                'page_name' => 'Əlaqə',
                'route_name' => 'contact',
                'sort_order' => 7,
                'h1' => [
                    'tr' => 'KibrisKare İletişim ve Müşteri Hizmetleri',
                    'az' => 'KibrisKare Əlaqə və Müştəri Xidmətləri',
                    'en' => 'Contact KibrisKare Customer Support',
                    'ru' => 'Контакты и служба поддержки KibrisKare',
                ],
                'title' => [
                    'tr' => 'İletişim - araba.kibriskare.com Müşteri Hizmetleri',
                    'az' => 'Əlaqə - araba.kibriskare.com Müştəri Xidmətləri',
                    'en' => 'Contact Us - KibrisKare Customer Support',
                    'ru' => 'Контакты - Служба поддержки KibrisKare',
                ],
                'description' => [
                    'tr' => 'KibrisKare müşteri hizmetleri, iletişim formu, telefon ve WhatsApp desteği ile bize ulaşın.',
                    'az' => 'KibrisKare müştəri xidmətləri, əlaqə forması, telefon və WhatsApp dəstəyi ilə bizimlə əlaqə saxlayın.',
                    'en' => 'Get in touch with KibrisKare customer support, phone and contact form.',
                    'ru' => 'Свяжитесь со службой поддержки KibrisKare по телефону или через форму обратной связи.',
                ],
            ],
            [
                'page_key' => 'about',
                'page_name' => 'Haqqımızda',
                'route_name' => 'about-us',
                'sort_order' => 8,
                'h1' => [
                    'tr' => 'KibrisKare Hakkında - Kıbrıs\'ın Güvenilir Otomobil Platformu',
                    'az' => 'KibrisKare Haqqında - Kiprin Etibarlı Avtomobil Platforması',
                    'en' => 'About KibrisKare - Cyprus Trusted Automotive Portal',
                    'ru' => 'О компании KibrisKare - Автомобильный портал Кипра',
                ],
                'title' => [
                    'tr' => 'Hakkımızda - araba.kibriskare.com Vizyon ve Misyonumuz',
                    'az' => 'Haqqımızda - araba.kibriskare.com Baxış və Missiyamız',
                    'en' => 'About Us - araba.kibriskare.com Vision & Mission',
                    'ru' => 'О нас - araba.kibriskare.com Миссия и видение',
                ],
                'description' => [
                    'tr' => 'araba.kibriskare.com hakkında bilgi, vizyonumuz ve KKTC otomotiv pazarına sunduğumuz dijital çözümler.',
                    'az' => 'araba.kibriskare.com haqqında məlumat, missiyamız və təqdim etdiyimiz avtomobil xidmətləri.',
                    'en' => 'Learn about araba.kibriskare.com, our automotive marketplace vision and solutions.',
                    'ru' => 'Информация об автопортале araba.kibriskare.com, миссия и наши решения.',
                ],
            ],
            [
                'page_key' => 'faq',
                'page_name' => 'Tez-tez Verilən Suallar (FAQ / SSS)',
                'route_name' => 'faq',
                'sort_order' => 9,
                'h1' => [
                    'tr' => 'Sıkça Sorulan Sorular ve Araç Alım-Satım Rehberi',
                    'az' => 'Tez-tez Verilən Suallar və Avtomobil Alqı-Satqı Bələdçisi',
                    'en' => 'Frequently Asked Questions (FAQ) - Cyprus Car Guide',
                    'ru' => 'Часто задаваемые вопросы об авторынке Кипра',
                ],
                'title' => [
                    'tr' => 'Sıkça Sorulan Sorular (SSS) - KibrisKare',
                    'az' => 'Tez-tez Verilən Suallar (FAQ) - KibrisKare',
                    'en' => 'Frequently Asked Questions (FAQ) - KibrisKare',
                    'ru' => 'Часто задаваемые вопросы (FAQ) - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Kuzey Kıbrıs’ta araç alırken, satarken, kiralarken ve ilan verirken en çok sorulan soruların yanıtları.',
                    'az' => 'Şimali Kiprdə avtomobil alarkən, satarkən, kirayələyərkən ən çox verilən sualların cavabları.',
                    'en' => 'Answers to common questions about buying, selling, renting and listing cars in Northern Cyprus.',
                    'ru' => 'Ответы на популярные вопросы о покупке, аренде и продаже авто на Северном Кипре.',
                ],
            ],
            [
                'page_key' => 'compare',
                'page_name' => 'Avtomobil Müqayisəsi',
                'route_name' => 'compares',
                'sort_order' => 10,
                'h1' => [
                    'tr' => 'Araç Karşılaştırma Listesi',
                    'az' => 'Avtomobil Müqayisəsi Siyahısı',
                    'en' => 'Vehicle Comparison List',
                    'ru' => 'Сравнение автомобилей',
                ],
                'title' => [
                    'tr' => 'Araç Karşılaştırma Aracı - KibrisKare',
                    'az' => 'Avtomobil Müqayisəsi Aləti - KibrisKare',
                    'en' => 'Vehicle Comparison Tool - KibrisKare',
                    'ru' => 'Сравнение автомобилей - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Seçtiğiniz araçların motor, yakıt, şanzıman ve donanım özelliklerini yan yana karşılaştırın.',
                    'az' => 'Seçdiyiniz avtomobillərin mühərrik, yanacaq, sürətlər qutusu və təchizat parametrlərini müqayisə edin.',
                    'en' => 'Compare vehicle engine, fuel, transmission and feature specifications side by side.',
                    'ru' => 'Сравните характеристики двигателей, комплектации и цены выбранных авто.',
                ],
            ],
            [
                'page_key' => 'favorites',
                'page_name' => 'Seçilmişlər (Sevimlilər)',
                'route_name' => 'favorites',
                'sort_order' => 11,
                'h1' => [
                    'tr' => 'Favori Araç İlanlarım',
                    'az' => 'Seçilmiş Avtomobil Elanlarım',
                    'en' => 'My Favorite Vehicles',
                    'ru' => 'Мои избранные автомобили',
                ],
                'title' => [
                    'tr' => 'Favori Araçlarım - KibrisKare',
                    'az' => 'Seçilmiş Avtomobillər - KibrisKare',
                    'en' => 'My Favorite Vehicles - KibrisKare',
                    'ru' => 'Избранные автомобили - KibrisKare',
                ],
                'description' => [
                    'tr' => 'Beğendiğiniz ve kaydettiğiniz satılık ve kiralık araç ilanlarını buradan takip edin.',
                    'az' => 'Bəyəndiyiniz və yadda saxladığınız satılıq və kirayə avtomobil elanlarını buradan izləyin.',
                    'en' => 'View and track your saved and favorite cars for sale and rent.',
                    'ru' => 'Сохраненные и избранные объявления автомобилей.',
                ],
            ],
        ];

        // 1. Clean up legacy keys
        self::whereIn('page_key', [
            'requests',
            'requests_create',
            'roommates',
            'roommates_create',
            'add_property',
            'agencies',
            'listing_rent_monthly',
        ])->delete();

        // 2. Insert or update default pages
        foreach ($defaultPages as $pageData) {
            $record = self::where('page_key', $pageData['page_key'])->first();
            if ($record) {
                $record->update([
                    'page_name' => $pageData['page_name'],
                    'route_name' => $pageData['route_name'],
                    'sort_order' => $pageData['sort_order'],
                    'h1' => $pageData['h1'],
                    'title' => $pageData['title'],
                    'description' => $pageData['description'],
                ]);
            } else {
                self::create($pageData);
            }
        }
    }
}
