<?php

namespace App\Filament\Resources\Admin\BranchResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Admin\BranchResource;

class CreateBranch extends CreateRecord
{
    protected static string $resource = BranchResource::class;
}
