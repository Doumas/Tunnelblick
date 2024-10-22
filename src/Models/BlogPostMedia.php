<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostMedia extends Model
{
    // Tabelle und Primärschlüssel definieren
    protected $table = 'BlogPostMedia';
    protected $primaryKey = 'media_id';

    // Massenweise zuweisbare Felder
    protected $fillable = [
        'post_id', 
        'media_url', 
        'media_type', 
        'caption', 
        'position'
    ];

    // Timestamps (created_at und updated_at)
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    // Standardwerte für Felder setzen
    protected $attributes = [
        'media_type' => 'image', // Standardtyp ist 'image'
    ];

    // Beziehung zu einem möglichen Post (Foreign Key Beziehung)
    public function post()
    {
        return $this->belongsTo(BlogPostMedia::class, 'post_id', 'post_id');
    }

    // Zugriffsmethoden für Felder
    public function getMediaUrlAttribute($value)
    {
        return assert('storage/' . $value); // Beispiel, um die URL zur Medienressource zu generieren
    }
}
