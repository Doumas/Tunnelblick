<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $table = 'donations'; // Tabellenname in der Datenbank
    protected $primaryKey = 'donation_id'; // Primärschlüssel der Tabelle
    public $timestamps = false; // `donated_at` wird manuell verwaltet

    protected $fillable = [
        'user_id',
        'amount',
        'donated_at',
        'reward_id',
        'reward_sent',
        'payment_status',
        'transaction_id',
        'shipment_tracking_number',
        'processing_status'
    ];

    // Beziehung zu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

 
}
