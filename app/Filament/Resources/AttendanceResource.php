<?php
namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                DatePicker::make('timestamp')
                    ->label('Timestamp')
                    ->required()
                    ->default(now())
                    ->minDate('2023-01-01')
                    ->maxDate(now()),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.tag_id')
                    ->label('Tag ID'),

                Tables\Columns\TextColumn::make('timestamp')
                    ->label('Timestamp')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? $state->format('Y-m-d H:i:s') : 'N/A'),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Filter by User')
                    ->options(User::query()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),


                Tables\Filters\Filter::make('timestamp_range')
                    ->label('Timestamp Range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Start Date'),
                        DatePicker::make('end_date')
                            ->label('End Date'),
                    ])
                    ->query(function ($query, $data) {
                        if (isset($data['start_date']) && isset($data['end_date'])) {
                            return $query->whereBetween('timestamp', [$data['start_date'], $data['end_date']]);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->label('Delete Selected')
                    ->action(function (array $records) {
                        foreach ($records as $record) {
                            $record->delete();
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
// Define relationships if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (!auth()->user()->hasRole('super_admin')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
