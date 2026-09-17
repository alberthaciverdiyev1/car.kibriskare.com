<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AutosalonResource\Pages;
use App\Modules\Car\Models\Autosalon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AutosalonResource extends Resource
{
    protected static ?string $model = Autosalon::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Avtomobil Kataloqu';

    protected static ?string $navigationLabel = 'Avtosalonlar və Dilerlər';

    protected static ?string $modelLabel = 'Avtosalon';

    protected static ?string $pluralModelLabel = 'Avtosalonlar və Dilerlər';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('SalonTabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Əsas Məlumatlar')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Salon / Şirkət Adı')
                                    ->placeholder('Məs: Kıbrıs Motors Galeri')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Select::make('user_id')
                                    ->label('Məsul İstifadəçi (Hesab)')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Forms\Components\Select::make('city_id')
                                    ->label('Şəhər')
                                    ->relationship('city', 'name->tr')
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('working_hours')
                                    ->label('İş Saatları')
                                    ->placeholder('09:00 - 18:30 (B.e - Şənbə)'),

                                Forms\Components\TextInput::make('rating')
                                    ->label('Reytinq')
                                    ->numeric()
                                    ->default(5.00),

                                Forms\Components\Toggle::make('is_verified')
                                    ->label('Rəsmi Təsdiqlənmiş Diler')
                                    ->default(true),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktiv')
                                    ->default(true),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Əlaqə və Ünvan')
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telefon Nömrəsi')
                                    ->tel(),

                                Forms\Components\TextInput::make('whatsapp')
                                    ->label('WhatsApp Nömrəsi')
                                    ->tel(),

                                Forms\Components\TextInput::make('email')
                                    ->label('E-poçt')
                                    ->email(),

                                Forms\Components\TextInput::make('website')
                                    ->label('Vebsayt')
                                    ->url(),

                                Forms\Components\Textarea::make('address')
                                    ->label('Ünvan')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Media və Təsvir')
                            ->schema([
                                Forms\Components\FileUpload::make('logo')
                                    ->label('Salon Loqosu')
                                    ->image()
                                    ->directory('salons/logos'),

                                Forms\Components\FileUpload::make('banner')
                                    ->label('Salon Banneri')
                                    ->image()
                                    ->directory('salons/banners'),

                                Forms\Components\RichEditor::make('description.tr')
                                    ->label('Haqqımızda Məlumat (TR)')
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('description.az')
                                    ->label('Haqqımızda Məlumat (AZ)')
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Loqo')
                    ->circular()
                    ->defaultImageUrl(asset('images/kibriskarelogo1.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Avtosalon')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('city.name.tr')
                    ->label('Şəhər')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cars_count')
                    ->counts('cars')
                    ->label('Elanlar')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Təsdiqlənmiş')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAutosalons::route('/'),
            'create' => Pages\CreateAutosalon::route('/create'),
            'edit' => Pages\EditAutosalon::route('/{record}/edit'),
        ];
    }
}
