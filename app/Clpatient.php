<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Clpatient;
use App\Hospital;

class Clpatient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prontuario', 'slug', 'ventilator', 'order', 'hospital_id', 'study', 'inserted_on'
    ];

    public function hospital()
    {
    	return $this->belongsTo('App\Hospital', 'hospital_id');
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
 
        static::creating(function ($cl_patient) {
            if (!is_null($cl_patient->prontuario)) {
                $cl_patient->slug = str_slug($cl_patient->prontuario);
     
                $latestSlug =
                Clpatient::whereRaw("slug RLIKE '^{$cl_patient->slug}(--[0-9]*)?$'")
                    ->latest('slug')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    if (count($pieces) == 1) { // first repetition
                        $cl_patient->slug .= '--' . '1';
                    } else {
                        $number = intval(end($pieces));
                        $cl_patient->slug .= '--' . ($number + 1);
                    }
                } 
            }
        });
 
        static::updating(function ($cl_patient) {
            $oldpatient = Clpatient::findOrFail($cl_patient->id);
            if (is_null($cl_patient->prontuario)) {
                $cl_patient->slug = null;
            } else {
                if ($oldpatient->prontuario != $cl_patient->prontuario) { // se o nome foi alterado, então altera slug também
                    $cl_patient->slug = str_slug($cl_patient->prontuario);
    
                    $latestSlug =
                    Clpatient::whereRaw("slug RLIKE '^{$cl_patient->slug}(--[0-9]*)?$'")
                        ->latest('slug')
                        ->pluck('slug');
                    if ($latestSlug->first() != null) {
                        $pieces = explode('--', $latestSlug->first());
                        if (count($pieces) == 1) { // first repetition
                            $cl_patient->slug .= '--' . '1';
                        } else {
                            $number = intval(end($pieces));
                            $cl_patient->slug .= '--' . ($number + 1);
                        }
                    } 
                }
            }
        });
	}
}
