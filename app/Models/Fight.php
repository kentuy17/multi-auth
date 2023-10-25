<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bet;
use App\Models\DerbyEvent;

class Fight extends Model
{
    use HasFactory;
    protected $table = 'fights';
    protected $fillable = [
        'id',
        'fight_no',
        'user_id',
        'amount',
        'game_winner',
        'status',
        'created_at',
        'updated_at',
        'event_id'
    ];

    protected $hidden = [
        'user_id',
        'amount',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y h:s A',
        'updated_at' => 'datetime:M d, Y h:s A',
    ];

    public function bet()
    {
        return $this->hasMany(Bet::class, 'fight_id');
    }

    public function bet_legit_meron()
    {
        return $this->hasMany(Bet::class, 'fight_id')
            ->whereNotIn('user_id', [9])
            ->where('side', 'M');
    }

    public function bet_legit_wala()
    {
        return $this->hasMany(Bet::class, 'fight_id')
            ->whereNotIn('user_id', [9])
            ->where('side', 'W');
    }

    public function event()
    {
        return $this->belongsTo(DerbyEvent::class, 'event_id');
    }
}
