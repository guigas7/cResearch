<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\User;

class User extends Authenticatable
{
    public static function boot()
    {
        parent::boot();
 
        static::created(function ($user) {
            if (!is_null($user->name)) {
                $user->slug = str_slug($user->name);
                
                $latestSlug =
                User::whereRaw("slug RLIKE '^{$user->slug}(--[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    if (count($pieces) == 1) { // first repetition
                        $user->slug .= '--' . '1';
                    } else {
                        $number = intval(end($pieces));
                        $user->slug .= '--' . ($number + 1);
                    }
                } 
            }
            $user->save();
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
                    if (count($pieces) == 1) { // first repetition
                        $user->slug .= '--' . '1';
                    } else {
                        $number = intval(end($pieces));
                        $user->slug .= '--' . ($number + 1);
                    }
                } 
            }
            $user->save();
        });
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
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function assignRole($role) {
        $this->roles()->sync($role, false); // add new role if needed, doesn't drop not included
    }

    public function abilities() 
    {
        return $this->roles->map->abilities->flatten()->pluck('name')->unique();
    }
}
