<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaybillSequenceResource\Pages;
use App\Filament\Resources\WaybillSequenceResource\RelationManagers;
use App\Models\WaybillSequence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WaybillSequenceResource extends Resource
{
    protected static ?string $model = WaybillSequence::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('start_number')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('current_number', $state)),
                Forms\Components\TextInput::make('end_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('current_number')
                    ->default(fn ($get) => $get('start_number'))
                    ->readOnly(),
                Forms\Components\Checkbox::make('active')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_number'),
                Tables\Columns\TextColumn::make('end_number'),
                Tables\Columns\TextColumn::make('current_number'),
                Tables\Columns\BooleanColumn::make('active'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListWaybillSequences::route('/'),
            'create' => Pages\CreateWaybillSequence::route('/create'),
            'edit' => Pages\EditWaybillSequence::route('/{record}/edit'),
        ];
    }
}
