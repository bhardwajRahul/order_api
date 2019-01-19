<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    const STATUS_UNASSIGNED = 'UNASSIGNED';

    protected $attributes = [
        'status' => self::STATUS_UNASSIGNED,
    ];
}
