<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Order;
use App\Http\Resources\Order as OrderResource;
use App\DistanceApi\Api\Request as Api;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Get Orders
        $page = $request->query('page');
        $limit = $request->query('limit');
    
        $orders = Order::all();
        return response()->json($orders,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Creating a New Order
        $order = new Order;

         //$origin = $request->input('origin');
        // $destination = $request->input('destination');

        $order->distance = Api::calculateDistance($request->input());
        
        if (ctype_digit(strval($order->distance))) {
            if ($order->save()) {
                return response()->json($order, 201);
            } 
        }
        return response()->json(["error"=> $order->distance], 403);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::findorFail($id);

        $validatedData = $request->validate([
            'status' => 'required| alpha | in:TAKEN',
        ]); 
        
        if ($order->status !== "TAKEN") {

            $order->status = strtoupper($request->input('status'));
            if ($order->save()) {
                return response()->json(["status"=> "SUCCESS"], 200);
            }
        } 
        return response()->json(["error"=> "Order Already been Taken"], 409);
        
    }

}
