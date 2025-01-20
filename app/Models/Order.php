<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Order extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'address'];

    public function basket()
    {
        return $this->hasMany(OrderItems::class);
    }
}
