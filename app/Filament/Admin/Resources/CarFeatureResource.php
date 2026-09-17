<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CarFeatureResource\Pages;
use App\Modules\Car\Models\CarFeature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CarFeatureResource extends Resource
{
    protected static ?string $model = CarFeature::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Avtomobil Kataloqu';

    protected static ?string $navigationLabel = 'Təchizat və Opsiyalar';

    protected static ?string $modelLabel = 'Təchizat';

    protected static ?string $pluralModelLabel = 'Təchizat və Opsiyalar';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Təchizat Adı (Çoxdilli)')
                    ->schema([
                        Forms\Components\TextInput::make('name.tr')
                            ->label('Ad (TR)')
                            ->placeholder('Məs: Deri Koltuklar, Sunroof, 360 Kamera')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('name.az')
                            ->label('Ad (AZ)')
                            ->placeholder('Məs: Dəri Salon, Lyuk, 360 Kamera')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Ad (EN)')
                            ->placeholder('Məs: Leather Seats, Sunroof')
                            ->nullable(),
                    ])->columns(3),

                Forms\Components\Section::make('Tənzimləmələr')
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),

                        Forms\Components\Select::make('category')
                            ->label('Kateqoriya')
                            ->options([
                                'comfort' => 'Komfort və Rahatlıq',
                                'safety' => 'Təhlükəsizlik və Asistentlər',
                                'multimedia' => 'Multimedia və Audio',
                                'exterior' => 'Eksteryer və İşıqlandırma',
                                'interior' => 'İnteryer və Salon',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktiv')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('name.tr')
                    ->label('Təchizat (TR)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.az')
                    ->label('Ad (AZ)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kateqoriya')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'comfort' => 'Komfort',
                        'safety' => 'Təhlükəsizlik',
                        'multimedia' => 'Multimedia',
                        'exterior' => 'Eksteryer',
                        'interior' => 'İnteryer',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'comfort' => 'warning',
                        'safety' => 'danger',
                        'multimedia' => 'info',
                        'exterior' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('cars_count')
                    ->counts('cars')
                    ->label('İstifadə Olunan Elanlar')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kateqoriya')
                    ->options([
                        'comfort' => 'Komfort və Rahatlıq',
                        'safety' => 'Təhlükəsizlik',
                        'multimedia' => 'Multimedia',
                        'exterior' => 'Eksteryer',
                        'interior' => 'İnteryer',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarFeatures::route('/'),
            'create' => Pages\CreateCarFeature::route('/create'),
            'edit' => Pages\EditCarFeature::route('/{record}/edit'),
        ];
    }
}
