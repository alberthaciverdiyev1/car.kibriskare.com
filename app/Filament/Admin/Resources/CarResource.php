<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CarResource\Pages;
use App\Modules\Car\Enums\CarCondition;
use App\Modules\Car\Enums\CarDealType;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Enums\Drivetrain;
use App\Modules\Car\Enums\FuelType;
use App\Modules\Car\Enums\ImportOrigin;
use App\Modules\Car\Enums\PlateType;
use App\Modules\Car\Enums\SteeringWheel;
use App\Modules\Car\Enums\Transmission;
use App\Modules\Car\Enums\VehicleType;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\CarBodyType;
use App\Modules\Car\Models\CarBrand;
use App\Modules\Car\Models\CarModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Avtomobil Kataloqu';

    protected static ?string $navigationLabel = 'Bütün Avtomobil Elanları';

    protected static ?string $modelLabel = 'Avtomobil Elanı';

    protected static ?string $pluralModelLabel = 'Avtomobil Elanları';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('CarFormTabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Əsas Məlumatlar')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\Select::make('vehicle_type')
                                        ->label('Nəqliyyat Kateqoriyası')
                                        ->options(VehicleType::options())
                                        ->default(VehicleType::Car->value)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Set $set) {
                                            $set('brand_id', null);
                                            $set('model_id', null);
                                            $set('body_type_id', null);
                                        }),

                                    Forms\Components\Select::make('brand_id')
                                        ->label('Marka')
                                        ->options(function (Forms\Get $get) {
                                            $vehicleType = $get('vehicle_type') ?? VehicleType::Car->value;
                                            return CarBrand::forVehicleType($vehicleType)
                                                ->where('is_active', true)
                                                ->orderBy('name')
                                                ->pluck('name', 'id');
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('model_id', null)),

                                    Forms\Components\Select::make('model_id')
                                        ->label('Model')
                                        ->options(fn (Forms\Get $get) => $get('brand_id')
                                            ? CarModel::where('brand_id', $get('brand_id'))->where('is_active', true)->pluck('name', 'id')
                                             : []
                                        )
                                        ->searchable()
                                        ->required(),

                                    Forms\Components\Select::make('body_type_id')
                                        ->label(fn (Forms\Get $get) => $get('vehicle_type') === VehicleType::Motorcycle->value ? 'Motosiklet Növü' : 'Ban Növü')
                                        ->options(function (Forms\Get $get) {
                                            $vehicleType = $get('vehicle_type') ?? VehicleType::Car->value;
                                            $locale = app()->getLocale();
                                            return CarBodyType::forVehicleType($vehicleType)
                                                ->where('is_active', true)
                                                ->orderBy('sort_order')
                                                ->get()
                                                ->mapWithKeys(fn ($item) => [$item->id => $item->getTranslation('name', $locale) ?: $item->getTranslation('name', 'tr') ?: (is_array($item->name) ? reset($item->name) : $item->name)]);
                                        })
                                        ->searchable()
                                        ->preload(),
                                ]),

                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\Select::make('deal_type')
                                        ->label('Elan Növü')
                                        ->options(CarDealType::options())
                                        ->default(CarDealType::Sale->value)
                                        ->required(),

                                    Forms\Components\TextInput::make('year')
                                        ->label('Buraxılış İli')
                                        ->numeric()
                                        ->minValue(1950)
                                        ->maxValue((int)date('Y') + 1)
                                        ->default((int)date('Y'))
                                        ->required(),

                                    Forms\Components\TextInput::make('mileage')
                                        ->label('Yürüş (Kilometraj)')
                                        ->numeric()
                                        ->suffix('km')
                                        ->required(),

                                    Forms\Components\Select::make('condition')
                                        ->label('Vəziyyəti')
                                        ->options(CarCondition::options())
                                        ->default(CarCondition::Used->value)
                                        ->required(),
                                ]),

                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('price_gbp')
                                        ->label('Qiymət (GBP £)')
                                        ->numeric()
                                        ->prefix('£')
                                        ->required(),

                                    Forms\Components\TextInput::make('price_try')
                                        ->label('Qiymət (TRY ₺)')
                                        ->numeric()
                                        ->prefix('₺')
                                        ->nullable(),

                                    Forms\Components\TextInput::make('price_eur')
                                        ->label('Qiymət (EUR €)')
                                        ->numeric()
                                        ->prefix('€')
                                        ->nullable(),
                                ]),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText('Boş buraxıldıqda avtomatik yaradılacaq.'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Texniki Spesifikasiyalar')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\TextInput::make('engine_volume')
                                        ->label('Mühərrik Həcmi (cc)')
                                        ->placeholder('1995, 2487')
                                        ->numeric()
                                        ->suffix('sm³'),

                                    Forms\Components\TextInput::make('engine_power')
                                        ->label('Mühərrik Gücü (a.g.)')
                                        ->placeholder('190, 245')
                                        ->numeric()
                                        ->suffix('hp'),

                                    Forms\Components\Select::make('fuel_type')
                                        ->label('Yanacaq Növü')
                                        ->options(FuelType::options())
                                        ->default(FuelType::Petrol->value)
                                        ->required(),

                                    Forms\Components\Select::make('transmission')
                                        ->label('Sürətlər Qutusu')
                                        ->options(Transmission::options())
                                        ->default(Transmission::Automatic->value)
                                        ->required(),
                                ]),

                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\Select::make('plate_type')
                                        ->label('Plaka Növü')
                                        ->options(PlateType::options())
                                        ->default(PlateType::Kktc->value)
                                        ->required(),

                                    Forms\Components\Select::make('steering_wheel')
                                        ->label('Sükan İstiqaməti')
                                        ->options(SteeringWheel::options())
                                        ->default(SteeringWheel::Right->value)
                                        ->required(fn (Forms\Get $get) => $get('vehicle_type') !== VehicleType::Motorcycle->value)
                                        ->hidden(fn (Forms\Get $get) => $get('vehicle_type') === VehicleType::Motorcycle->value),

                                    Forms\Components\Select::make('drivetrain')
                                        ->label('Ötürücü')
                                        ->options(Drivetrain::options())
                                        ->nullable(),

                                    Forms\Components\TextInput::make('color')
                                        ->label('Rəng')
                                        ->placeholder('Qara, Ağ, Gümüşü, Mavi'),
                                ]),

                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\Select::make('import_origin')
                                        ->label('İthalat Menşei')
                                        ->options(ImportOrigin::options())
                                        ->nullable(),

                                    Forms\Components\DatePicker::make('road_tax_valid_until')
                                        ->label('Seyrüsefer Son Tarixi')
                                        ->nullable(),

                                    Forms\Components\DatePicker::make('inspection_valid_until')
                                        ->label('Muayene Son Tarixi')
                                        ->nullable(),

                                    Forms\Components\TextInput::make('video_url')
                                        ->label('Araç Videosu (YouTube URL)')
                                        ->url()
                                        ->maxLength(500),
                                ]),

                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('vin')
                                        ->label('VIN Kod')
                                        ->maxLength(50),

                                    Forms\Components\TextInput::make('doors')
                                        ->label('Qapı Sayı')
                                        ->numeric()
                                        ->default(4)
                                        ->hidden(fn (Forms\Get $get) => $get('vehicle_type') === VehicleType::Motorcycle->value),

                                    Forms\Components\TextInput::make('seats')
                                        ->label('Oturacaq Sayı')
                                        ->numeric()
                                        ->default(5),
                                ]),

                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\Toggle::make('is_customs_cleared')
                                        ->label('KKTC Plakalı / Gömrük ödənilib')
                                        ->default(true),

                                    Forms\Components\Toggle::make('title_deed_ready')
                                        ->label('Koçan / Devre Hazır')
                                        ->default(true),

                                    Forms\Components\Toggle::make('is_credit_available')
                                        ->label('Kredit / Taksit mümkündür')
                                        ->default(false),

                                    Forms\Components\Toggle::make('is_barter_available')
                                        ->label('Barter / Takas mümkündür')
                                        ->default(false),

                                    Forms\Components\Toggle::make('has_warranty')
                                        ->label('Zəmanəti var')
                                        ->default(false),

                                    Forms\Components\Toggle::make('is_negotiable')
                                        ->label('Razılaşma payı var')
                                        ->default(false),

                                    Forms\Components\Toggle::make('is_metallic')
                                        ->label('Metalik Rəng')
                                        ->default(false),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Ekspertiz & Hasar Vəziyyəti')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Toggle::make('is_heavy_damaged')
                                        ->label('Ağır Hasarlı / Pert Kayıtlı')
                                        ->default(false),

                                    Forms\Components\Toggle::make('has_tramer')
                                        ->label('Tramer / Hasar Kaydı Var')
                                        ->default(false)
                                        ->live(),

                                    Forms\Components\TextInput::make('tramer_amount')
                                        ->label('Tramer Tutarı')
                                        ->numeric()
                                        ->visible(fn (Forms\Get $get) => (bool)$get('has_tramer')),

                                    Forms\Components\Select::make('tramer_currency')
                                        ->label('Tramer Valyutası')
                                        ->options([
                                            'GBP' => 'GBP (£)',
                                            'TRY' => 'TRY (₺)',
                                            'EUR' => 'EUR (€)',
                                            'USD' => 'USD ($)',
                                        ])
                                        ->default('GBP')
                                        ->visible(fn (Forms\Get $get) => (bool)$get('has_tramer')),

                                    Forms\Components\FileUpload::make('inspection_pdf')
                                        ->label('Ekspertiz Raporu (PDF / Görsel)')
                                        ->disk('public')
                                        ->directory('cars/inspections')
                                        ->acceptedFileTypes(['application/pdf', 'image/*']),
                                ]),

                                Forms\Components\KeyValue::make('damage_parts')
                                    ->label('Kaporta Parça Durumları')
                                    ->keyLabel('Parça (hood, roof, trunk, door...)')
                                    ->valueLabel('Vəziyyət (original, painted, replaced)')
                                    ->helperText('Parçalar: hood, roof, trunk, front_bumper, rear_bumper, front_left_door, front_right_door, rear_left_door, rear_right_door, front_left_fender, front_right_fender, rear_left_fender, rear_right_fender'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Təchizat və Opsiyalar')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Forms\Components\CheckboxList::make('features')
                                    ->label('Avtomobilin Təchizatı və Komplektasiyası')
                                    ->relationship('features', 'name->tr')
                                    ->columns(3)
                                    ->bulkToggleable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Şəkillər və Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Repeater::make('images')
                                    ->relationship('images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image_path')
                                            ->label('Şəkil')
                                            ->image()
                                            ->directory('cars')
                                            ->required(),
                                        Forms\Components\Toggle::make('is_main')
                                            ->label('Əsas Şəkil')
                                            ->default(false),
                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Sıra')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(1)
                                    ->reorderable('sort_order')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Əlaqə və Təsvir')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('autosalon_id')
                                        ->label('Avtosalon (Əgər salona aiddirsə)')
                                        ->relationship('autosalon', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),

                                    Forms\Components\Select::make('city_id')
                                        ->label('Şəhər')
                                        ->relationship('city', 'name->tr')
                                        ->searchable()
                                        ->preload(),

                                    Forms\Components\TextInput::make('contact_name')
                                        ->label('Əlaqədar Şəxs')
                                        ->maxLength(100),
                                ]),

                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('contact_phone')
                                        ->label('Əlaqə Telefonu')
                                        ->tel(),
                                    Forms\Components\TextInput::make('contact_whatsapp')
                                        ->label('WhatsApp Nömrəsi')
                                        ->tel(),
                                ]),

                                Forms\Components\Textarea::make('description.tr')
                                    ->label('Elanın Təsviri (TR)')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('description.az')
                                    ->label('Elanın Təsviri (AZ)')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Moderasiya və Status')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('status')
                                        ->label('Elan Statusu')
                                        ->options(CarStatus::options())
                                        ->default(CarStatus::Active->value)
                                        ->required(),

                                    Forms\Components\Toggle::make('is_premium')
                                        ->label('Premium Elan')
                                        ->default(false),

                                    Forms\Components\Toggle::make('is_urgent')
                                        ->label('Önə Çək (Öne Çıkarılmış)')
                                        ->default(false),
                                ]),

                                Forms\Components\Textarea::make('rejection_reason')
                                    ->label('İmtina Səbəbi')
                                    ->placeholder('Elan qaydalara uyğun olmadıqda imtina səbəbini qeyd edin.')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('Şəkil')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->defaultImageUrl(asset('images/car-placeholder.svg')),

                Tables\Columns\TextColumn::make('display_title')
                    ->label('Avtomobil')
                    ->searchable(['brand.name', 'model.name', 'year'])
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Qiymət')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('price_gbp', $direction))
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('year')
                    ->label('İl')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('formatted_mileage')
                    ->label('Yürüş')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('mileage', $direction)),

                Tables\Columns\TextColumn::make('deal_type')
                    ->label('Növ')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof CarDealType ? $state->label() : $state),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof CarStatus ? $state->label() : $state)
                    ->color(fn ($state) => $state instanceof CarStatus ? $state->color() : 'gray'),

                Tables\Columns\IconColumn::make('is_premium')
                    ->label('Premium')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_urgent')
                    ->label('Önə Çək')
                    ->boolean(),

                Tables\Columns\TextColumn::make('city.name.tr')
                    ->label('Şəhər')
                    ->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vehicle_type')
                    ->label('Kateqoriya')
                    ->options(VehicleType::options()),

                Tables\Filters\SelectFilter::make('brand_id')
                    ->label('Marka')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(CarStatus::options()),

                Tables\Filters\SelectFilter::make('deal_type')
                    ->label('Elan Növü')
                    ->options(CarDealType::options()),

                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('Premium Elanlar'),

                Tables\Filters\TernaryFilter::make('is_urgent')
                    ->label('Önə Çəkilmiş Elanlar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
