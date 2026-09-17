<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CarBrandResource\Pages;
use App\Modules\Car\Models\CarBrand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CarBrandResource extends Resource
{
    protected static ?string $model = CarBrand::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Avtomobil Kataloqu';

    protected static ?string $navigationLabel = 'Markalar';

    protected static ?string $modelLabel = 'Marka';

    protected static ?string $pluralModelLabel = 'Markalar';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Marka Məlumatları')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Marka Adı')
                            ->placeholder('Məs: Mercedes-Benz, BMW, Toyota')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(120),

                        Forms\Components\TextInput::make('country')
                            ->label('İstehsalçı Ölkə')
                            ->placeholder('Məs: Almaniya, Yaponiya, ABŞ')
                            ->maxLength(100),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Marka Loqosu')
                            ->image()
                            ->directory('brands')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_popular')
                            ->label('Populyar Marka (Ana səhifədə önə çıxar)')
                            ->default(false),

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
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Loqo')
                    ->circular()
                    ->defaultImageUrl(asset('images/kibriskarelogo1.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Marka')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('country')
                    ->label('Ölkə')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('models_count')
                    ->counts('models')
                    ->label('Modellər')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('cars_count')
                    ->counts('cars')
                    ->label('Elanlar')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Populyar')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label('Populyar Markalar'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktivlik'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarBrands::route('/'),
            'create' => Pages\CreateCarBrand::route('/create'),
            'edit' => Pages\EditCarBrand::route('/{record}/edit'),
        ];
    }
}
