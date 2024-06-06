<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable=['invoice','customer_id','user_id','total'];

    public function order_detail():HasMany
    {
        return $this->hasMany(Order_detail::class);
    }

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
