<?php

namespace Database\Seeders;

use App\Modules\Car\Enums\CarCondition;
use App\Modules\Car\Enums\CarDealType;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Enums\Drivetrain;
use App\Modules\Car\Enums\FuelType;
use App\Modules\Car\Enums\SteeringWheel;
use App\Modules\Car\Enums\Transmission;
use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\CarBodyType;
use App\Modules\Car\Models\CarBrand;
use App\Modules\Car\Models\CarFeature;
use App\Modules\Car\Models\CarImage;
use App\Modules\Car\Models\CarModel;
use App\Modules\Location\Models\City;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Body Types
        $bodyTypes = [
            ['slug' => 'sedan', 'icon' => 'car-sedan', 'sort_order' => 1, 'name' => ['tr' => 'Sedan', 'en' => 'Sedan', 'az' => 'Sedan', 'ru' => 'Седан']],
            ['slug' => 'suv', 'icon' => 'car-suv', 'sort_order' => 2, 'name' => ['tr' => 'SUV / Crossover', 'en' => 'SUV', 'az' => 'SUV / Krossover', 'ru' => 'Внедорожник']],
            ['slug' => 'hatchback', 'icon' => 'car-hatchback', 'sort_order' => 3, 'name' => ['tr' => 'Hatchback', 'en' => 'Hatchback', 'az' => 'Hetçbek', 'ru' => 'Хэтчбек']],
            ['slug' => 'coupe', 'icon' => 'car-coupe', 'sort_order' => 4, 'name' => ['tr' => 'Coupe', 'en' => 'Coupe', 'az' => 'Kupe', 'ru' => 'Купе']],
            ['slug' => 'convertible', 'icon' => 'car-convertible', 'sort_order' => 5, 'name' => ['tr' => 'Cabrio / Üstü Açık', 'en' => 'Convertible', 'az' => 'Kabriolet', 'ru' => 'Кабриолет']],
            ['slug' => 'pickup', 'icon' => 'car-pickup', 'sort_order' => 6, 'name' => ['tr' => 'Pickup', 'en' => 'Pickup', 'az' => 'Pikap', 'ru' => 'Пикап']],
            ['slug' => 'van', 'icon' => 'car-van', 'sort_order' => 7, 'name' => ['tr' => 'Minivan / Van', 'en' => 'Minivan', 'az' => 'Miniven', 'ru' => 'Минивэн']],
            ['slug' => 'station-wagon', 'icon' => 'car-wagon', 'sort_order' => 8, 'name' => ['tr' => 'Station Wagon', 'en' => 'Station Wagon', 'az' => 'Universal', 'ru' => 'Универсал']],
        ];

        $savedBodyTypes = [];
        foreach ($bodyTypes as $bt) {
            $savedBodyTypes[$bt['slug']] = CarBodyType::updateOrCreate(['slug' => $bt['slug']], $bt);
        }

        // 2. Car Features
        $features = [
            // Comfort
            ['slug' => 'leather-seats', 'category' => 'comfort', 'name' => ['tr' => 'Deri Koltuklar', 'en' => 'Leather Seats', 'az' => 'Dəri Salon']],
            ['slug' => 'heated-seats', 'category' => 'comfort', 'name' => ['tr' => 'Koltuk Isıtma', 'en' => 'Heated Seats', 'az' => 'Oturacaqların qızdırılması']],
            ['slug' => 'ventilated-seats', 'category' => 'comfort', 'name' => ['tr' => 'Koltuk Soğutma', 'en' => 'Ventilated Seats', 'az' => 'Oturacaqların soyudulması']],
            ['slug' => 'sunroof', 'category' => 'comfort', 'name' => ['tr' => 'Açılır Tavan / Sunroof', 'en' => 'Sunroof', 'az' => 'Lyuk']],
            ['slug' => 'panoramic-roof', 'category' => 'comfort', 'name' => ['tr' => 'Panoramik Cam Tavan', 'en' => 'Panoramic Sunroof', 'az' => 'Panoram tavan']],
            ['slug' => 'keyless-go', 'category' => 'comfort', 'name' => ['tr' => 'Anahtarsız Giriş ve Çalıştırma (Keyless Go)', 'en' => 'Keyless Entry & Start', 'az' => 'Start/Stop & Keyless Go']],
            ['slug' => 'climate-control', 'category' => 'comfort', 'name' => ['tr' => 'Çift Bölgeli Dijital Klima', 'en' => 'Dual Climate Control', 'az' => 'İqlim-kontrol']],
            ['slug' => 'ambient-lighting', 'category' => 'comfort', 'name' => ['tr' => 'Ambiyans Aydınlatma', 'en' => 'Ambient Lighting', 'az' => 'Ambians İşıqlandırma']],
            ['slug' => 'electric-tailgate', 'category' => 'comfort', 'name' => ['tr' => 'Elektrikli Bagaj Kapağı', 'en' => 'Electric Tailgate', 'az' => 'Elektrikli Baqaj']],

            // Safety
            ['slug' => 'abs-esp', 'category' => 'safety', 'name' => ['tr' => 'ABS / ESP / Fren Destek', 'en' => 'ABS / ESP', 'az' => 'ABS & ESP']],
            ['slug' => 'lane-assist', 'category' => 'safety', 'name' => ['tr' => 'Şerit Takip Asistanı', 'en' => 'Lane Keeping Assist', 'az' => 'Zolaq izləmə sensoru']],
            ['slug' => 'blind-spot', 'category' => 'safety', 'name' => ['tr' => 'Kör Nokta Uyarı Sistemi', 'en' => 'Blind Spot Monitor', 'az' => 'Kor zona xəbərdarlığı']],
            ['slug' => 'adaptive-cruise', 'category' => 'safety', 'name' => ['tr' => 'Adaptif Hız Sabitleyici (ACC)', 'en' => 'Adaptive Cruise Control', 'az' => 'Adaptiv Kruiz-kontrol']],
            ['slug' => 'park-sensors', 'category' => 'safety', 'name' => ['tr' => 'Ön ve Arka Park Sensörleri', 'en' => 'Front & Rear Parking Sensors', 'az' => 'Park radarları']],
            ['slug' => 'camera-360', 'category' => 'safety', 'name' => ['tr' => '360 Derece Çevre Görüş Kamerası', 'en' => '360° Surround Camera', 'az' => '360 dərəcə kamera']],
            ['slug' => 'rear-camera', 'category' => 'safety', 'name' => ['tr' => 'Geri Görüş Kamerası', 'en' => 'Rear View Camera', 'az' => 'Arxa görüntü kamerası']],
            ['slug' => 'head-up-display', 'category' => 'safety', 'name' => ['tr' => 'Head-Up Display (Ön Cam Gösterge)', 'en' => 'Head-Up Display', 'az' => 'Head-Up Display']],

            // Multimedia
            ['slug' => 'apple-carplay', 'category' => 'multimedia', 'name' => ['tr' => 'Apple CarPlay & Android Auto', 'en' => 'Apple CarPlay & Android Auto', 'az' => 'Apple CarPlay & Android Auto']],
            ['slug' => 'navigation', 'category' => 'multimedia', 'name' => ['tr' => 'Dahili Navigasyon Sistemi', 'en' => 'Navigation System', 'az' => 'Naviqasiya']],
            ['slug' => 'premium-sound', 'category' => 'multimedia', 'name' => ['tr' => 'Premium Ses Sistemi (Burmester / Harman / Bose)', 'en' => 'Premium Sound System', 'az' => 'Premium səs sistemi']],
            ['slug' => 'wireless-charging', 'category' => 'multimedia', 'name' => ['tr' => 'Kablosuz Telefon Şarjı', 'en' => 'Wireless Phone Charging', 'az' => 'Simsiz şarj']],

            // Exterior
            ['slug' => 'led-headlights', 'category' => 'exterior', 'name' => ['tr' => 'LED / Matrix Farlar', 'en' => 'LED / Matrix Headlights', 'az' => 'LED faralar']],
            ['slug' => 'alloy-wheels', 'category' => 'exterior', 'name' => ['tr' => 'Alaşım Jantlar', 'en' => 'Alloy Wheels', 'az' => 'Yüngül lehimli disklər']],
            ['slug' => 'fog-lights', 'category' => 'exterior', 'name' => ['tr' => 'Sis Farları', 'en' => 'Fog Lights', 'az' => 'Duman əleyhinə işıqlar']],
            ['slug' => 'air-suspension', 'category' => 'exterior', 'name' => ['tr' => 'Havalı Süspansiyon (Airmatic)', 'en' => 'Air Suspension', 'az' => 'Pnevmatik asqı']],
        ];

        $savedFeatures = [];
        foreach ($features as $f) {
            $savedFeatures[$f['slug']] = CarFeature::updateOrCreate(['slug' => $f['slug']], $f);
        }

        // 3. Brands and Models
        $brandsData = [
            'Mercedes-Benz' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['G-Class', 'E-Class', 'C-Class', 'S-Class', 'A-Class', 'CLA', 'GLA', 'GLC', 'GLE', 'GLS', 'CLS', 'Vito']
            ],
            'BMW' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['3 Series', '5 Series', 'X5', 'X3', '4 Series', '7 Series', 'X6', 'X1', 'X7', 'M3', 'M4', 'M5', '1 Series', '2 Series']
            ],
            'Toyota' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['RAV4', 'Corolla', 'Yaris', 'Land Cruiser', 'Land Cruiser Prado', 'C-HR', 'Camry', 'Hilux', 'Prius', 'Aqua', 'Vitz', 'Noah', 'Voxy']
            ],
            'Porsche' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['Cayenne', 'Macan', 'Panamera', '911', 'Taycan', 'Boxster', 'Cayman']
            ],
            'Land Rover' => [
                'country' => 'UK', 'is_popular' => true,
                'models' => ['Defender', 'Range Rover Sport', 'Range Rover', 'Range Rover Evoque', 'Range Rover Velar', 'Discovery', 'Discovery Sport']
            ],
            'Nissan' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Qashqai', 'Juke', 'X-Trail', 'Note', 'Micra', 'Navara', 'Leaf', 'Patrol']
            ],
            'Honda' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Civic', 'CR-V', 'HR-V', 'Vezel', 'Jazz / Fit', 'Accord', 'Freed', 'Stepwgn']
            ],
            'Volkswagen' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['Golf', 'Passat', 'Tiguan', 'Polo', 'T-Roc', 'Touareg', 'Arteon', 'Amarok', 'Transporter', 'Caddy']
            ],
            'Ford' => [
                'country' => 'USA', 'is_popular' => true,
                'models' => ['Ranger', 'Mustang', 'Focus', 'Fiesta', 'Kuga', 'Puma', 'Transit']
            ],
            'Audi' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron']
            ],
            'Hyundai' => [
                'country' => 'South Korea', 'is_popular' => true,
                'models' => ['Tucson', 'i20', 'i30', 'Elantra', 'Santa Fe', 'Kona', 'Bayon', 'Ioniq 5']
            ],
            'Kia' => [
                'country' => 'South Korea', 'is_popular' => true,
                'models' => ['Sportage', 'Rio', 'Ceed', 'Sorento', 'Stonic', 'Picanto', 'Niro', 'EV6']
            ],
            'Suzuki' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Jimny', 'Swift', 'Vitara', 'S-Cross', 'Baleno', 'Ignis']
            ],
            'Mazda' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Mazda 2', 'Mazda 3', 'Mazda 6', 'CX-3', 'CX-5', 'CX-30', 'MX-5']
            ],
            'Mitsubishi' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['L200', 'Outlander', 'Pajero', 'ASX', 'Eclipse Cross']
            ],
            'Volvo' => [
                'country' => 'Sweden', 'is_popular' => false,
                'models' => ['XC40', 'XC60', 'XC90', 'S60', 'S90', 'V60']
            ],
            'Jeep' => [
                'country' => 'USA', 'is_popular' => false,
                'models' => ['Wrangler', 'Grand Cherokee', 'Compass', 'Renegade', 'Gladiator']
            ],
        ];

        $savedBrands = [];
        $savedModels = [];
        $sort = 1;

        foreach ($brandsData as $brandName => $bData) {
            $brand = CarBrand::updateOrCreate(
                ['name' => $brandName],
                [
                    'slug' => Str::slug($brandName),
                    'country' => $bData['country'],
                    'is_popular' => $bData['is_popular'],
                    'sort_order' => $sort++,
                    'is_active' => true,
                ]
            );
            $savedBrands[$brandName] = $brand;

            $mSort = 1;
            foreach ($bData['models'] as $mName) {
                $model = CarModel::updateOrCreate(
                    ['brand_id' => $brand->id, 'name' => $mName],
                    [
                        'slug' => Str::slug($mName),
                        'is_popular' => $mSort <= 3,
                        'sort_order' => $mSort++,
                        'is_active' => true,
                    ]
                );
                $savedModels[$brandName . '_' . $mName] = $model;
            }
        }

        // 4. Cities in KKTC
        $lefkosa = City::where('name->tr', 'Lefkoşa')->orWhere('name->en', 'Nicosia')->first() ?? City::first();
        $girne = City::where('name->tr', 'Girne')->orWhere('name->en', 'Kyrenia')->first() ?? City::first();
        $magusa = City::where('name->tr', 'Gazimağusa')->orWhere('name->en', 'Famagusta')->first() ?? City::first();
        $iskele = City::where('name->tr', 'İskele')->orWhere('name->en', 'Iskele')->first() ?? City::first();
        $guzelyurt = City::where('name->tr', 'Güzelyurt')->orWhere('name->en', 'Morphou')->first() ?? City::first();

        $admin = User::first();

        // 5. Autosalons in Cyprus
        $salons = [
            [
                'name' => 'Kıbrıs Motors Galeri',
                'slug' => 'kibris-motors-galeri',
                'phone' => '+90 533 888 11 22',
                'whatsapp' => '+90 533 888 11 22',
                'email' => 'info@kibrismotors.com',
                'city_id' => $lefkosa?->id,
                'address' => 'Dr. Fazıl Küçük Bulvarı No:42, Lefkoşa',
                'working_hours' => '09:00 - 18:30 (B.e - Şənbə)',
                'logo' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=400&auto=format&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1562911791-c7a97b729ec5?w=1200&auto=format&fit=crop&q=80',
                'description' => [
                    'tr' => 'Kıbrıs Motors olarak 15 yıllık tecrübemizle İngiltere ve Japonya ithal lüks ve güvenilir ikinci el ve sıfır araç satışı yapmaktayız.',
                    'az' => 'Kıbrıs Motors 15 illik təcrübəsi ilə İngiltərə və Yaponiyadan idxal olunmuş lüks və etibarlı avtomobillərin satışını həyata keçirir.',
                ],
                'is_verified' => true,
                'rating' => 4.9,
            ],
            [
                'name' => 'Girne Auto Center & Luxury Cars',
                'slug' => 'girne-auto-center',
                'phone' => '+90 542 855 33 44',
                'whatsapp' => '+90 542 855 33 44',
                'email' => 'sales@girneautocenter.com',
                'city_id' => $girne?->id,
                'address' => 'Karaoğlanoğlu Caddesi No:18, Girne',
                'working_hours' => '08:30 - 19:00',
                'logo' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&auto=format&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=1200&auto=format&fit=crop&q=80',
                'description' => [
                    'tr' => 'Girne bölgesinin en seçkin lüks araç galerisi. BMW, Mercedes, Porsche ve Range Rover modelleri için showroomumuza bekleriz.',
                    'az' => 'Girnə bölgəsinin ən seçkin lüks avtomobil salonu.',
                ],
                'is_verified' => true,
                'rating' => 4.8,
            ],
            [
                'name' => 'Mağusa Auto & Rent a Car',
                'slug' => 'magusa-auto-rent-a-car',
                'phone' => '+90 533 870 55 66',
                'whatsapp' => '+90 533 870 55 66',
                'email' => 'info@magusaauto.com',
                'city_id' => $magusa?->id,
                'address' => 'İsmet İnönü Bulvarı No:105, Gazimağusa',
                'working_hours' => '24/7 Açıq (Havalimanı Teslim)',
                'logo' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=400&auto=format&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1508974239320-0a029497e820?w=1200&auto=format&fit=crop&q=80',
                'description' => [
                    'tr' => 'Gazimağusa ve tüm KKTC genelinde araç kiralama (Rent a Car) ve uygun fiyatlı ikinci el araç satışı.',
                    'az' => 'Qazimağusa və bütün ŞKTR ərazisində avtomobil icarəsi və satışı.',
                ],
                'is_verified' => true,
                'rating' => 4.7,
            ],
            [
                'name' => 'Akdeniz Premium Motors',
                'slug' => 'akdeniz-premium-motors',
                'phone' => '+90 548 833 77 11',
                'whatsapp' => '+90 548 833 77 11',
                'email' => 'akdeniz@premiumkibris.com',
                'city_id' => $lefkosa?->id,
                'address' => 'Organize Sanayi Bölgesi 2. Cadde, Lefkoşa',
                'working_hours' => '09:00 - 18:00',
                'logo' => 'https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?w=400&auto=format&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?w=1200&auto=format&fit=crop&q=80',
                'description' => [
                    'tr' => 'Japonya ve İngiltere doğrudan araç ithalatı, gümrükleme ve takas imkanları.',
                    'az' => 'Yaponiya və İngiltərədən birbaşa idxal, gömrük və barter.',
                ],
                'is_verified' => true,
                'rating' => 4.9,
            ],
            [
                'name' => 'İskele Long Beach Car Club',
                'slug' => 'iskele-long-beach-car-club',
                'phone' => '+90 533 899 44 55',
                'whatsapp' => '+90 533 899 44 55',
                'email' => 'contact@longbeachcars.com',
                'city_id' => $iskele?->id,
                'address' => 'Long Beach Sahil Yolu, İskele',
                'working_hours' => '09:00 - 20:00',
                'logo' => 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=400&auto=format&fit=crop&q=80',
                'banner' => 'https://images.unsplash.com/photo-1514316454349-750a7fd3da3a?w=1200&auto=format&fit=crop&q=80',
                'description' => [
                    'tr' => 'İskele ve Long Beach bölgesinde SUV, Sedan ve Cabrio araç kiralama & satış merkezi.',
                    'az' => 'İskələ bölgəsində SUV, Sedan və Cabrio avtomobil icarəsi və satışı.',
                ],
                'is_verified' => true,
                'rating' => 4.6,
            ],
        ];

        $savedSalons = [];
        foreach ($salons as $s) {
            $savedSalons[] = Autosalon::updateOrCreate(
                ['slug' => $s['slug']],
                array_merge($s, ['user_id' => $admin?->id, 'is_active' => true])
            );
        }

        // Clean up previous demo cars
        Car::truncate();
        CarImage::truncate();
        \Illuminate\Support\Facades\DB::table('car_feature_car')->truncate();

        // 6. Comprehensive Realistic Demo Cars
        $carsData = [
            // 1. Mercedes G 63 AMG
            [
                'brand' => 'Mercedes-Benz', 'model' => 'G-Class', 'body' => 'suv',
                'year' => 2023, 'mileage' => 14000, 'engine_volume' => 3982, 'engine_power' => 585,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Mat Qara (Obsidian Black)', 'is_metallic' => true,
                'price_gbp' => 165000, 'price_try' => 7450000, 'price_eur' => 193000, 'price_usd' => 210000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 1,
                'city_id' => $girne?->id, 'contact_name' => 'Girne Auto Center', 'contact_phone' => '+90 542 855 33 44',
                'title' => ['tr' => '2023 Mercedes-Benz G 63 AMG V8 BiTurbo - Night Package - Özel Plakalı', 'en' => '2023 Mercedes-Benz G 63 AMG V8 BiTurbo', 'az' => '2023 Mercedes-Benz G 63 AMG V8 BiTurbo Night Package'],
                'description' => [
                    'tr' => "İngiltere çıkışlı, ilk sahibinden özel sipariş Night Package G63 AMG.\n- Burmester 3D Surround ses sistemi\n- 22 inç Forged AMG jantlar\n- Karbon fiber iç kaplamalar\n- 360 Çevre görüş ve aktif sürüş asistanları\nKazasız, boyasız, sıfır ayarında. KKTC plakalı ve tüm vergileri ödenmiştir.",
                    'az' => "İngiltərədən xüsusi sifarişlə gətirilib, ilk sahibindən. Burmester 3D audio, 22 AMG disklər, Karbon salon. Vuruqsuz, rəngsiz.",
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1520050206274-a1ae44613e6d?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1563720223185-11003d516935?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 2. BMW M4 Competition Coupe
            [
                'brand' => 'BMW', 'model' => '4 Series', 'body' => 'coupe',
                'year' => 2022, 'mileage' => 22000, 'engine_volume' => 2993, 'engine_power' => 510,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::RearWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Sarı (Sao Paulo Yellow)', 'is_metallic' => true,
                'price_gbp' => 69000, 'price_try' => 3100000, 'price_eur' => 81000, 'price_usd' => 88000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 0,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Kıbrıs Motors Galeri', 'contact_phone' => '+90 533 888 11 22',
                'title' => ['tr' => '2022 BMW M4 Competition Coupe (G82) - Carbon Bucket Seats', 'en' => '2022 BMW M4 Competition Coupe', 'az' => '2022 BMW M4 Competition Coupe (G82)'],
                'description' => [
                    'tr' => "G82 Kasa BMW M4 Competition. M Carbon Bucket yarış koltukları, M Drift Analyser, Head-Up Display, Harman Kardon ses, Lazer Farlar. Kusursuz kondisyonda.",
                    'az' => "G82 kuza BMW M4 Competition. M Carbon oturacaqlar, Lazer faralar, Harman Kardon.",
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1555353540-64580b51c258?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 3. Porsche Cayenne Coupe
            [
                'brand' => 'Porsche', 'model' => 'Cayenne', 'body' => 'suv',
                'year' => 2021, 'mileage' => 35000, 'engine_volume' => 2995, 'engine_power' => 340,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Ağ (Crayon)', 'is_metallic' => true,
                'price_gbp' => 78500, 'price_try' => 3550000, 'price_eur' => 92000, 'price_usd' => 100000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 1,
                'city_id' => $girne?->id, 'contact_name' => 'Girne Auto Center', 'contact_phone' => '+90 542 855 33 44',
                'title' => ['tr' => '2021 Porsche Cayenne Coupe 3.0 V6 - Sport Chrono & Panoramik', 'en' => '2021 Porsche Cayenne Coupe', 'az' => '2021 Porsche Cayenne Coupe Sport Chrono'],
                'description' => [
                    'tr' => "Porsche yetkili servis bakımlı. Sport Chrono paketi, 21 inç RS Spyder jantlar, Bose Surround ses sistemi, 14 yönlü elektrikli hafızalı konfor koltuklar.",
                    'az' => "Sport Chrono paketi, 21 RS Spyder disklər, Bose audio, tam full komplektasiya.",
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 4. Land Rover Defender 110
            [
                'brand' => 'Land Rover', 'model' => 'Defender', 'body' => 'suv',
                'year' => 2022, 'mileage' => 28000, 'engine_volume' => 2996, 'engine_power' => 300,
                'fuel' => FuelType::Diesel, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Yaşıl / Tasman Blue', 'is_metallic' => true,
                'price_gbp' => 64500, 'price_try' => 2900000, 'price_eur' => 75500, 'price_usd' => 82000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 3,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Akdeniz Premium Motors', 'contact_phone' => '+90 548 833 77 11',
                'title' => ['tr' => '2022 Land Rover Defender 110 D300 SE - Black Pack & Air Suspension', 'en' => '2022 Land Rover Defender 110 D300 SE', 'az' => '2022 Land Rover Defender 110 D300 SE'],
                'description' => [
                    'tr' => "İngiltere ithal, kusursuz arazi ve şehir canavarı. Havalı süspansiyon, Meridian ses, 3D Surround kamera sistemi, katlanır tavan.",
                    'az' => "İngiltərədən idxal, pnevma asqı, Meridian səs, 3D surround kamera.",
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1563720223185-11003d516935?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 5. Toyota RAV4 Hybrid 2022
            [
                'brand' => 'Toyota', 'model' => 'RAV4', 'body' => 'suv',
                'year' => 2022, 'mileage' => 21000, 'engine_volume' => 2487, 'engine_power' => 218,
                'fuel' => FuelType::Hybrid, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Mirvari Ağ', 'is_metallic' => true,
                'price_gbp' => 28900, 'price_try' => 1300000, 'price_eur' => 33900, 'price_usd' => 36900,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 0,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Kıbrıs Motors Galeri', 'contact_phone' => '+90 533 888 11 22',
                'title' => ['tr' => '2022 Toyota RAV4 2.5 Hybrid AWD Dynamic - Japonya İthal', 'en' => '2022 Toyota RAV4 2.5 Hybrid AWD Dynamic', 'az' => '2022 Toyota RAV4 2.5 Hybrid AWD Dynamic'],
                'description' => [
                    'tr' => "Japonya çıkışlı, düşük kilometreli, yakıt tasarruflu mükemmel aile SUV'u. Deri koltuk, elektrikli bagaj, CarPlay, şerit takip, kör nokta uyarı.",
                    'az' => "Yaponiyadan gətirilib, az yürüşlü, çox qənaətcil hibrid SUV. Dəri oturacaqlar, elektrikli baqaj, Apple CarPlay.",
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1581540222194-0def2dda95b8?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 6. Mercedes-Benz E 220 d AMG
            [
                'brand' => 'Mercedes-Benz', 'model' => 'E-Class', 'body' => 'sedan',
                'year' => 2021, 'mileage' => 38000, 'engine_volume' => 1950, 'engine_power' => 194,
                'fuel' => FuelType::Diesel, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::RearWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Qara Metalik', 'is_metallic' => true,
                'price_gbp' => 34500, 'price_try' => 1550000, 'price_eur' => 40500, 'price_usd' => 44000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 0,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Kıbrıs Motors Galeri', 'contact_phone' => '+90 533 888 11 22',
                'title' => ['tr' => '2021 Mercedes-Benz E 220 d AMG Line - Sağ Direksiyon', 'en' => '2021 Mercedes-Benz E 220 d AMG Line', 'az' => '2021 Mercedes-Benz E 220 d AMG Line - Sağ Sükan'],
                'description' => [
                    'tr' => 'İngiltere ithal, ilk sahibinden, hatasız ve boyasız. Panoramik cam tavan, Burmester ses sistemi, 360 kamera, aktif şerit takip.',
                    'az' => 'İngiltərədən gətirilib, birinci sahibindən, vuruqsuz və rəngsiz. Panoram lyuk, Burmester səs sistemi, 360 kamera.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 7. BMW 320i M Sport 2020
            [
                'brand' => 'BMW', 'model' => '3 Series', 'body' => 'sedan',
                'year' => 2020, 'mileage' => 49000, 'engine_volume' => 1998, 'engine_power' => 184,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::RearWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Mavi / Portimao Blue', 'is_metallic' => true,
                'price_gbp' => 26500, 'price_try' => 1190000, 'price_eur' => 31000, 'price_usd' => 33900,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => false, 'seller_type' => 'owner', 'salon_idx' => null,
                'city_id' => $girne?->id, 'contact_name' => 'Kemal Özcan', 'contact_phone' => '+90 533 844 77 99',
                'title' => ['tr' => '2020 BMW 320i M Sport (G20) - Sahibinden Temiz', 'en' => '2020 BMW 320i M Sport (G20)', 'az' => '2020 BMW 320i M Sport (G20)'],
                'description' => [
                    'tr' => 'Sahibinden temiz kullanılmış M Sport paket. Live Cockpit Professional, Harman Kardon ses, 19 inç M jantlar, sunroof.',
                    'az' => 'Şəxsi maşınımdır, M Sport paket. Harman Kardon, 19 diskler, lyuk.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 8. Ford Ranger Wildtrak 2022
            [
                'brand' => 'Ford', 'model' => 'Ranger', 'body' => 'pickup',
                'year' => 2022, 'mileage' => 31000, 'engine_volume' => 1996, 'engine_power' => 213,
                'fuel' => FuelType::Diesel, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Narinci (Pride Orange)', 'is_metallic' => true,
                'price_gbp' => 32000, 'price_try' => 1440000, 'price_eur' => 37500, 'price_usd' => 41000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 3,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Akdeniz Premium Motors', 'contact_phone' => '+90 548 833 77 11',
                'title' => ['tr' => '2022 Ford Ranger 2.0 Bi-Turbo Wildtrak 4x4 - Offroad Donanımlı', 'en' => '2022 Ford Ranger 2.0 Bi-Turbo Wildtrak', 'az' => '2022 Ford Ranger Wildtrak 4x4'],
                'description' => [
                    'tr' => 'Wildtrak full donanım, 10 ileri otomatik vites, 4x4 kilitli diferansiyel, elektrikli sürgülü kasa kapağı, deri koltuklar.',
                    'az' => 'Wildtrak full komplektasiya, 10 pilləli avtomat, 4x4 kilid, dəri salon.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 9. Suzuki Jimny 4x4 2023
            [
                'brand' => 'Suzuki', 'model' => 'Jimny', 'body' => 'suv',
                'year' => 2023, 'mileage' => 8500, 'engine_volume' => 1462, 'engine_power' => 102,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Kinetik Sarı / Yaşıl', 'is_metallic' => false,
                'price_gbp' => 21900, 'price_try' => 990000, 'price_eur' => 25800, 'price_usd' => 28000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 4,
                'city_id' => $iskele?->id, 'contact_name' => 'İskele Long Beach Car Club', 'contact_phone' => '+90 533 899 44 55',
                'title' => ['tr' => '2023 Suzuki Jimny 1.5 AllGrip Pro 4x4 - Sıfır Ayarında', 'en' => '2023 Suzuki Jimny 1.5 AllGrip Pro', 'az' => '2023 Suzuki Jimny 1.5 AllGrip 4x4'],
                'description' => [
                    'tr' => 'Kıbrıs yolları ve plajları için mükemmel kompakt 4x4 jip. LED farlar, dokunmatik ekran, hız sabitleyici, yokuş kalkış ve iniş desteği.',
                    'az' => 'Kipr üçün ideal kompakt 4x4 cipi. LED faralar, sensor ekran, kruiz kontrol.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 10. Daily Rental: Nissan Note e-Power 2023
            [
                'brand' => 'Nissan', 'model' => 'Note', 'body' => 'hatchback',
                'year' => 2023, 'mileage' => 15000, 'engine_volume' => 1198, 'engine_power' => 116,
                'fuel' => FuelType::Hybrid, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::FrontWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Gümüşü Metalik', 'is_metallic' => true,
                'price_gbp' => 35, 'price_try' => 1500, 'price_eur' => 40, 'price_usd' => 45,
                'deal_type' => CarDealType::RentDaily, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 2,
                'city_id' => $magusa?->id, 'contact_name' => 'Mağusa Auto & Rent', 'contact_phone' => '+90 533 870 55 66',
                'title' => ['tr' => '2023 Nissan Note e-Power Hybrid - Günlük Kiralık (Rent a Car)', 'en' => '2023 Nissan Note e-Power - Daily Rental', 'az' => '2023 Nissan Note e-Power - Günlük Kirayə (Rent a Car)'],
                'description' => [
                    'tr' => "Ercan Havalimanı teslim imkanı. Düşük yakıt tüketimli e-Power hibrit sistem. Kaskolu ve bakımlı günlük/haftalık/aylık kiralık araç.",
                    'az' => "Ercan Hava limanında təhvil verilmə imkanı. Çox ekonom hibrid, tam sığortalı günlük və aylıq icarə.",
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 11. Daily Rental: Toyota Yaris 2022
            [
                'brand' => 'Toyota', 'model' => 'Yaris', 'body' => 'hatchback',
                'year' => 2022, 'mileage' => 26000, 'engine_volume' => 1490, 'engine_power' => 116,
                'fuel' => FuelType::Hybrid, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::FrontWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Ağ', 'is_metallic' => false,
                'price_gbp' => 30, 'price_try' => 1350, 'price_eur' => 35, 'price_usd' => 39,
                'deal_type' => CarDealType::RentDaily, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 4,
                'city_id' => $iskele?->id, 'contact_name' => 'İskele Long Beach Car Club', 'contact_phone' => '+90 533 899 44 55',
                'title' => ['tr' => '2022 Toyota Yaris Hybrid - Günlük Kirayə / Rent a Car', 'en' => '2022 Toyota Yaris Hybrid Daily Rental', 'az' => '2022 Toyota Yaris Hybrid - Günlük Kirayə'],
                'description' => [
                    'tr' => 'İskele ve Gazimağusa bölgesinde adrese teslim. Otomatik vites, geri görüş kamerası, klimalı, temiz ve dezenfekte edilmiş.',
                    'az' => 'İskələ və Mağusa ərazisində ünvana çatdırılma. Avtomat, arxa kamera, kondisioner.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1581540222194-0def2dda95b8?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 12. Volkswagen Golf 8 R-Line 2021
            [
                'brand' => 'Volkswagen', 'model' => 'Golf', 'body' => 'hatchback',
                'year' => 2021, 'mileage' => 36000, 'engine_volume' => 1498, 'engine_power' => 150,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::FrontWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Boz Metalik (Dolphin Grey)', 'is_metallic' => true,
                'price_gbp' => 22500, 'price_try' => 1010000, 'price_eur' => 26400, 'price_usd' => 28800,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => true, 'seller_type' => 'owner', 'salon_idx' => null,
                'city_id' => $girne?->id, 'contact_name' => 'Tolga Demir', 'contact_phone' => '+90 533 866 22 11',
                'title' => ['tr' => '2021 Volkswagen Golf 8 1.5 eTSI R-Line - Panoramik Cam Tavan', 'en' => '2021 VW Golf 8 1.5 eTSI R-Line', 'az' => '2021 VW Golf 8 1.5 eTSI R-Line'],
                'description' => [
                    'tr' => 'Sahibinden temiz, R-Line iç ve dış paket, dijital kokpit, IQ.Light matrix farlar, şerit takip ve kablosuz CarPlay.',
                    'az' => 'R-Line salon və ban, rəqəmsal kokpit, Matrix faralar, lyuk.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 13. Hyundai Tucson 2022
            [
                'brand' => 'Hyundai', 'model' => 'Tucson', 'body' => 'suv',
                'year' => 2022, 'mileage' => 27000, 'engine_volume' => 1598, 'engine_power' => 180,
                'fuel' => FuelType::Hybrid, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::FrontWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Qara Metalik', 'is_metallic' => true,
                'price_gbp' => 24800, 'price_try' => 1120000, 'price_eur' => 29000, 'price_usd' => 31800,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 2,
                'city_id' => $magusa?->id, 'contact_name' => 'Mağusa Auto & Rent', 'contact_phone' => '+90 533 870 55 66',
                'title' => ['tr' => '2022 Hyundai Tucson 1.6 T-GDI Hybrid N-Line - Full Donanım', 'en' => '2022 Hyundai Tucson 1.6 T-GDI N-Line', 'az' => '2022 Hyundai Tucson N-Line Hybrid'],
                'description' => [
                    'tr' => 'N-Line sportif tasarım, deri/süet koltuklar, 360 kamera, Krell premium ses sistemi, elektrikli hafızalı koltuklar.',
                    'az' => 'N-Line idman paketi, dəri/alkaantara oturacaqlar, 360 kamera, Krell audio.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1508974239320-0a029497e820?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 14. Audi Q8 S-Line 2021
            [
                'brand' => 'Audi', 'model' => 'Q8', 'body' => 'suv',
                'year' => 2021, 'mileage' => 41000, 'engine_volume' => 2967, 'engine_power' => 286,
                'fuel' => FuelType::Diesel, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Daytona Boz', 'is_metallic' => true,
                'price_gbp' => 63000, 'price_try' => 2840000, 'price_eur' => 74000, 'price_usd' => 80500,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 1,
                'city_id' => $girne?->id, 'contact_name' => 'Girne Auto Center', 'contact_phone' => '+90 542 855 33 44',
                'title' => ['tr' => '2021 Audi Q8 50 TDI Quattro S-Line - Black Edition - Panoramik', 'en' => '2021 Audi Q8 50 TDI Quattro S-Line', 'az' => '2021 Audi Q8 50 TDI Quattro S-Line'],
                'description' => [
                    'tr' => 'Audi Black Edition görünüm, HD Matrix LED farlar, Bang & Olufsen ses sistemi, soft-close vakumlu kapılar, havalı süspansiyon.',
                    'az' => 'Black Edition, HD Matrix LED faralar, Bang & Olufsen audio, pnevmatik asqı, qapı vakuumları.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 15. Ford Mustang GT 5.0 V8 2020
            [
                'brand' => 'Ford', 'model' => 'Mustang', 'body' => 'coupe',
                'year' => 2020, 'mileage' => 33000, 'engine_volume' => 4951, 'engine_power' => 450,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::RearWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Qırmızı (Race Red)', 'is_metallic' => false,
                'price_gbp' => 38500, 'price_try' => 1730000, 'price_eur' => 45000, 'price_usd' => 49000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => true, 'seller_type' => 'owner', 'salon_idx' => null,
                'city_id' => $girne?->id, 'contact_name' => 'Caner Arslan', 'contact_phone' => '+90 533 822 66 55',
                'title' => ['tr' => '2020 Ford Mustang GT 5.0 V8 - MagneRide & Aktif Egzoz', 'en' => '2020 Ford Mustang GT 5.0 V8', 'az' => '2020 Ford Mustang GT 5.0 V8'],
                'description' => [
                    'tr' => 'Orijinal V8 motor sesi, aktif valfli egzoz, MagneRide süspansiyon, dijital gösterge, Shaker ses sistemi. Garaj arabası.',
                    'az' => 'Orijinal 5.0 V8 motor, aktiv səsboğucu, rəqəmsal panel, qaraj şəraitində saxlanılıb.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1584345604476-8ec5e12e42dd?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=1000&auto=format&fit=crop&q=80',
                ],
            ],

            // 16. Toyota Hilux Invincible 2023
            [
                'brand' => 'Toyota', 'model' => 'Hilux', 'body' => 'pickup',
                'year' => 2023, 'mileage' => 18000, 'engine_volume' => 2755, 'engine_power' => 204,
                'fuel' => FuelType::Diesel, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Titan Boz Metalik', 'is_metallic' => true,
                'price_gbp' => 37000, 'price_try' => 1670000, 'price_eur' => 43500, 'price_usd' => 47000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 3,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Akdeniz Premium Motors', 'contact_phone' => '+90 548 833 77 11',
                'title' => ['tr' => '2023 Toyota Hilux Invincible X 2.8 D-4D 4x4 Otomatik', 'en' => '2023 Toyota Hilux Invincible X', 'az' => '2023 Toyota Hilux Invincible X 4x4'],
                'description' => [
                    'tr' => 'Invincible X en üst donanım, JBL ses sistemi, 360 çevre görüş, ısıtmalı deri koltuklar, orijinal basamak ve roll-bar.',
                    'az' => 'Invincible X ən üst paket, JBL səs sistemi, 360 kamera, qızdırılan dəri oturacaqlar.',
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=1000&auto=format&fit=crop&q=80',
                ],
            ],
        ];

        foreach ($carsData as $c) {
            $brand = $savedBrands[$c['brand']] ?? CarBrand::first();
            $model = $savedModels[$c['brand'] . '_' . $c['model']] ?? CarModel::where('brand_id', $brand->id)->first();
            $body = $savedBodyTypes[$c['body']] ?? CarBodyType::first();
            $salon = $c['salon_idx'] !== null ? ($savedSalons[$c['salon_idx']] ?? null) : null;

            $slugTitle = $c['title']['tr'] ?? 'car';
            $slug = Str::slug($slugTitle) . '-' . Str::lower(Str::random(6));

            $car = Car::create([
                'user_id' => $admin?->id,
                'autosalon_id' => $salon?->id,
                'brand_id' => $brand->id,
                'model_id' => $model->id,
                'body_type_id' => $body?->id,
                'city_id' => $c['city_id'],
                'title' => $c['title'],
                'slug' => $slug,
                'description' => $c['description'],
                'deal_type' => $c['deal_type'],
                'price_gbp' => $c['price_gbp'],
                'price_try' => $c['price_try'],
                'price_eur' => $c['price_eur'],
                'price_usd' => $c['price_usd'],
                'main_currency' => 'GBP',
                'year' => $c['year'],
                'mileage' => $c['mileage'],
                'mileage_unit' => 'km',
                'engine_volume' => $c['engine_volume'],
                'engine_power' => $c['engine_power'],
                'fuel_type' => $c['fuel'],
                'transmission' => $c['transmission'],
                'drivetrain' => $c['drivetrain'],
                'steering_wheel' => $c['steering'],
                'color' => $c['color'],
                'is_metallic' => $c['is_metallic'],
                'doors' => $c['body'] === 'coupe' ? 2 : 4,
                'seats' => $c['body'] === 'coupe' ? 4 : 5,
                'condition' => $c['condition'],
                'is_customs_cleared' => true,
                'is_credit_available' => true,
                'is_barter_available' => true,
                'vin' => 'WBA' . strtoupper(Str::random(14)),
                'seller_type' => $c['seller_type'],
                'contact_name' => $c['contact_name'],
                'contact_phone' => $c['contact_phone'],
                'contact_whatsapp' => $c['contact_phone'],
                'status' => CarStatus::Active,
                'is_vip' => $c['is_vip'],
                'is_premium' => $c['is_premium'],
                'is_urgent' => rand(0, 1) === 1,
                'view_count' => rand(150, 1200),
                'phone_view_count' => rand(12, 95),
                'favorite_count' => rand(5, 48),
                'published_at' => now()->subDays(rand(1, 14)),
            ]);

            // Save multiple realistic car images
            $carImages = $c['images'] ?? ['https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=1000&auto=format&fit=crop&q=80'];
            foreach ($carImages as $imgIdx => $imgUrl) {
                CarImage::create([
                    'car_id' => $car->id,
                    'image_path' => $imgUrl,
                    'thumbnail_url' => $imgUrl,
                    'is_main' => $imgIdx === 0,
                    'sort_order' => $imgIdx,
                ]);
            }

            // Attach 6-12 random features
            $featureIds = collect($savedFeatures)->pluck('id')->random(min(rand(6, 12), count($savedFeatures)));
            $car->features()->sync($featureIds);
        }

        // Update autosalons cars count
        foreach ($savedSalons as $salon) {
            $salon->update([
                'cars_count' => Car::where('autosalon_id', $salon->id)->count()
            ]);
        }
    }
}
