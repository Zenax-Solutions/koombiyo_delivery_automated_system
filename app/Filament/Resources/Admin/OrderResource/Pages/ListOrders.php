<?php

namespace App\Filament\Resources\Admin\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Admin\OrderResource;
use App\Models\Branch;
use App\Models\Order;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        // Fetch all branches from the branches table
        $branches = Branch::all();

        // Initialize an empty array to hold the tabs
        $tabs = [
            'All' => Tab::make()->badge(number_format(Order::all()->count())),
        ];

        // Loop through each branch and create a tab for it
        foreach ($branches as $branch) {
            // Get the count of orders for the current branch
            $orderCount = Order::where('branch_id', $branch->id)->count();

            // Add a new tab for the current branch
            $tabs[$branch->name] = Tab::make()
                ->badge(number_format($orderCount))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('branch_id', $branch->id))
                ->label($branch->name); // Set the label to the branch name
        }

        // Return the array of tabs
        return $tabs;
    }
}
