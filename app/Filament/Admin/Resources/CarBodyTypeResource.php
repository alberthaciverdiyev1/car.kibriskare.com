<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CarBodyTypeResource\Pages;
use App\Modules\Car\Models\CarBodyType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CarBodyTypeResource extends Resource
{
    protected static ?string $model = CarBodyType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Avtomobil Kataloqu';

    protected static ?string $navigationLabel = 'Ban Növləri';

    protected static ?string $modelLabel = 'Ban Növü';

    protected static ?string $pluralModelLabel = 'Ban Növləri';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ban Növü Adı (Çoxdilli)')
                    ->schema([
                        Forms\Components\TextInput::make('name.tr')
                            ->label('Ad (TR)')
                            ->placeholder('Məs: Sedan, SUV / Crossover')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('name.az')
                            ->label('Ad (AZ)')
                            ->placeholder('Məs: Sedan, SUV / Krossover')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.en')
                            ->label('Ad (EN)')
                            ->placeholder('Məs: Sedan, SUV')
                            ->nullable(),

                        Forms\Components\TextInput::make('name.ru')
                            ->label('Ad (RU)')
                            ->placeholder('Məs: Седан, Внедорожник')
                            ->nullable(),
                    ])->columns(4),

                Forms\Components\Section::make('Tənzimləmələr')
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(80),

                        Forms\Components\TextInput::make('icon')
                            ->label('İkon Kodu')
                            ->placeholder('car-sedan, car-suv, car-coupe'),

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
                    ->label('Ban Növü (TR)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name.az')
                    ->label('Ad (AZ)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('name.en')
                    ->label('Ad (EN)')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('cars_count')
                    ->counts('cars')
                    ->label('Elanlar')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarBodyTypes::route('/'),
            'create' => Pages\CreateCarBodyType::route('/create'),
            'edit' => Pages\EditCarBodyType::route('/{record}/edit'),
        ];
    }
}
