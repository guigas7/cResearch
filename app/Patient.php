<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Patient;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'ventilator', 'order', 'hospital_id', 'study', 'inserted_on'
    ];

    public function hospital()
    {
    	return $this->belongsTo(User::class, 'hospital_id');
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
            if (!is_null($patient->name)) {
                dd($patient);
                $patient->slug = str_slug($patient->name);
     
                $latestSlug =
                Patient::whereRaw("slug RLIKE '^{$patient->slug}(--[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    $number = intval(end($pieces));
                    $patient->slug .= '--' . ($number + 1);
                }
            }
        });
 
        static::updating(function ($patient) {
            $oldpatient = Patient::findOrFail($patient->id);
            if ($oldpatient->name != $patient->name) { // se o nome foi alterado, então altera slug também
                $patient->slug = str_slug($patient->name);
 
                $latestSlug =
                Patient::whereRaw("slug RLIKE '^{$patient->slug}(--[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug');
                if ($latestSlug->first() != null) {
                    $pieces = explode('--', $latestSlug->first());
                    $number = intval(end($pieces));
                    $patient->slug .= '--' . ($number + 1);
                }
            }
        });
	}
}
