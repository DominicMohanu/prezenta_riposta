<?php
namespace App\Filament\Resources;

use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\PaymentResource\Pages;
use Filament\Forms\Components\TextInput;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payments'; // Custom navigation label
    protected static ?string $title = 'Payment'; // Custom title

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('payer_id')
                    ->options(function () {
                        return \App\Models\User::all()->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->multiple(false),

                TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->maxLength(255),

                TextInput::make('comments')
                    ->label('Comments')
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Payment::with('payer'))
            ->columns([
                Tables\Columns\TextColumn::make('payer.name')
                    ->label('Payer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount'),

                Tables\Columns\TextColumn::make('comments')
                    ->label('Comments')
                    ->limit(50),
            ])
            ->filters([
//
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
