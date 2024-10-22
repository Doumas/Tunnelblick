<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $table = 'blog_posts'; // Tabellenname in der Datenbank
    protected $primaryKey = 'post_id'; // Primärschlüssel der Tabelle
    public $timestamps = true; // Automatische Verwaltung von created_at und updated_at

    protected $fillable = [
        'title',
        'main_image',
        'content',
        'dj_id'
    ];

    // Beziehung zu DJ, falls ein DJ zugeordnet ist
    public function dj()
    {
        return $this->belongsTo(DJ::class, 'dj_id');
    }

    // Beziehung zu BlogPostMedia, falls Medien zugeordnet sind
    public function media()
    {
        return $this->hasMany(BlogPostMedia::class, 'post_id');
    }
}
