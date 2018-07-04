<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Account extends Model
{
    use Notifiable;
    public $incrementing = false;
    protected $fillable = ['編號', 'id','password','name','level','黑武神','金遊',
        '積巴', '巴爾','遊俠','魔光','黑騎','音速',
        '富豪', '犽霸','更名卡','舒適','銀色','TOP100',
        '車子數量', 'Koin','膠囊R','膠囊S','膠囊B','狀態','price','store'];

    public function scopestatus($query, $status)
    {
        if (!in_array($status, ['1', '2', '3','4'])) {
            return $query;
        }

        return $query->where('狀態', $status);
    }

}
