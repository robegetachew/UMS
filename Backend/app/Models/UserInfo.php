<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'gender',
        'phone_number',
        'date_of_birth',
        'location',
        'image_path',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
