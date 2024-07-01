<?php

namespace App\Filament\Resources\Admin;

use Filament\Forms;
use Filament\Tables;
use App\Models\Branch;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Admin\BranchResource\Pages;
use App\Filament\Resources\Admin\BranchResource\RelationManagers;
use Filament\Tables\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Outlets';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    FileUpload::make('logo')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->autofocus(),
                    TextInput::make('slug')->readOnly()->required(),

                    RichEditor::make('address')
                        ->nullable()
                        ->string()
                        ->required()
                        ->fileAttachmentsVisibility('public'),

                    RichEditor::make('contact_details')
                        ->string()
                        ->nullable()
                        ->fileAttachmentsVisibility('public'),

                    TextInput::make('api_key')
                        ->string()
                        ->placeholder('add koombiyo api key here')
                        ->autofocus(),

                    Toggle::make('api_enable')
                        ->onColor('success')
                        ->offColor('danger')
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->visibility('public'),

                TextColumn::make('name')->copyable()
                    ->copyMessage('Shop Url is copied')
                    ->copyableState(fn (Branch $record): string => route('order-form', Str::slug($record->name, '-'))),


                TextColumn::make('address')->limit(255)->html(),

                TextColumn::make('contact_details')->limit(255)->html(),

                ToggleColumn::make('api_enable')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('Link')
                    ->copyable()
                    ->copyableState(fn (Branch $record): string => route('order-form', Str::slug($record->name, '-')))
                    ->icon('heroicon-o-clipboard-document')
                    ->color('success'),
                Action::make('Navigate to shop')
                    ->url(fn (Branch $record): string => route('order-form', Str::slug($record->name, '-')))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-link')
                    ->color('danger')

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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'view' => Pages\ViewBranch::route('/{record}'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
