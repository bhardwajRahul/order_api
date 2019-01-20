<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Order;
use App\Http\Resources\Order as OrderResource;
use App\DistanceApi\Api\Request as Api;

class OrderController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //Get Orders
        $page = $request->query('page');
        $limit = $request->query('limit');
        $begin = ($page * $limit) - $limit;

        $orders = DB::table('orders')
                ->offset($begin)
                ->limit($limit)
                ->get();

        if (count($orders) > 0) {
            return response()->json($orders, 200);
        } else {
            return response()->json(["error" => "Data Not Found"], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //Creating a New Order
        $order = new Order;

        $validatedData = $request->validate([
            "origin" => "required | array",
            "destination" => "required | array",
        ]);

        $order->distance = Api::calculateDistance($validatedData);

        if (ctype_digit(strval($order->distance))) {
            if ($order->save()) {
                return response()->json([
                            'id' => $order->id,
                            'distance' => $order->distance,
                            'status' => $order->status,
                                ], 200);
            }
        }
        return response()->json(["error" => $order->distance], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $order = Order::findorFail($id);

        $validatedData = $request->validate([
            'status' => 'required | alpha | in:TAKEN',
        ]);

        if ($order->status !== "ASSIGNED") {
            $order->status = 'ASSIGNED';

            if ($order->save()) {
                return response()->json(["status" => "SUCCESS"], 200);
            }
        }
        return response()->json(["error" => "Already Assigned"], 409);
    }

}