<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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

    public static function boot()
    {
        parent::boot();
 
        static::creating(function ($user) {
            $latestSlug =
            User::whereRaw("slug RLIKE '^{$user->slug}(--[0-9]*)?$'")
                ->latest('id')
                ->pluck('slug');
            if ($latestSlug->first() != null) {
                $pieces = explode('--', $latestSlug->first());
                $number = intval(end($pieces));
                $user->slug .= '--' . ($number + 1);
            }
        });
 
        static::updating(function ($user) {
            $oldUser = User::findOrFail($user->id);
            if ($oldUser->name != $user->name) { // se o nome foi alterado, então altera slug também
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
}
