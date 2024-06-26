<?php

namespace App\Models\Traits;

use Filament\Panel;

trait FilamentTrait
{
    /*
     * Returns whether the user is allowed to access Filament
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isSuperAdmin();
    }
}
