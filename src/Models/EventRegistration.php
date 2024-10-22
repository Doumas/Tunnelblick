<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $table = 'EventRegistrations'; // Tabellenname in der Datenbank
    protected $primaryKey = 'registration_id'; // Primärschlüssel der Tabelle
    public $timestamps = false; // Wir verwenden keinen automatischen Zeitstempel

    // Die Attribute, die massenhaft zugewiesen werden dürfen
    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_type',
        'price',
        'payment_status',
        'registered_at'
    ];

    // Beziehung zu Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Beziehung zu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
