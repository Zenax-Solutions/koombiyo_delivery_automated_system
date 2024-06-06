<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Filters\DateRangeFilter;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Admin\OrderResource\Pages;
use App\Models\City;
use App\Models\District;
use Filament\Tables\Columns\ViewColumn;
use App\Models\Product;
use App\Services\WaybillNumberGenerator;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Support\Facades\Session;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;



class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Orders';


    public static function form(Form $form): Form
    {

        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('branch_id')
                        ->required()
                        ->relationship('branch', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('waybill_id')
                        ->nullable()
                        ->string(),

                    TextInput::make('receiver_name')
                        ->nullable()
                        ->required()
                        ->string(),

                    TextInput::make('delivery_address')
                        ->nullable()
                        ->required()
                        ->string(),

                    Select::make('district_id')
                        ->required()
                        ->label('District')
                        ->options(District::all()->pluck('name_en', 'id'))
                        ->native(false)
                        ->live()
                        ->searchable(),

                    Select::make('city_id')
                        ->label('City')
                        ->required()
                        ->options((function (callable $get) {

                            $city = City::where('district_id', $get('district_id'))->get();

                            if ($city) {
                                return $city->pluck('name_en', 'id');
                            }

                            return City::all()->pluck('name_en', 'id');
                        }))
                        ->native(false)
                        ->searchable(),

                    TextInput::make('receiver_phone')
                        ->nullable()
                        ->tel()
                        ->required()
                        ->string(),


                    Repeater::make('description')
                        ->label('Products')
                        ->schema([
                            Grid::make(['default' => 0])->schema([

                                Select::make('product_id')
                                    ->required()
                                    ->label('Product')
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->native(false)
                                    ->searchable(),

                                Select::make('size')
                                    ->required()
                                    ->options([
                                        'S' => 'S',
                                        'M' => 'M',
                                        'L' => 'L',
                                        'XL' => 'XL',
                                        'XXL' => 'XXL',
                                    ]),

                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                            ]),
                        ]),

                    TextInput::make('cod')
                        ->nullable()
                        ->numeric()
                        ->default(0)
                        ->step(1),

                    TextInput::make('actual_value')
                        ->nullable()
                        ->default(0)
                        ->numeric()
                        ->step(1),

                    ToggleButtons::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'canceled' => 'Canceled',
                            'dispatched' => 'Dispatched',
                        ])
                        ->icons([
                            'pending' => 'heroicon-o-arrow-path',
                            'canceled' => 'heroicon-o-x-mark',
                            'dispatched' => 'heroicon-o-truck',
                        ])
                        ->colors([
                            'pending' => 'danger',
                            'canceled' => 'warning',
                            'dispatched' => 'success',
                        ])
                        ->label('Order status')->default('pending'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name'),

                TextColumn::make('waybill_id')->label('Waybill Id')->searchable(),

                TextColumn::make('id')->label('Order Number')->searchable(),

                TextColumn::make('receiver_name')->label('Receiver Name')->searchable(),

                TextColumn::make('delivery_address')->label('Delivery Address')->searchable(),

                TextColumn::make('district.name_en')->label('District'),

                TextColumn::make('city.name_en')->label('City'),

                TextColumn::make('receiver_phone')->label('Receiver Phone')->searchable(),

                ViewColumn::make('description')->view('tables.columns.shows-products'),

                // TextColumn::make('description')->badge()->separator(','),

                TextColumn::make('cod')->label('COD')->numeric(decimalPlaces: 2),

                TextColumn::make('actual_value')->numeric()->label('Actual Value'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'danger',
                        'canceled' => 'warning',
                        'dispatched' => 'success',
                    }),
            ])
            ->filters([
                DateRangeFilter::make('created_at'),

                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'canceled' => 'Canceled',
                        'dispatched' => 'Dispatched',
                    ])->multiple()


            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    BulkAction::make('bulk print')
                        ->action(function (Collection $records) {

                            Session::put('bulkwaybill_records', $records->toArray());

                            return redirect()->route('bulkwaybill');
                        })->icon('heroicon-o-printer')->color('warning'),

                    BulkAction::make('Dispatched')
                        ->action(function (Collection $selectedRecords) {
                            $selectedRecords->each(
                                fn (Model $selectedRecord) => $selectedRecord->update([
                                    'status' => 'dispatched',
                                ]),
                            );
                        })->icon('heroicon-o-truck')->color('success'),

                    BulkAction::make('Canceled')
                        ->action(function (Collection $selectedRecords) {
                            $selectedRecords->each(
                                fn (Model $selectedRecord) => $selectedRecord->update([
                                    'status' => 'canceled',
                                ]),
                            );
                        })->icon('heroicon-o-x-mark')->color('warning'),

                    BulkAction::make('Pending')
                        ->action(function (Collection $selectedRecords) {
                            $selectedRecords->each(
                                fn (Model $selectedRecord) => $selectedRecord->update([
                                    'status' => 'pending',
                                ]),
                            );
                        })->icon('heroicon-o-arrow-path')->color('danger'),

                    // BulkAction::make('waybill_genarate')
                    //     ->action(function (Collection $selectedRecords) {
                    //         $selectedRecords->each(
                    //             fn (Model $selectedRecord) => $selectedRecord->update([
                    //                 'waybill_id' => WaybillNumberGenerator::generate(),
                    //             ]),
                    //         );
                    //     })->icon('heroicon-o-hashtag'),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportBulkAction::make()->exports([

                    ExcelExport::make()->withColumns([
                        Column::make('waybill_id')->heading('Waybill Id'),
                        Column::make('id')->heading('Order Number'),
                        Column::make('receiver_name')->heading('Receiver Name'),
                        Column::make('delivery_address')->heading('Delivery Address'),
                        Column::make('district.name_en')->heading('District'),
                        Column::make('city.name_en')->heading('City'),
                        Column::make('receiver_phone')->heading('Receiver Phone'),
                        Column::make('cod')->formatStateUsing(function ($state) {
                            return number_format($state, 2);
                        })->heading('COD'),
                        Column::make('description')
                            ->formatStateUsing(function ($state) {

                                $product = Product::find($state['product_id']);
                                $description = '';

                                if ($product) {
                                    $description .= $product->name . ' (' . $state['size'] . ') X ' . $state['quantity'] . ', ';
                                }

                                // Remove the trailing comma and space
                                return rtrim($description, ', ');
                            }),
                        Column::make('actual_value')->heading('Actual Value'),
                    ])
                ])

            ]);
    }


    public function getTableBulkActions()
    {
        return  [];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
