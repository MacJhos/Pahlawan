<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class MyProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Foto Profil')
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('avatars')
                            ->alignCenter()
                            ->preserveFilenames()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1'),
                    ]),

                Section::make('Informasi Pribadi')
                    ->description('Perbarui data diri dan tanggal lahir Anda.')
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),

                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ]),

                Section::make('Keamanan')
                    ->description('Kosongkan jika tidak ingin mengubah kata sandi.')
                    ->schema([
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),
            ]);
    }
}
