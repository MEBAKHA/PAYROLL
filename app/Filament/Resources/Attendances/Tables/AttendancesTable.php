<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('tanggal'),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('schedule_latitude')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('schedule_longitude')
                    ->numeric()
                     ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('schedule_start_time')
                    ->time()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('schedule_end_time')
                    ->time()
                     ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('latitude')
                    ->numeric()
                     ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('longitude')
                    ->numeric()
                     ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('start_time')
                    ->time()
                    ->label('jam masuk')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time()
                    ->label('jam pulang')

                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
