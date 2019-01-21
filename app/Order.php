<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model {

    protected $attributes = [
        'status' => 'UNASSIGNED',
    ];

    public static function getOrders($begin, $limit) {
        return DB::table('orders')->offset($begin)->limit($limit)->get(['id', 'distance', 'status']);
    }

    public static function saveOrderOrigin($data) {
        return DB::table('orders_origin')->insertGetId(
                        ['latitude' => $data[0], 'longitude' => $data[1]]
        );
    }

    public static function saveOrderDestination($data) {
        return DB::table('orders_destination')->insertGetId(
                        ['latitude' => $data[0], 'longitude' => $data[1]]
        );
    }

}