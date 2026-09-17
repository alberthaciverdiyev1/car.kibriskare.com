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

            // Safety
            ['slug' => 'abs-esp', 'category' => 'safety', 'name' => ['tr' => 'ABS / ESP / Fren Destek', 'en' => 'ABS / ESP', 'az' => 'ABS & ESP']],
            ['slug' => 'lane-assist', 'category' => 'safety', 'name' => ['tr' => 'Şerit Takip Asistanı', 'en' => 'Lane Keeping Assist', 'az' => 'Zolaq izləmə sensoru']],
            ['slug' => 'blind-spot', 'category' => 'safety', 'name' => ['tr' => 'Kör Nokta Uyarı Sistemi', 'en' => 'Blind Spot Monitor', 'az' => 'Kor zona xəbərdarlığı']],
            ['slug' => 'adaptive-cruise', 'category' => 'safety', 'name' => ['tr' => 'Adaptif Hız Sabitleyici (ACC)', 'en' => 'Adaptive Cruise Control', 'az' => 'Adaptiv Kruiz-kontrol']],
            ['slug' => 'park-sensors', 'category' => 'safety', 'name' => ['tr' => 'Ön ve Arka Park Sensörleri', 'en' => 'Front & Rear Parking Sensors', 'az' => 'Park radarları']],
            ['slug' => 'camera-360', 'category' => 'safety', 'name' => ['tr' => '360 Derece Çevre Görüş Kamerası', 'en' => '360° Surround Camera', 'az' => '360 dərəcə kamera']],
            ['slug' => 'rear-camera', 'category' => 'safety', 'name' => ['tr' => 'Geri Görüş Kamerası', 'en' => 'Rear View Camera', 'az' => 'Arxa görüntü kamerası']],

            // Multimedia
            ['slug' => 'apple-carplay', 'category' => 'multimedia', 'name' => ['tr' => 'Apple CarPlay & Android Auto', 'en' => 'Apple CarPlay & Android Auto', 'az' => 'Apple CarPlay & Android Auto']],
            ['slug' => 'navigation', 'category' => 'multimedia', 'name' => ['tr' => 'Dahili Navigasyon Sistemi', 'en' => 'Navigation System', 'az' => 'Naviqasiya']],
            ['slug' => 'premium-sound', 'category' => 'multimedia', 'name' => ['tr' => 'Premium Ses Sistemi (Burmester / Harman Kardon / Bose)', 'en' => 'Premium Sound System', 'az' => 'Premium səs sistemi']],
            ['slug' => 'wireless-charging', 'category' => 'multimedia', 'name' => ['tr' => 'Kablosuz Telefon Şarjı', 'en' => 'Wireless Phone Charging', 'az' => 'Simsiz şarj']],

            // Exterior
            ['slug' => 'led-headlights', 'category' => 'exterior', 'name' => ['tr' => 'LED / Matrix Farlar', 'en' => 'LED / Matrix Headlights', 'az' => 'LED faralar']],
            ['slug' => 'alloy-wheels', 'category' => 'exterior', 'name' => ['tr' => 'Alaşım Jantlar', 'en' => 'Alloy Wheels', 'az' => 'Yüngül lehimli disklər']],
            ['slug' => 'fog-lights', 'category' => 'exterior', 'name' => ['tr' => 'Sis Farları', 'en' => 'Fog Lights', 'az' => 'Duman əleyhinə işıqlar']],
        ];

        $savedFeatures = [];
        foreach ($features as $f) {
            $savedFeatures[$f['slug']] = CarFeature::updateOrCreate(['slug' => $f['slug']], $f);
        }

        // 3. Brands and Models (Popular in Cyprus)
        $brandsData = [
            'Toyota' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Corolla', 'Yaris', 'RAV4', 'C-HR', 'Camry', 'Hilux', 'Land Cruiser', 'Prius', 'Auris', 'Aqua', 'Vitz', 'Noah', 'Voxy']
            ],
            'Mercedes-Benz' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['C-Class', 'E-Class', 'S-Class', 'A-Class', 'CLA', 'GLA', 'GLC', 'GLE', 'G-Class', 'GLS', 'CLS', 'Vito']
            ],
            'BMW' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['1 Series', '2 Series', '3 Series', '4 Series', '5 Series', '7 Series', 'X1', 'X3', 'X5', 'X6', 'X7', 'M3', 'M5']
            ],
            'Nissan' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Qashqai', 'Juke', 'X-Trail', 'Micra', 'Navara', 'Note', 'Leaf', 'Patrol', 'Tiida', 'Dualis']
            ],
            'Honda' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Civic', 'CR-V', 'HR-V', 'Jazz / Fit', 'Accord', 'Vezel', 'Freed', 'Stepwgn']
            ],
            'Volkswagen' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['Golf', 'Passat', 'Polo', 'Tiguan', 'T-Roc', 'Touareg', 'Arteon', 'Amarok', 'Transporter', 'Caddy']
            ],
            'Ford' => [
                'country' => 'USA', 'is_popular' => true,
                'models' => ['Focus', 'Fiesta', 'Kuga', 'Puma', 'Ranger', 'Mustang', 'Transit', 'Mondeo']
            ],
            'Audi' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron']
            ],
            'Hyundai' => [
                'country' => 'South Korea', 'is_popular' => true,
                'models' => ['Tucson', 'i20', 'i30', 'Elantra', 'Santa Fe', 'Kona', 'Bayon', 'Ioniq 5']
            ],
            'Kia' => [
                'country' => 'South Korea', 'is_popular' => true,
                'models' => ['Sportage', 'Rio', 'Ceed', 'Sorento', 'Stonic', 'Picanto', 'Niro', 'EV6']
            ],
            'Mazda' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Mazda 2', 'Mazda 3', 'Mazda 6', 'CX-3', 'CX-5', 'CX-30', 'MX-5']
            ],
            'Land Rover' => [
                'country' => 'UK', 'is_popular' => true,
                'models' => ['Range Rover', 'Range Rover Sport', 'Range Rover Evoque', 'Range Rover Velar', 'Defender', 'Discovery', 'Discovery Sport']
            ],
            'Porsche' => [
                'country' => 'Germany', 'is_popular' => true,
                'models' => ['Cayenne', 'Macan', 'Panamera', '911', 'Taycan', 'Boxster', 'Cayman']
            ],
            'Suzuki' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['Swift', 'Vitara', 'Jimny', 'S-Cross', 'Baleno', 'Ignis']
            ],
            'Mitsubishi' => [
                'country' => 'Japan', 'is_popular' => true,
                'models' => ['L200', 'Outlander', 'Pajero', 'ASX', 'Eclipse Cross', 'Mirage / Space Star']
            ],
            'Renault' => [
                'country' => 'France', 'is_popular' => false,
                'models' => ['Clio', 'Megane', 'Captur', 'Kadjar', 'Austral', 'Trafic']
            ],
            'Peugeot' => [
                'country' => 'France', 'is_popular' => false,
                'models' => ['208', '308', '2008', '3008', '5008', '508']
            ],
            'Volvo' => [
                'country' => 'Sweden', 'is_popular' => false,
                'models' => ['XC40', 'XC60', 'XC90', 'S60', 'S90', 'V60', 'V90']
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

        // 4. Sample Autosalons in Cyprus
        $lefkosa = City::where('name->tr', 'Lefkoşa')->orWhere('name->en', 'Nicosia')->first() ?? City::first();
        $girne = City::where('name->tr', 'Girne')->orWhere('name->en', 'Kyrenia')->first() ?? City::first();
        $magusa = City::where('name->tr', 'Gazimağusa')->orWhere('name->en', 'Famagusta')->first() ?? City::first();

        $admin = User::first();

        $salons = [
            [
                'name' => 'Kıbrıs Motors Galeri',
                'slug' => 'kibris-motors-galeri',
                'phone' => '+90 533 888 11 22',
                'whatsapp' => '+90 533 888 11 22',
                'email' => 'info@kibrismotors.com',
                'city_id' => $lefkosa?->id,
                'address' => 'Dr. Fazıl Küçük Bulvarı, Lefkoşa',
                'working_hours' => '09:00 - 18:30 (B.e - Şənbə)',
                'is_verified' => true,
                'rating' => 4.9,
            ],
            [
                'name' => 'Girne Auto Center',
                'slug' => 'girne-auto-center',
                'phone' => '+90 542 855 33 44',
                'whatsapp' => '+90 542 855 33 44',
                'email' => 'sales@girneautocenter.com',
                'city_id' => $girne?->id,
                'address' => 'Karaoğlanoğlu Caddesi, Girne',
                'working_hours' => '08:30 - 19:00',
                'is_verified' => true,
                'rating' => 4.8,
            ],
            [
                'name' => 'Mağusa Rent a Car & Auto',
                'slug' => 'magusa-rent-a-car-auto',
                'phone' => '+90 533 870 55 66',
                'whatsapp' => '+90 533 870 55 66',
                'email' => 'info@magusaauto.com',
                'city_id' => $magusa?->id,
                'address' => 'İsmet İnönü Bulvarı, Gazimağusa',
                'working_hours' => '24/7 Açıq',
                'is_verified' => true,
                'rating' => 4.7,
            ],
        ];

        $savedSalons = [];
        foreach ($salons as $s) {
            $savedSalons[] = Autosalon::updateOrCreate(['slug' => $s['slug']], array_merge($s, ['user_id' => $admin?->id]));
        }

        // 5. Realistic Sample Car Listings
        $carsData = [
            [
                'brand' => 'Mercedes-Benz', 'model' => 'E-Class', 'body' => 'sedan',
                'year' => 2021, 'mileage' => 38000, 'engine_volume' => 1950, 'engine_power' => 194,
                'fuel' => FuelType::Diesel, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::RearWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Qara Metalik', 'is_metallic' => true,
                'price_gbp' => 34500, 'price_try' => 1550000, 'price_eur' => 40500, 'price_usd' => 44000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 0,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Ahmet Bey', 'contact_phone' => '+90 533 888 11 22',
                'title' => ['tr' => '2021 Mercedes-Benz E 220 d AMG Line - Sağ Direksiyon', 'en' => '2021 Mercedes-Benz E 220 d AMG Line - Right Hand Drive', 'az' => '2021 Mercedes-Benz E 220 d AMG Line - Sağ Sükan'],
                'description' => ['tr' => 'İngiltere ithal, ilk sahibinden, hatasız ve boyasız. Tüm bakımları yetkili serviste yapılmıştır. Panoramik cam tavan, Burmester ses sistemi, 360 kamera, aktif şerit takip.', 'az' => 'İngiltərədən gətirilib, birinci sahibindən, vuruqsuz və rəngsiz. Bütün qulluqları rəsmi servisdə olunub. Panoram lyuk, Burmester səs sistemi, 360 kamera.'],
            ],
            [
                'brand' => 'Toyota', 'model' => 'RAV4', 'body' => 'suv',
                'year' => 2022, 'mileage' => 24000, 'engine_volume' => 2487, 'engine_power' => 218,
                'fuel' => FuelType::Hybrid, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Ağ Mirvari', 'is_metallic' => true,
                'price_gbp' => 28900, 'price_try' => 1300000, 'price_eur' => 34000, 'price_usd' => 37000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 1,
                'city_id' => $girne?->id, 'contact_name' => 'Mustafa Bey', 'contact_phone' => '+90 542 855 33 44',
                'title' => ['tr' => '2022 Toyota RAV4 2.5 Hybrid AWD Dynamic - Çok Temiz', 'en' => '2022 Toyota RAV4 2.5 Hybrid AWD Dynamic', 'az' => '2022 Toyota RAV4 2.5 Hybrid AWD Dynamic'],
                'description' => ['tr' => 'Japonya çıkışlı, düşük kilometreli, yakıt tasarruflu mükemmel SUV. Deri koltuk, elektrikli bagaj, CarPlay.', 'az' => 'Yaponiyadan gətirilib, az yürüşlü, çox qənaətcil hibrid SUV. Dəri oturacaqlar, elektrikli baqaj, Apple CarPlay.'],
            ],
            [
                'brand' => 'BMW', 'model' => '3 Series', 'body' => 'sedan',
                'year' => 2020, 'mileage' => 49000, 'engine_volume' => 1998, 'engine_power' => 184,
                'fuel' => FuelType::Petrol, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::RearWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Mavi / Portimao Blue', 'is_metallic' => true,
                'price_gbp' => 26500, 'price_try' => 1190000, 'price_eur' => 31000, 'price_usd' => 33900,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => true, 'seller_type' => 'owner', 'salon_idx' => null,
                'city_id' => $girne?->id, 'contact_name' => 'Kemal Özcan', 'contact_phone' => '+90 533 844 77 99',
                'title' => ['tr' => '2020 BMW 320i M Sport (G20) - Sahibinden', 'en' => '2020 BMW 320i M Sport (G20)', 'az' => '2020 BMW 320i M Sport (G20)'],
                'description' => ['tr' => 'Sahibinden temiz kullanılmış M Sport paket. Live Cockpit Professional, Harman Kardon ses, 19 inç M jantlar.', 'az' => 'Şəxsi maşınımdır, M Sport paket. Harman Kardon, 19 diskler.'],
            ],
            [
                'brand' => 'Nissan', 'model' => 'Note', 'body' => 'hatchback',
                'year' => 2023, 'mileage' => 12000, 'engine_volume' => 1198, 'engine_power' => 116,
                'fuel' => FuelType::Hybrid, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::FrontWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Gümüşü Metalik', 'is_metallic' => true,
                'price_gbp' => 35, 'price_try' => 1500, 'price_eur' => 40, 'price_usd' => 45, // Daily rent price!
                'deal_type' => CarDealType::RentDaily, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => false, 'seller_type' => 'dealer', 'salon_idx' => 2,
                'city_id' => $magusa?->id, 'contact_name' => 'Mağusa Rent', 'contact_phone' => '+90 533 870 55 66',
                'title' => ['tr' => '2023 Nissan Note e-Power - Günlük Kiralık (Rent a Car)', 'en' => '2023 Nissan Note e-Power - Daily Rental', 'az' => '2023 Nissan Note e-Power - Günlük Kirayə (Rent a Car)'],
                'description' => ['tr' => 'Kıbrıs genelinde havalimanı teslimat imkanı. Çok ekonomik e-Power hibrit motor, klimalı, sigortalı.', 'az' => 'Ercan Hava limanında təhvil verilmə imkanı. Çox ekonom hibrid, kaskolu.'],
            ],
            [
                'brand' => 'Land Rover', 'model' => 'Range Rover Sport', 'body' => 'suv',
                'year' => 2022, 'mileage' => 31000, 'engine_volume' => 2996, 'engine_power' => 300,
                'fuel' => FuelType::Diesel, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::AllWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Qara', 'is_metallic' => true,
                'price_gbp' => 68500, 'price_try' => 3080000, 'price_eur' => 80000, 'price_usd' => 87000,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => true, 'is_premium' => true, 'seller_type' => 'dealer', 'salon_idx' => 0,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Ahmet Bey', 'contact_phone' => '+90 533 888 11 22',
                'title' => ['tr' => '2022 Range Rover Sport D300 Dynamic SE - Full Opsiyon', 'en' => '2022 Range Rover Sport D300 Dynamic SE', 'az' => '2022 Range Rover Sport D300 Dynamic SE'],
                'description' => ['tr' => 'Kusursuz kondisyonda, Meridian ses sistemi, havalı süspansiyon, soft-close kapılar.', 'az' => 'Əla vəziyyətdə, Meridian səs sistemi, pnevma asqı, qapı servosorguları.'],
            ],
            [
                'brand' => 'Toyota', 'model' => 'Yaris', 'body' => 'hatchback',
                'year' => 2021, 'mileage' => 32000, 'engine_volume' => 1490, 'engine_power' => 116,
                'fuel' => FuelType::Hybrid, 'transmission' => Transmission::Automatic, 'drivetrain' => Drivetrain::FrontWheel,
                'steering' => SteeringWheel::Right, 'color' => 'Qırmızı', 'is_metallic' => true,
                'price_gbp' => 14800, 'price_try' => 665000, 'price_eur' => 17400, 'price_usd' => 18900,
                'deal_type' => CarDealType::Sale, 'condition' => CarCondition::Used,
                'is_vip' => false, 'is_premium' => false, 'seller_type' => 'owner', 'salon_idx' => null,
                'city_id' => $lefkosa?->id, 'contact_name' => 'Selin Kaya', 'contact_phone' => '+90 542 888 99 00',
                'title' => ['tr' => '2021 Toyota Yaris 1.5 Hybrid Icon - Şehir İçi Ekonomik', 'en' => '2021 Toyota Yaris 1.5 Hybrid', 'az' => '2021 Toyota Yaris 1.5 Hybrid'],
                'description' => ['tr' => 'Şehir içi 3.8 litre yakıt tüketimi, geri görüş kamerası, dokunmatik ekran, şerit takip.', 'az' => 'Şəhərdə 3.8 litr yanacaq sərfiyyatı, arxa kamera, sensor ekran.'],
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
                'doors' => 4,
                'seats' => 5,
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
                'is_urgent' => false,
                'view_count' => rand(80, 500),
                'published_at' => now()->subDays(rand(1, 10)),
            ]);

            // Add placeholder images
            CarImage::create([
                'car_id' => $car->id,
                'image_path' => 'images/box-house.jpg',
                'thumbnail_url' => asset('images/box-house.jpg'),
                'is_main' => true,
                'sort_order' => 0,
            ]);

            // Attach random features
            $featureIds = collect($savedFeatures)->pluck('id')->random(min(8, count($savedFeatures)));
            $car->features()->sync($featureIds);
        }
    }
}
