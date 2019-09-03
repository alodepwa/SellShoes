<?php

namespace App\Http\Controllers;

use App\Order;
use App\Size;
use App\Product;
use Mail;
use App\Mail\SendMailOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    

    // approve order form users
    public function approveOrder(Request $request){
        $id = $request->get('id');
        $quantity = explode(';', $request->get('quantity'));
        $product =explode(';', $request->get('product'));
        $size = explode(';', $request->get('size'));

        for ($i=0; $i <count($product)-1 ; $i++) { 
            $price=Product::findOrFail($product[$i]);
            foreach ($price->sizes as $key => $value) {
                if($size[$i]==$value->pivot->size_id){
                    $sl[$i]=$value->pivot->quantity;
                    if($quantity[$i]<=$sl[$i]){
                        $newQuantity = $sl[$i]-$quantity[$i];
                        $update[$size[$i]]=['quantity'=>$newQuantity];
                        $price->sizes()->syncWithoutDetaching($update);
                        $order =Order::findOrFail($id); 
                        $order['status']=2;
                        $order->save();
                        $result="Thêm mới thành công vào order";
                    }else{
                        $result="số lượng trong kho không đủ";
                        break;
                    }
                }
            }
        }   
        return response()->json($result);
    }
    public function loadListOrder(Request $request){
        $value = $request->value;
        $order = Order::where('status','=',$value)->paginate(7);
        $size = Size::all();
        $out='';
        if($value==1){
            foreach ($order as $key => $value) {
                $out.='<tr><td>'.$value->id.'</td><td>'.$value->name.'</td><td >'.$value->email.'</td><td>';
                foreach($value->products as $vl){
                    $out.='<p class="'.$value->id.'product" data-id="'.$vl->id.'">'.$vl->name.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $quantity) {
                            $out.='<p class="'.$value->id.'quantity" data-id="'.$quantity->pivot->quantity.'">'.$quantity->pivot->quantity.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $quantity) {
                    $out.='<p>'.$quantity->pivot->price.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $vl) {
                    $checkSize = $vl->pivot->size;
                    foreach ($size as $key => $vl) {
                    if($checkSize == $vl->id){
                        $out.= '<p class="'.$value->id.'size" data-id='.$vl->id.'>'.$vl->name.'</p>';
                    }
                }
                    
                }
                $out.='</td>
                            <td>
                                <button class="btn-info yes" data-id="'.$value->id.'">yes</button>
                                <button class="btn-danger no" data-id="'.$value->id.'">no</button>
                            </td>
                        </tr>';
            }
        }else if($value==2){
            foreach ($order as $key => $value) {
                $out.='<tr><td>'.$value->id.'</td><td>'.$value->name.'</td><td >'.$value->email.'</td><td>';
                foreach($value->products as $vl){
                    $out.='<p class="'.$value->id.'product" data-id="'.$vl->id.'">'.$vl->name.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $quantity) {
                            $out.='<p class="'.$value->id.'quantity" data-id="'.$quantity->pivot->quantity.'">'.$quantity->pivot->quantity.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $quantity) {
                    $out.='<p>'.$quantity->pivot->price.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $vl) {
                    $checkSize = $vl->pivot->size;
                    foreach ($size as $key => $vl) {
                    if($checkSize == $vl->id){
                        $out.= '<p class="'.$value->id.'size" data-id='.$vl->id.'>'.$vl->name.'</p>';
                    }
                }
                    
                }
                $out.='</td>
                            <td>
                                <p class="text-primary">Đang giao hàng</p>
                            </td>
                        </tr>';
            }
        }else{
            foreach ($order as $key => $value) {
                $out.='<tr><td>'.$value->id.'</td><td>'.$value->name.'</td><td >'.$value->email.'</td><td>';
                foreach($value->products as $vl){
                    $out.='<p class="'.$value->id.'product" data-id="'.$vl->id.'">'.$vl->name.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $quantity) {
                            $out.='<p class="'.$value->id.'quantity" data-id="'.$quantity->pivot->quantity.'">'.$quantity->pivot->quantity.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $quantity) {
                    $out.='<p>'.$quantity->pivot->price.'</p>';
                }
                $out.='</td><td>';
                foreach ($value->products as $vl) {
                    $checkSize = $vl->pivot->size;
                    foreach ($size as $key => $vl) {
                    if($checkSize == $vl->id){
                        $out.= '<p class="'.$value->id.'size" data-id='.$vl->id.'>'.$vl->name.'</p>';
                    }
                }
                    
                }
                $out.='</td>
                            <td>
                                <p class="text-danger">Đơn hàng đã hủy.</p>
                            </td>
                        </tr>';
            }
        }
        $paginate='<div class="row">
            <div class="col-12 d-flex justify-content-center" id="pageAdd">
                '.$order->links().'
            </div>
        </div>';
        $result =['out'=>$out,'paginate'=>$paginate];

        return response()->json($result);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::where('status','=',1)->paginate(7);
        $size = Size::all();
        foreach ($order as $key => $value) {
            $order2=$value->products;
            foreach ($order2 as $key => $value) {
                $order3=$value->pivot->status;
            }
        }
        $list  = Order::where('status','=',2)->paginate(7);
        return view('admin.listOrder',compact('order','size','list'));
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
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        foreach ($order->products as $key => $value) {
            $productID = $value->id;
        }
        $product = Product::findOrFail($productID);
        foreach ($product->images as $key => $value) {
            $path = $value->path;
        }
        if($order->delete()){
            Mail::to($order->email)->send(new SendMailOrder($order,$path));
            $order->products()->detach();
            $result="Đã loại bỏ đơn hàng";
        }
        return response()->json($result);
    }
}
