<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Patient;
use App\Block;
use App\User;

class User extends Authenticatable
{
    public static function boot()
    {
        parent::boot();
 
        static::created(function ($user) {
            if (!is_null($user->name)) {
                dd($user);
                $user->slug = str_slug($user->name);
     
                $latestSlug =
                User::whereRaw("slug RLIKE '^{$user->slug}(--[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    $number = intval(end($pieces));
                    $user->slug .= '--' . ($number + 1);
                }
            }
        });
 
        static::updating(function ($user) {
            $olduser = User::findOrFail($user->id);
            if ($olduser->name != $user->name) { // se o nome foi alterado, então altera slug também
                $user->slug = str_slug($user->name);
 
                $latestSlug =
                User::whereRaw("slug RLIKE '^{$user->slug}(--[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    $number = intval(end($pieces));
                    $user->slug .= '--' . ($number + 1);
                }
            }
        });
    }

    public function nextEmptySlot($group)
    {
        return Patient::where('hospital_id', '=', $this->id)
            ->whereNull('name')
            ->where('ventilator', $group)
            ->orderBy('order')
            ->first();
    }

    public function maxBlock()
    {
        return Block::select('id')
            ->orderBy('id', 'DESC')
            ->first()
            ->id;
    }

    public function getNextOrder()
    {
        $next = Patient::select('order')
            ->where('hospital_id', '=', $this->id)
            ->orderBy('order', 'desc')
            ->first();
        return is_null($next) ? 1 : $next->order + 1;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'login', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class, 'hospital_id');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
