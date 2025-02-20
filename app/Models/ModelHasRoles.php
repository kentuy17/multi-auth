<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Roles;
use App\Models\User;

class ModelHasRoles extends Model
{
    use HasFactory;
    protected $table = 'model_has_roles';

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
        'updated_at',
        'created_at'
    ];

    protected $OPERATOR = 3;
    protected $AUDITOR = 5;
    protected $CASHOUT = 6;
    protected $CASHIN = 7;



    public function roles()
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'model_id', 'id');
    }

    public function operators()
    {
        return $this->belongsTo(User::class, 'model_id')
            ->where('role_id', $this->CASHIN);
    }

    public function active_operators()
    {
        // return $this->operators()->where('active', 1);
        // return $this->operators()->online();
        return $this->operators()->where('id', 104);
    }

    public function lowest_pts()
    {
        return $this->operators()->orderBy('points');
    }
    public function auditor()
    {
        return $this->belongsTo(User::class, 'model_id')
            ->where('role_id', $this->AUDITOR);
    }
    public function cashout()
    {
        return $this->belongsTo(User::class, 'model_id')
            ->where('role_id', $this->CASHOUT);
    }
    public function cashin()
    {
        return $this->belongsTo(User::class, 'model_id')
            ->where('role_id', $this->CASHIN);
    }
}
