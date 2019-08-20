<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Size;
use App\Product;
use App\Mail\SendMail;
use Mail;

class ManagementOrderController extends Controller
{
    // public function sendMail(){
    //     $user = 'hello trung ';
    //     Mail::to('levantrung98.qn@gmail.com')->send(new SendMail($user));
    //     return 'send mail thành công';
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $userID = $user->id;
        $orderID = Order::where('user_id','=',$userID)->get();
        $product = Product::all();
        $size = Size::all();
        return view('user.quanlidonhang',compact('orderID','product','size'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
