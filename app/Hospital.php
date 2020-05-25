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
                    if (count($pieces) == 1) { // first repetition
                        $hospital->slug .= '--' . '1';
                    } else {
                        $number = intval(end($pieces));
                        $hospital->slug .= '--' . ($number + 1);
                    }
                } 
            }
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
                    if (count($pieces) == 1) { // first repetition
                        $hospital->slug .= '--' . '1';
                    } else {
                        $number = intval(end($pieces));
                        $hospital->slug .= '--' . ($number + 1);
                    }
                } 
            }
        });
    }

    public function nextEmptySlotCl($group) // grab patients
    {
        return Clpatient::where('hospital_id', '=', $this->id) // in the same hospital
            ->where('ventilator', $group) // in the same vent group
            ->whereNull('prontuario') // only empty ones
            ->orderBy('id') // first of them
            ->first();
    }

    public function nextEmptySlotPr() // grab patients
    {
        return Prpatient::whereNull('prontuario') // no groups, so only empty ones
            ->orderBy('id') // first of them
            ->first();
    }

    public function maxBlock()
    {
        return Block::select('id')
            ->orderBy('id', 'DESC')
            ->first()
            ->id;
    }

    public function getNextOrderCl()
    {
        $next = Clpatient::select('order') // grab order
            ->where('hospital_id', '=', $this->id) // in the same hospital
            // ->where('ventilator', $group) // this wasn't here first by mistake so i don't want to break it now
            ->orderBy('id', 'desc') // last one
            ->first();
        return is_null($next) ? 1 : $next->order + 1;
    }

    public function getNextOrderPr()
    {
        $next = Prpatient::select('order') // grab order
            ->orderBy('id', 'desc') // last one
            ->first();
        return is_null($next) ? 1 : $next->order + 1;
    }

    public function findPatientCl($prontuario)
    {
        return $this->patientsCl->where('prontuario', $prontuario)->first();
    }

    public function findPatientPr($prontuario)
    {
        return $this->patientsPr->where('prontuario', $prontuario)->first();
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

    public function patientsPr()
    {
        return $this->hasMany('App\Prpatient', 'hospital_id');
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
