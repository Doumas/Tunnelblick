<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DJ extends Model
{
    protected $table = 'DJs';
    protected $primaryKey = 'dj_id';
    protected $fillable = [
        'name', 'slug', 'profile_image', 'bio', 
        'job_description', 'social_media_links', 
        'title_image', 'event_images', 'past_events'
    ];

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }
}
