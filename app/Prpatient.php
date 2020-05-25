<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Prpatient;
use App\Hospital;

class Prpatient extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prontuario', 'slug', 'order', 'hospital_id', 'study', 'inserted_on'
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
 
        static::creating(function ($patient) {
            if (!is_null($patient->prontuario)) {
                $patient->slug = str_slug($patient->prontuario);
     
                $latestSlug =
                Prpatient::whereRaw("slug RLIKE '^{$patient->slug}(--[0-9]*)?$'")
                    ->latest('slug')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    if (count($pieces) == 1) { // first repetition
                        $patient->slug .= '--' . (1);
                    } else {
                        $number = intval(end($pieces));
                        $patient->slug .= '--' . ($number + 1);
                    }
                } 
            }
        });
 
        static::updating(function ($patient) {
            $oldpatient = Prpatient::findOrFail($patient->id);
            if (is_null($patient->prontuario)) {
                $patient->slug = null;
            } else {
                if ($oldpatient->prontuario != $patient->prontuario) { // se o nome foi alterado, então altera slug também
                    $patient->slug = str_slug($patient->prontuario);
     
                    $latestSlug =
                    Prpatient::whereRaw("slug RLIKE '^{$patient->slug}(--[0-9]*)?$'")
                        ->latest('slug')
                        ->pluck('slug');
                    if ($latestSlug->first() != null) {
                        $pieces = explode('--', $latestSlug->first());
                        if (count($pieces) == 1) { // first repetition
                            $patient->slug .= '--' . (1);
                        } else {
                            $number = intval(end($pieces));
                            $patient->slug .= '--' . ($number + 1);
                        }
                    } 
                }
            }
        });
	}
}
