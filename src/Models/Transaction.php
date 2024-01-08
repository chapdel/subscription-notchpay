<?php

namespace Laravelcm\Subscriptions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return config('laravel-subscriptions.tables.subscription_transactions', 'transactions');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->reference = self::reference();
        });
    }

    protected static function reference()
    {
        $r = str()->random(16);

        if (self::whereReference($r)->exists()) {
            return self::reference();
        }

        return $r;
    }

    public function buyer()
    {
        return $this->morphTo();
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
