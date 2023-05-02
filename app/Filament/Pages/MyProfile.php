<?php

namespace App\Filament\Pages;

use App\Models\FakturEkspedisi;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use JeffGreco13\FilamentBreezy\FilamentBreezy;
use JeffGreco13\FilamentBreezy\Traits\HasBreezyTwoFactor;

class MyProfile extends Page
{
    use HasBreezyTwoFactor;

    protected static string $view = "filament-breezy::filament.pages.my-profile";

    public $user;
    public $userData;
    // Password
    public $new_password;
    public $new_password_confirmation;
    // Sanctum tokens
    public $token_name;
    public $abilities = [];
    public $plain_text_token;
    protected $loginColumn;
    // faktur
    public $fakturData;

    public function boot()
    {
        // user column
        $this->loginColumn = config('filament-breezy.fallback_login_field');
    }

    public function mount()
    {
        $this->user = Filament::auth()->user();
        $this->updateProfileForm->fill($this->user->toArray());

        // faktur
        $faktur = FakturEkspedisi::where('pegawai_id', $this->user->pegawai->id ?? null)->first();
        if($faktur){
            $this->updateFakturForm->fill($faktur->toArray());
        }
    }

    protected function getForms(): array
    {
        return array_merge(parent::getForms(), [
            "updateProfileForm" => $this->makeForm()
                ->model(config('filament-breezy.user_model'))
                ->schema($this->getUpdateProfileFormSchema())
                ->statePath('userData'),
            "updatePasswordForm" => $this->makeForm()->schema(
                $this->getUpdatePasswordFormSchema()
            ),
            "createApiTokenForm" => $this->makeForm()->schema(
                $this->getCreateApiTokenFormSchema()
            ),
            "confirmTwoFactorForm" => $this->makeForm()->schema(
                $this->getConfirmTwoFactorFormSchema()
            ),
            "updateFakturForm" => $this->makeForm()
                ->model(FakturEkspedisi::class)
                ->schema($this->getUpdateFakturFormSchema())
                ->statePath('fakturData')
        ]);
    }

    protected function getUpdateProfileFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->disabled(function(){
                    $role = auth()->user()->roles[0]->name;
                    if($role == 'kurir'){
                        return true;
                    }
                })
                ->label(__('filament-breezy::default.fields.name')),
            Forms\Components\TextInput::make($this->loginColumn)
                ->required()
                ->email(fn () => $this->loginColumn === 'email')
                ->unique(config('filament-breezy.user_model'), ignorable: $this->user)
                ->label(__('filament-breezy::default.fields.email')),
        ];
    }

    protected function getUpdateFakturFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('tempat_tujuan')
                ->required()
                ->label('Kantor/Tempat Tujuan'),
            Forms\Components\TextInput::make('alamat_tujuan')
                ->required()
                ->label('Alamat Tujuan'),
        ];
    }

    public function updateFaktur()
    {
        $query = FakturEkspedisi::query();
        $fakturState = $this->updateFakturForm->getState();
        
        if($query->where('pegawai_id', $this->user->pegawai->id)->exists()){
            // update
            $query->where('pegawai_id', $this->user->pegawai->id)
                ->update([
                    'pegawai_id' => $this->user->pegawai->id,
                    'tempat_tujuan' => $fakturState['tempat_tujuan'],
                    'alamat_tujuan' => $fakturState['alamat_tujuan']
                ]);
        }else{
            $query->create([
                'pegawai_id' => $this->user->pegawai->id,
                'tempat_tujuan' => $fakturState['tempat_tujuan'],
                'alamat_tujuan' => $fakturState['alamat_tujuan']
            ]);
        }

        $this->notify("success", "Update faktur ekspedisi berhasil");
    }

    public function updateProfile()
    {
        $this->user->update($this->updateProfileForm->getState());
        $this->notify("success", __('filament-breezy::default.profile.personal_info.notify'));
    }

    protected function getUpdatePasswordFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make("new_password")
                ->label(__('filament-breezy::default.fields.new_password'))
                ->password()
                ->rules(app(FilamentBreezy::class)->getPasswordRules())
                ->required(),
            Forms\Components\TextInput::make("new_password_confirmation")
                ->label(__('filament-breezy::default.fields.new_password_confirmation'))
                ->password()
                ->same("new_password")
                ->required(),
        ];
    }

    public function updatePassword()
    {
        $state = $this->updatePasswordForm->getState();
        $this->user->update([
            "password" => Hash::make($state["new_password"]),
        ]);
        session()->forget('password_hash_' . config('filament.auth.guard'));
        Filament::auth()->login($this->user);
        $this->notify("success", __('filament-breezy::default.profile.password.notify'));
        $this->reset(["new_password", "new_password_confirmation"]);
    }

    protected function getCreateApiTokenFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('token_name')->label(__('filament-breezy::default.fields.token_name'))->required(),
            Forms\Components\CheckboxList::make('abilities')
            ->label(__('filament-breezy::default.fields.abilities'))
            ->options(config('filament-breezy.sanctum_permissions'))
            ->columns(2)
            ->required(),
        ];
    }

    public function createApiToken()
    {
        $state = $this->createApiTokenForm->getState();
        $indexes = $state['abilities'];
        $abilities = config("filament-breezy.sanctum_permissions");
        $selected = collect($abilities)->filter(function ($item, $key) use (
            $indexes
        ) {
            return in_array($key, $indexes);
        })->toArray();
        $this->plain_text_token = Filament::auth()->user()->createToken($state['token_name'], array_values($selected))->plainTextToken;
        $this->notify("success", __('filament-breezy::default.profile.sanctum.create.notify'));
        $this->emit('tokenCreated');
        $this->reset(['token_name']);
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => __('filament-breezy::default.profile.profile'),
        ];
    }

    protected static function getNavigationIcon(): string
    {
        return config('filament-breezy.profile_page_icon', 'heroicon-o-document-text');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-breezy::default.profile.account');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-breezy::default.profile.profile');
    }

    protected function getTitle(): string
    {
        return __('filament-breezy::default.profile.my_profile');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return config('filament-breezy.show_profile_page_in_navbar');
    }
}