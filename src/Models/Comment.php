<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments'; // Tabellenname in der Datenbank
    protected $primaryKey = 'comment_id'; // Primärschlüssel der Tabelle
    public $timestamps = true; // Automatische Verwaltung von created_at und updated_at (falls es gibt)

    protected $fillable = [
        'user_id',
        'content',
        'post_id',
        'video_id'
    ];

    // Beziehung zu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Beziehung zu BlogPost (optional, falls ein Kommentar zu einem BlogPost gehört)
    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

   
}
