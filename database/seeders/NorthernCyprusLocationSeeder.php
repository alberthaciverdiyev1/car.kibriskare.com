<?php

namespace Database\Seeders;

use App\Modules\Location\Enums\FilterKey;
use App\Modules\Location\Models\Amenity;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\Filter;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Property\Models\Property;
use App\Modules\Property\Models\PropertyImage;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Seeder;

class NorthernCyprusLocationSeeder extends Seeder
{
    /**
     * Run the database seeds for Northern Cyprus (KKTC) locations.
     *
     * Idempotent: updateOrCreate istifadə edir, təkrar işə salmaq təhlükəsizdir.
     */
    public function run(): void
    {
        // 1. Comprehensive Northern Cyprus Cities & Districts
        $locations = [
            [
                'name' => ['az' => 'Girne', 'tr' => 'Girne', 'en' => 'Kyrenia', 'ru' => 'Гирне (Кирения)'],
                'slug' => 'girne',
                'sort_order' => 1,
                'districts' => [
                    ['name' => ['az' => 'Girne Mərkəz', 'tr' => 'Girne Merkez', 'en' => 'Kyrenia Center', 'ru' => 'Центр Гирне'], 'slug' => 'girne-merkez'],
                    ['name' => ['az' => 'Alsancak', 'tr' => 'Alsancak', 'en' => 'Alsancak', 'ru' => 'Алсанджак'], 'slug' => 'alsancak'],
                    ['name' => ['az' => 'Lapta', 'tr' => 'Lapta', 'en' => 'Lapta', 'ru' => 'Лапта'], 'slug' => 'lapta'],
                    ['name' => ['az' => 'Çatalköy', 'tr' => 'Çatalköy', 'en' => 'Catalkoy', 'ru' => 'Чаталкой'], 'slug' => 'catalkoy'],
                    ['name' => ['az' => 'Esentepe', 'tr' => 'Esentepe', 'en' => 'Esentepe', 'ru' => 'Эсентепе'], 'slug' => 'esentepe'],
                    ['name' => ['az' => 'Karaoğlanoğlu', 'tr' => 'Karaoğlanoğlu', 'en' => 'Karaoglanoglu', 'ru' => 'Караогланоглу'], 'slug' => 'karaoglanoglu'],
                    ['name' => ['az' => 'Ozanköy', 'tr' => 'Ozanköy', 'en' => 'Ozankoy', 'ru' => 'Озанкой'], 'slug' => 'ozankoy'],
                    ['name' => ['az' => 'Beylerbeyi (Bellapais)', 'tr' => 'Beylerbeyi (Bellapais)', 'en' => 'Bellapais', 'ru' => 'Беллапаис'], 'slug' => 'bellapais'],
                    ['name' => ['az' => 'Karşıyaka', 'tr' => 'Karşıyaka', 'en' => 'Karsiyaka', 'ru' => 'Каршияка'], 'slug' => 'karsiyaka'],
                    ['name' => ['az' => 'Doğanköy', 'tr' => 'Doğanköy', 'en' => 'Dogankoy', 'ru' => 'Доганкой'], 'slug' => 'dogankoy'],
                    ['name' => ['az' => 'Zeytinlik', 'tr' => 'Zeytinlik', 'en' => 'Zeytinlik', 'ru' => 'Зейтинлик'], 'slug' => 'zeytinlik'],
                    ['name' => ['az' => 'Dikmen', 'tr' => 'Dikmen', 'en' => 'Dikmen', 'ru' => 'Дикмен'], 'slug' => 'dikmen'],
                    ['name' => ['az' => 'Bahçeli', 'tr' => 'Bahçeli', 'en' => 'Bahceli', 'ru' => 'Бахчели'], 'slug' => 'bahceli'],
                    ['name' => ['az' => 'Tatlısu', 'tr' => 'Tatlısu', 'en' => 'Tatlisu', 'ru' => 'Татлысу'], 'slug' => 'tatlisu'],
                ]
            ],
            [
                'name' => ['az' => 'Lefkoşa', 'tr' => 'Lefkoşa', 'en' => 'Nicosia', 'ru' => 'Лефкоша (Никосия)'],
                'slug' => 'lefkosa',
                'sort_order' => 2,
                'districts' => [
                    ['name' => ['az' => 'Lefkoşa Mərkəz', 'tr' => 'Lefkoşa Merkez', 'en' => 'Nicosia Center', 'ru' => 'Центр Лефкоша'], 'slug' => 'lefkosa-merkez'],
                    ['name' => ['az' => 'Gönyeli', 'tr' => 'Gönyeli', 'en' => 'Gonyeli', 'ru' => 'Гёньели'], 'slug' => 'gonyeli'],
                    ['name' => ['az' => 'Küçük Kaymaklı', 'tr' => 'Küçük Kaymaklı', 'en' => 'Kucuk Kaymakli', 'ru' => 'Кючюк Каймаклы'], 'slug' => 'kucuk-kaymakli'],
                    ['name' => ['az' => 'Ortaköy', 'tr' => 'Ortaköy', 'en' => 'Ortakoy', 'ru' => 'Ортакой'], 'slug' => 'ortakoy'],
                    ['name' => ['az' => 'Köşklüçiftlik / Kumsal', 'tr' => 'Köşklüçiftlik / Kumsal', 'en' => 'Kumsal', 'ru' => 'Кумсал'], 'slug' => 'kumsal'],
                    ['name' => ['az' => 'Marmara', 'tr' => 'Marmara', 'en' => 'Marmara', 'ru' => 'Мармара'], 'slug' => 'marmara'],
                    ['name' => ['az' => 'Yenişehir', 'tr' => 'Yenişehir', 'en' => 'Yenisehir', 'ru' => 'Енишехир'], 'slug' => 'yenisehir'],
                    ['name' => ['az' => 'Taşkınköy', 'tr' => 'Taşkınköy', 'en' => 'Taskinkoy', 'ru' => 'Ташкынкой'], 'slug' => 'taskinkoy'],
                    ['name' => ['az' => 'Hamitköy', 'tr' => 'Hamitköy', 'en' => 'Hamitkoy', 'ru' => 'Хамиткой'], 'slug' => 'hamitkoy'],
                    ['name' => ['az' => 'Değirmenlik', 'tr' => 'Değirmenlik', 'en' => 'Degirmenlik', 'ru' => 'Дегирменлик'], 'slug' => 'degirmenlik'],
                    ['name' => ['az' => 'Alayköy', 'tr' => 'Alayköy', 'en' => 'Alaykoy', 'ru' => 'Алайкой'], 'slug' => 'alaykoy'],
                    ['name' => ['az' => 'Haspolat', 'tr' => 'Haspolat', 'en' => 'Haspolat', 'ru' => 'Хасполат'], 'slug' => 'haspolat'],
                ]
            ],
            [
                'name' => ['az' => 'Gazimağusa', 'tr' => 'Gazimağusa', 'en' => 'Famagusta', 'ru' => 'Газимагуса (Фамагуста)'],
                'slug' => 'gazimagusa',
                'sort_order' => 3,
                'districts' => [
                    ['name' => ['az' => 'Gazimağusa Mərkəz', 'tr' => 'Gazimağusa Merkez', 'en' => 'Famagusta Center', 'ru' => 'Центр Фамагусты'], 'slug' => 'gazimagusa-merkez'],
                    ['name' => ['az' => 'Salamis / Yeni Boğaziçi', 'tr' => 'Salamis / Yeni Boğaziçi', 'en' => 'Yeni Bogazici', 'ru' => 'Ени Богазчи'], 'slug' => 'yeni-bogazici'],
                    ['name' => ['az' => 'Karakol', 'tr' => 'Karakol', 'en' => 'Karakol', 'ru' => 'Каракол'], 'slug' => 'karakol'],
                    ['name' => ['az' => 'Sakarya', 'tr' => 'Sakarya', 'en' => 'Sakarya', 'ru' => 'Сакарья'], 'slug' => 'sakarya'],
                    ['name' => ['az' => 'Gülseren', 'tr' => 'Gülseren', 'en' => 'Gulseren', 'ru' => 'Гюльсерен'], 'slug' => 'gulseren'],
                    ['name' => ['az' => 'Tuzla', 'tr' => 'Tuzla', 'en' => 'Tuzla', 'ru' => 'Тузла'], 'slug' => 'tuzla'],
                    ['name' => ['az' => 'Dumlupınar', 'tr' => 'Dumlupınar', 'en' => 'Dumlupinar', 'ru' => 'Думлупынар'], 'slug' => 'dumlupinar'],
                    ['name' => ['az' => 'Çanakkale', 'tr' => 'Çanakkale', 'en' => 'Canakkale', 'ru' => 'Чанаккале'], 'slug' => 'canakkale'],
                    ['name' => ['az' => 'Maraş', 'tr' => 'Maraş', 'en' => 'Maras', 'ru' => 'Мараш'], 'slug' => 'maras'],
                    ['name' => ['az' => 'Geçitkale', 'tr' => 'Geçitkale', 'en' => 'Gecitkale', 'ru' => 'Гечиткале'], 'slug' => 'gecitkale'],
                ]
            ],
            [
                'name' => ['az' => 'İskele', 'tr' => 'İskele', 'en' => 'Iskele (Trikomo)', 'ru' => 'Искеле (Трикомо)'],
                'slug' => 'iskele',
                'sort_order' => 4,
                'districts' => [
                    ['name' => ['az' => 'İskele Mərkəz', 'tr' => 'İskele Merkez', 'en' => 'Iskele Center', 'ru' => 'Центр Искеле'], 'slug' => 'iskele-merkez'],
                    ['name' => ['az' => 'Long Beach', 'tr' => 'Long Beach', 'en' => 'Long Beach', 'ru' => 'Лонг Бич'], 'slug' => 'long-beach'],
                    ['name' => ['az' => 'Boğaz', 'tr' => 'Boğaz', 'en' => 'Bogaz', 'ru' => 'Богаз'], 'slug' => 'bogaz'],
                    ['name' => ['az' => 'Bafra Turizm Bölgəsi', 'tr' => 'Bafra Turizm Bölgesi', 'en' => 'Bafra', 'ru' => 'Бафра'], 'slug' => 'bafra'],
                    ['name' => ['az' => 'Ötüken', 'tr' => 'Ötüken', 'en' => 'Otuken', 'ru' => 'Отукен'], 'slug' => 'otuken'],
                    ['name' => ['az' => 'Kumyalı', 'tr' => 'Kumyalı', 'en' => 'Kumyali', 'ru' => 'Кумьялы'], 'slug' => 'kumyali'],
                    ['name' => ['az' => 'Mehmetçik', 'tr' => 'Mehmetçik', 'en' => 'Mehmetcik', 'ru' => 'Мехметчик'], 'slug' => 'mehmetcik'],
                    ['name' => ['az' => 'Dipkarpaz', 'tr' => 'Dipkarpaz', 'en' => 'Dipkarpaz', 'ru' => 'Дипкарпаз'], 'slug' => 'dipkarpaz'],
                    ['name' => ['az' => 'Yenierenköy', 'tr' => 'Yenierenköy', 'en' => 'Yenierenkoy', 'ru' => 'Ениэренкёй'], 'slug' => 'yenierenkoy'],
                ]
            ],
            [
                'name' => ['az' => 'Güzelyurt', 'tr' => 'Güzelyurt', 'en' => 'Guzelyurt (Morphou)', 'ru' => 'Гюзельюрт (Морфу)'],
                'slug' => 'guzelyurt',
                'sort_order' => 5,
                'districts' => [
                    ['name' => ['az' => 'Güzelyurt Mərkəz', 'tr' => 'Güzelyurt Merkez', 'en' => 'Guzelyurt Center', 'ru' => 'Центр Гюзельюрт'], 'slug' => 'guzelyurt-merkez'],
                    ['name' => ['az' => 'Kalkanlı (ODTÜ)', 'tr' => 'Kalkanlı (ODTÜ)', 'en' => 'Kalkanli', 'ru' => 'Калканлы'], 'slug' => 'kalkanli'],
                    ['name' => ['az' => 'Bostancı', 'tr' => 'Bostancı', 'en' => 'Bostanci', 'ru' => 'Бостанджи'], 'slug' => 'bostanci'],
                    ['name' => ['az' => 'Yayla', 'tr' => 'Yayla', 'en' => 'Yayla', 'ru' => 'Яйла'], 'slug' => 'yayla'],
                    ['name' => ['az' => 'Zümrütköy', 'tr' => 'Zümrütköy', 'en' => 'Zumrutkoy', 'ru' => 'Зюмрюткой'], 'slug' => 'zumrutkoy'],
                    ['name' => ['az' => 'Akçay', 'tr' => 'Akçay', 'en' => 'Akcay', 'ru' => 'Акчай'], 'slug' => 'akcay'],
                    ['name' => ['az' => 'Aydınköy', 'tr' => 'Aydınköy', 'en' => 'Aydinkoy', 'ru' => 'Айдынкой'], 'slug' => 'aydinkoy'],
                ]
            ],
            [
                'name' => ['az' => 'Lefke', 'tr' => 'Lefke', 'en' => 'Lefka', 'ru' => 'Лефке'],
                'slug' => 'lefke',
                'sort_order' => 6,
                'districts' => [
                    ['name' => ['az' => 'Lefke Mərkəz', 'tr' => 'Lefke Merkez', 'en' => 'Lefke Center', 'ru' => 'Центр Лефке'], 'slug' => 'lefke-merkez'],
                    ['name' => ['az' => 'Gemikonağı (LAÜ)', 'tr' => 'Gemikonağı (LAÜ)', 'en' => 'Gemikonagi', 'ru' => 'Гемиконагы'], 'slug' => 'gemikonagi'],
                    ['name' => ['az' => 'Yedidalga', 'tr' => 'Yedidalga', 'en' => 'Yedidalga', 'ru' => 'Йедидалга'], 'slug' => 'yedidalga'],
                    ['name' => ['az' => 'Gaziveren', 'tr' => 'Gaziveren', 'en' => 'Gaziveren', 'ru' => 'Газиверен'], 'slug' => 'gaziveren'],
                    ['name' => ['az' => 'Bağlıköy', 'tr' => 'Bağlıköy', 'en' => 'Baglikoy', 'ru' => 'Баглыкой'], 'slug' => 'baglikoy'],
                    ['name' => ['az' => 'Yeşilyurt', 'tr' => 'Yeşilyurt', 'en' => 'Yesilyurt', 'ru' => 'Ешилюрт'], 'slug' => 'yesilyurt'],
                ]
            ]
        ];

        $createdCities = [];

        foreach ($locations as $loc) {
            $city = City::updateOrCreate(
                ['slug' => $loc['slug']],
                [
                    'name' => $loc['name'],
                    'sort_order' => $loc['sort_order'],
                    'is_active' => true,
                ]
            );
            $createdCities[] = $city;

            $dOrder = 1;
            foreach ($loc['districts'] as $dist) {
                District::updateOrCreate(
                    ['city_id' => $city->id, 'slug' => $dist['slug']],
                    [
                        'name' => $dist['name'],
                        'sort_order' => $dOrder++,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
