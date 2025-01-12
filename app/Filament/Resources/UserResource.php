<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Existing fields
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('password')->required(),
                Forms\Components\TextInput::make('email')->email()->unique(ignoreRecord: true),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                // New fields
                Forms\Components\TextInput::make('tag_id')->nullable(),         // Adding tag_id field
                Forms\Components\DatePicker::make('date_of_birth')->nullable(), // Adding date_of_birth field
                Forms\Components\TextInput::make('phone_number')->nullable()    // Adding phone_number field
                ->tel(),  // Make it accept phone number input format
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Existing columns
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),

                // New columns
                Tables\Columns\TextColumn::make('tag_id'),
                Tables\Columns\TextColumn::make('date_of_birth'),
                Tables\Columns\TextColumn::make('phone_number'),  // Adding phone_number column
            ])
            ->filters([
                // Filters if any
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
