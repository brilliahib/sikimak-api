<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company_name',
        'company_location',
        'apply_status',
        'approval_status',
        'application_category',
        'notes',
        'deadline',
        'work_location',
        'submitted_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
