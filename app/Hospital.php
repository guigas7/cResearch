<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Clpatient;

class Hospital extends Model
{
	public static function boot()
    {
        parent::boot();
 
        static::created(function ($hospital) {
            if (!is_null($hospital->name)) {
                $hospital->slug = str_slug($hospital->name);
                
                $latestSlug =
                Hospital::whereRaw("slug RLIKE '^{$hospital->slug}(--[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    $number = intval(end($pieces));
                    $hospital->slug .= '--' . ($number + 1);
                }
            }
            $hospital->save();
        });
 
        static::updating(function ($hospital) {
            $oldHospital = Hospital::findOrFail($hospital->id);
            if ($oldHospital->name != $hospital->name) { // se o nome foi alterado, então altera slug também
                $hospital->slug = str_slug($hospital->name);
 
                $latestSlug =
                Hospital::whereRaw("slug RLIKE '^{$hospital->slug}(--[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    $number = intval(end($pieces));
                    $hospital->slug .= '--' . ($number + 1);
                }
            }
        });
    }

    public function nextEmptySlotCl($group, $hospital_id)
    {
        return Clpatient::where('hospital_id', '=', $hospital_id)
            ->whereNull('prontuario')
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

    public function getNextOrderCl($hospital_id)
    {
        $next = Clpatient::select('order')
            ->where('hospital_id', '=', $hospital_id)
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
        'name', 'slug',
    ];

    public function patientsCl()
    {
        return $this->hasMany('App\Clpatient', 'hospital_id');
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
