<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Notifications\Notification;
use Filament\Notifications\Livewire\DatabaseNotifications;
use Illuminate\Notifications\Messages\BroadcastMessage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function toDatabase(User $notifiable): array
    {
        return Notification::make()
            ->title('New Order Received 🛍️🔥')
            ->getDatabaseMessage();
    }


    public function toBroadcast(User $notifiable): BroadcastMessage
    {
        return Notification::make()
            ->title('Saved successfully')
            ->getBroadcastMessage();
    }
}
