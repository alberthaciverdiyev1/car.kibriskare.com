<?php

namespace Database\Seeders;

use App\Modules\Shared\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Category: general
            [
                'category' => 'general',
                'sort_order' => 1,
                'is_active' => true,
                'question' => [
                    'tr' => 'araba.kibriskare.com nedir ve nasıl çalışır?',
                    'az' => 'araba.kibriskare.com nədir və necə işləyir?',
                    'en' => 'What is araba.kibriskare.com and how does it work?',
                    'ru' => 'Что такое araba.kibriskare.com и как это работает?',
                ],
                'answer' => [
                    'tr' => 'araba.kibriskare.com, Kuzey Kıbrıs’ta (KKTC) satılık ve günlük kiralık (rent a car) araç ilanları, güncel oto galerileri ve araç sahipleri için geliştirilmiş modern otomobil platformudur. Bireysel satıcılar ve kurumsal oto galerileri araç ilanlarını güvenle yayınlayabilir.',
                    'az' => 'araba.kibriskare.com Şimali Kiprdə (KKTC) satılıq və günlük kirayə (rent a car) avtomobil elanları, aktual avtosalonlar və avtomobil sahibləri üçün müasir elan platformasıdır. Fərdi satıcılar və avtosalonlar elanlarını yerləşdirə bilərlər.',
                    'en' => 'araba.kibriskare.com is a modern automotive marketplace in Northern Cyprus (TRNC) for buying, selling, and renting cars. Individual sellers and authorized auto salons can list vehicles securely.',
                    'ru' => 'araba.kibriskare.com — это современная автоплатформа на Северном Кипре (ТРСК) для покупки, продажи и посуточной аренды авто (rent a car). Частные продавцы и автосалоны могут безопасно размещать объявления.',
                ],
            ],
            [
                'category' => 'general',
                'sort_order' => 2,
                'is_active' => true,
                'question' => [
                    'tr' => 'İlanlara bakmak için üye olmak zorunlu mu?',
                    'az' => 'Elanlara baxmaq üçün qeydiyyatdan keçmək məcburidirmi?',
                    'en' => 'Is registration required to browse car listings?',
                    'ru' => 'Обязательна ли регистрация для просмотра объявлений?',
                ],
                'answer' => [
                    'tr' => 'Hayır, sitedeki tüm satılık ve kiralık araç ilanlarını incelemek, filtrelemek ve satıcılarla doğrudan iletişime geçmek tamamen ücretsiz ve üyeliksizdir. Ancak ilan eklemek ve favorilere kaydetmek için ücretsiz hesap açmanız önerilir.',
                    'az' => 'Xeyr, saytdakı bütün satılıq və kirayə avtomobil elanları ilə tanış olmaq, filtrləmək və satıcılarla birbaşa əlaqə saxlamaq tamamilə açıqdır və qeydiyyat tələb olunmur.',
                    'en' => 'No, browsing, filtering, and contacting car sellers on our platform is completely open and free without registration. Creating a free account is recommended to save favorites.',
                    'ru' => 'Нет, просмотр всех объявлений об авто, фильтрация и связь с продавцами полностью бесплатны и не требуют регистрации.',
                ],
            ],
            [
                'category' => 'general',
                'sort_order' => 3,
                'is_active' => true,
                'question' => [
                    'tr' => 'Platform hangi şehir ve bölgeleri kapsıyor?',
                    'az' => 'Platforma hansı şəhər və bölgələri əhatə edir?',
                    'en' => 'Which cities and regions does the platform cover?',
                    'ru' => 'Какие города и регионы охватывает платформа?',
                ],
                'answer' => [
                    'tr' => 'Platformumuz Girne, Lefkoşa, Gazimağusa, İskele, Güzelyurt ve Lefke başta olmak üzere Kuzey Kıbrıs’ın tüm şehirlerindeki satılık ve kiralık araçları kapsamaktadır.',
                    'az' => 'Platformamız Girnə, Lefkoşa, Qazimağusa, İskele, Gözəlyurd və Lefke daxil olmaqla Şimali Kiprin bütün şəhərlərindəki avtomobilləri əhatə edir.',
                    'en' => 'Our platform covers all cities and districts across Northern Cyprus, including Kyrenia, Nicosia, Famagusta, Iskele, Guzelyurt, and Lefke.',
                    'ru' => 'Наша платформа охватывает все города Северного Кипра: Гирне, Лефкоша, Газимагуса, Искеле, Гюзельюрт и Лефке.',
                ],
            ],

            // Category: listings
            [
                'category' => 'listings',
                'sort_order' => 4,
                'is_active' => true,
                'question' => [
                    'tr' => 'Sitede nasıl yeni araç ilanı verebilirim?',
                    'az' => 'Saytda necə yeni avtomobil elanı yerləşdirə bilərəm?',
                    'en' => 'How can I post a new vehicle listing?',
                    'ru' => 'Как подать объявление о продаже или аренде авто?',
                ],
                'answer' => [
                    'tr' => 'Sağ üst menüdeki "İlan Ver" butonuna tıklayarak formu açın. Araç markası, modeli, üretim yılı, kilometresi, yakıt ve vites türü, fiyatı ve fotoğraflarını ekleyerek ilanınızı onaya gönderebilirsiniz.',
                    'az' => 'Yuxarı menyudakı "Elan Yerləşdir" düyməsinə klikləyərək formanı açın. Avtomobilin markası, modeli, ili, yürüşü, yanacaq və sürətlər qutusu növünü, qiymətini və şəkillərini qeyd edib təsdiqə göndərə bilərsiniz.',
                    'en' => 'Click the "Post Ad" button in the menu. Fill in the car make, model, year, mileage, transmission, fuel type, price, and upload photos to submit for review.',
                    'ru' => 'Нажмите кнопку "Подать объявление". Укажите марку, модель, год выпуска, пробег, трансмиссию, тип топлива, цену и загрузите фотографии.',
                ],
            ],
            [
                'category' => 'listings',
                'sort_order' => 5,
                'is_active' => true,
                'question' => [
                    'tr' => 'Eklediğim ilan ne zaman sitede görünür?',
                    'az' => 'Əlavə etdiyim elan nə vaxt saytda aktiv olacaq?',
                    'en' => 'When will my vehicle listing go live?',
                    'ru' => 'Когда мое объявление появится на сайте?',
                ],
                'answer' => [
                    'tr' => 'İlanların gerçekliğini ve kalitesini güvence altına almak için ilanlar moderatör kontrolünden geçirilir. Kontrol süreci genellikle 15-30 dakika içinde tamamlanır ve ilan anında yayına alınır.',
                    'az' => 'Bütün yeni elanlar keyfiyyət və dəqiqlik baxımından moderator nəzarətindən keçir. Yoxlanış adətən 15-30 dəqiqə ərzində tamamlanır və elan yayına buraxılır.',
                    'en' => 'To ensure accuracy and quality, listings undergo moderator verification. This usually takes 15-30 minutes, after which your vehicle is published.',
                    'ru' => 'Все новые объявления проходят быструю проверку модератором (обычно 15–30 минут), после чего сразу публикуются.',
                ],
            ],
            [
                'category' => 'listings',
                'sort_order' => 6,
                'is_active' => true,
                'question' => [
                    'tr' => 'Araç fotoğrafları için kurallar ve limitler nelerdir?',
                    'az' => 'Avtomobil şəkilləri ilə bağlı hansı qaydalar var?',
                    'en' => 'What are the photo guidelines and limits for vehicles?',
                    'ru' => 'Каковы правила для фотографий автомобилей?',
                ],
                'answer' => [
                    'tr' => 'İlanınıza en az 1, en fazla 20 adet net ve kaliteli araç fotoğrafı ekleyebilirsiniz (JPG, PNG, WebP). İlk yüklediğiniz görsel kapak fotoğrafı olarak kullanılır.',
                    'az' => 'Elana ən az 1, ən çox 20 ədəd aydın və keyfiyyətli avtomobil fotosu əlavə edə bilərsiniz (JPG, PNG, WebP). İlk şəkil əsas üzlük fotosu təyin olunur.',
                    'en' => 'You can upload up to 20 clear car photos (JPG, PNG, WebP). The first uploaded image serves as the main cover photo.',
                    'ru' => 'Вы можете загрузить до 20 качественных фотографий автомобиля (JPG, PNG, WebP). Первое фото будет главной обложкой.',
                ],
            ],

            // Category: payments
            [
                'category' => 'payments',
                'sort_order' => 7,
                'is_active' => true,
                'question' => [
                    'tr' => 'Standart araç ilanı vermek ücretli mi?',
                    'az' => 'Standart avtomobil elanı yerləşdirmək ödənişlidirmi?',
                    'en' => 'Is posting a standard car listing free?',
                    'ru' => 'Бесплатно ли размещение стандартного объявления?',
                ],
                'answer' => [
                    'tr' => 'Hayır, platformumuzda standart araç ilanı vermek bireysel satıcılar için tamamen ücretsizdir.',
                    'az' => 'Xeyr, platformamızda fərdi satıcılar üçün standart avtomobil elanı yerləşdirmək tamamilə pulsuzdur.',
                    'en' => 'No, posting standard vehicle listings for individual sellers is completely free of charge.',
                    'ru' => 'Нет, стандартное размещение объявлений об авто для частных продавцов абсолютно бесплатно.',
                ],
            ],
            [
                'category' => 'payments',
                'sort_order' => 8,
                'is_active' => true,
                'question' => [
                    'tr' => 'Premium ve İlanı Öne Çıkarma hizmeti nedir?',
                    'az' => 'Premium və Elanı İrəli Çəkmə xidməti nədir?',
                    'en' => 'What are Premium and Bump-Up listing services?',
                    'ru' => 'Что такое услуги Премиум и Поднятие объявления?',
                ],
                'answer' => [
                    'tr' => 'İlanınızı Premium statüsüne yükselterek veya öne çıkararak ana sayfada ve arama sonuçlarının en üst sıralarında özel rozet ile sergileyebilir, aracınızı çok daha hızlı satabilirsiniz.',
                    'az' => 'Elanınızı Premium etməklə və ya irəli çəkməklə ana səhifədə və axtarış siyahısının ən üstündə nümayiş etdirə və avtomobilinizi qat-qat sürətlə sata bilərsiniz.',
                    'en' => 'Promoting or making your listing Premium places it at the top of search results and homepage with special badges, maximizing buyer inquiries.',
                    'ru' => 'Услуги Премиум и поднятия выводят автомобиль на первые позиции в поиске и на главной странице, ускоряя продажу.',
                ],
            ],
            [
                'category' => 'payments',
                'sort_order' => 9,
                'is_active' => true,
                'question' => [
                    'tr' => 'Fiyatlar hangi para birimlerinde görüntülenebilir?',
                    'az' => 'Qiymətlər hansı valyutalarda göstərilir?',
                    'en' => 'Which currencies are supported on the platform?',
                    'ru' => 'Какие валюты поддерживаются на платформе?',
                ],
                'answer' => [
                    'tr' => 'İlanlar İngiliz Sterlini (GBP - £), Euro (EUR - €), Amerikan Doları (USD - $) ve Türk Lirası (TL - ₺) para birimlerinde görüntülenebilir. Üst bardaki kur seçiciden anlık çevrim yapabilirsiniz.',
                    'az' => 'Qiymətlər İngilis Funtu (GBP - £), Avro (EUR - €), ABŞ Dolları (USD - $) və Türk Lirəsi (TL - ₺) ilə göstərilir və anlıq məzənnə ilə avtomatik çevrilir.',
                    'en' => 'Vehicle prices can be viewed in British Pounds (GBP £), Euros (EUR €), US Dollars (USD $), and Turkish Lira (TRY ₺) with live conversion.',
                    'ru' => 'Цены отображаются и конвертируются в фунтах стерлингов (GBP £), евро (EUR €), долларах США (USD $) и турецких лирах (TRY ₺).',
                ],
            ],

            // Category: safety
            [
                'category' => 'safety',
                'sort_order' => 10,
                'is_active' => true,
                'question' => [
                    'tr' => 'Araç alırken veya kiralarken nelere dikkat etmeliyim?',
                    'az' => 'Avtomobil alarkən və ya kirayələyərkən nəyə diqqət etməliyəm?',
                    'en' => 'What should I look out for when buying or renting a car?',
                    'ru' => 'На что обратить внимание при покупке или аренде авто?',
                ],
                'answer' => [
                    'tr' => 'Aracı satın almadan önce mutlaka yetkili bir serviste veya oto ekspertizde kontrol ettirmeniz, ruhsat ve koçan belgelerini doğrulamanız ve kesinlikle görmediğiniz bir araç için kapora göndermemeniz önerilir.',
                    'az' => 'Avtomobili almazdan əvvəl servisdə yoxlatdırmağınız, sənədlərini dəqiqləşdirməyiniz və görmədiyiniz avtomobil üçün beh göndərməməyiniz tövsiyə olunur.',
                    'en' => 'Always inspect the vehicle at a certified mechanic before purchasing, verify the title documents, and never transfer down payments without seeing the vehicle in person.',
                    'ru' => 'Обязательно проводите диагностику в автосервисе перед покупкой, проверяйте документы и никогда не переводите задаток, не осмотрев автомобиль лично.',
                ],
            ],
            [
                'category' => 'safety',
                'sort_order' => 11,
                'is_active' => true,
                'question' => [
                    'tr' => 'Şüpheli veya yanıltıcı bir ilan görürsem ne yapmalıyım?',
                    'az' => 'Şübhəli və ya yalan məlumatlı elan görsəm nə etməliyəm?',
                    'en' => 'What should I do if I encounter a suspicious vehicle listing?',
                    'ru' => 'Что делать при обнаружении подозрительного объявления?',
                ],
                'answer' => [
                    'tr' => 'İlan sayfasındaki "Şikayet Et" butonunu veya İletişim sayfamızı kullanarak bize anında bildirebilirsiniz. Moderasyon ekibimiz ilanı derhal incelemeye alır.',
                    'az' => 'Elan səhifəsindəki "Şikayət et" düyməsi və ya Əlaqə bölməmiz vasitəsilə dərhal məlumat verə bilərsiniz. Nəzarət komandamız elanı dərhal araşdırır.',
                    'en' => 'Use the report action on the listing page or contact our support team. Our moderation staff reviews all reports promptly.',
                    'ru' => 'Воспользуйтесь кнопкой "Пожаловаться" на странице объявления или свяжитесь с поддержкой. Мы оперативно проверим объявление.',
                ],
            ],
        ];

        Faq::truncate();

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }
    }
}
