<?php

namespace App\Filament\Resources\Admin;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Admin\ProductResource\Pages;
use App\Filament\Resources\Admin\ProductResource\RelationManagers;
use App\Models\Branch;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Inventory';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('category_id')
                        ->required()
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('branch_id')
                        ->required()
                        ->label('Branch')
                        ->options(Branch::all()->pluck('name', 'id'))
                        ->native(false)
                        ->live()
                        ->searchable(),

                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),

                    FileUpload::make('image')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('variations')
                        ->label('Size Variations')
                        ->multiple()
                        ->options([
                            'S' => 'S',
                            'M' => 'M',
                            'L' => 'L',
                            'XL' => 'XL',
                            'XXL' => 'XXL',
                        ])


                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('category.name'),

                TextColumn::make('branch.name'),

                TextColumn::make('name'),

                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('price')->numeric(),

                TextColumn::make('variations')->label('Size Variations')->badge()->separator(','),

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
