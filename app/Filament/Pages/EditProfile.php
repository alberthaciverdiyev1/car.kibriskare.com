<?php

namespace App\Filament\Pages;

use App\Modules\Shared\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Profilim';

    public function getTitle(): string | Htmlable
    {
        return __('panel.profile');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.profile');
    }

    public static function getSlug(): string
    {
        return static::$slug ?? 'profile';
    }

    /**
     * İstifadəçinin rolunu təyin edir (admin / avtosalon / fərdi).
     */
    protected function getRoleLabel(): string
    {
        $user = $this->getUser();

        return match (true) {
            $user->email === User::ADMIN_EMAIL => 'Admin (Super Administrator)',
            $user->isAutosalonOwner() => 'Avtosalon Sahibi — ' . ($user->tenantAutosalon()?->name ?? 'Avtosalon'),
            default => 'Fərdi İstifadəçi',
        };
    }

    /**
     * Əsas profil formuna əlavə komponentlər:
     * - Əgər Avtosalon Sahibidirsə: Avtosalon Məlumatlarını görür və redaktə edir.
     */
    protected function getAdditionalFormComponents(): array
    {
        $user = $this->getUser();

        $components = [
            Placeholder::make('role_summary')
                ->label('Platforma Rolu')
                ->content(new HtmlString(
                    '<span class="inline-flex items-center gap-1 rounded-full bg-orange-100 text-orange-700 text-xs font-medium px-3 py-1">' .
                    e($this->getRoleLabel()) .
                    '</span>'
                )),
        ];

        // Əgər Avtosalon Sahibidirsə -> Avtosalon Məlumatları göstərilir
        if ($user->isAutosalonOwner() && $user->tenantAutosalon()) {
            $components[] = Section::make('Avtosalon Məlumatları')
                ->description('Veb saytında və elanlarınızda görünən rəsmi avtosalon detalları.')
                ->schema([
                    TextInput::make('autosalon.name')
                        ->label('Avtosalonun Adı')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('autosalon.description.tr')
                        ->label('Haqqında Ətraflı Məlumat')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Avtosalonunuz haqqında ətraflı məlumat.'),

                    FileUpload::make('autosalon.logo')
                        ->label('Salon Loqosu')
                        ->image()
                        ->imageEditor()
                        ->directory('salons/logos')
                        ->visibility('public')
                        ->columnSpan(1),

                    FileUpload::make('autosalon.banner')
                        ->label('Banner Şəkli')
                        ->image()
                        ->imageEditor()
                        ->directory('salons/banners')
                        ->visibility('public')
                        ->columnSpan(1),

                    TextInput::make('autosalon.phone')
                        ->label('Telefon Nömrəsi')
                        ->tel()
                        ->required(),

                    TextInput::make('autosalon.whatsapp')
                        ->label('WhatsApp Nömrəsi')
                        ->tel()
                        ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                    TextInput::make('autosalon.email')
                        ->label('Rəsmi E-poçt')
                        ->email(),

                    TextInput::make('autosalon.website')
                        ->label('Vebsayt')
                        ->url()
                        ->placeholder('https://...'),

                    TextInput::make('autosalon.working_hours')
                        ->label('İş Saatları')
                        ->placeholder('09:00 - 18:30'),

                    TextInput::make('autosalon.address')
                        ->label('Ofis / Salon Ünvanı')
                        ->maxLength(255),
                ])->columns(2);
        }

        return $components;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        ...$this->getAdditionalFormComponents(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getUser();

        if ($user->isAutosalonOwner() && $user->tenantAutosalon()) {
            $salon = $user->tenantAutosalon();
            $data['autosalon'] = [
                'name' => $salon->name,
                'description' => $salon->description,
                'logo' => $salon->logo,
                'banner' => $salon->banner,
                'phone' => $salon->phone,
                'whatsapp' => $salon->whatsapp,
                'email' => $salon->email,
                'website' => $salon->website,
                'working_hours' => $salon->working_hours,
                'address' => $salon->address,
            ];
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $autosalonData = $data['autosalon'] ?? null;
        unset($data['autosalon']);

        $record->update($data);

        if ($record->isAutosalonOwner() && $autosalonData !== null && $record->tenantAutosalon()) {
            $record->tenantAutosalon()->update($autosalonData);
        }

        try {
            cache()->flush();
        } catch (\Throwable $e) {
            // ignore
        }

        return $record;
    }

    public function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => $this->getMaxWidth(),
        ];
    }
}
