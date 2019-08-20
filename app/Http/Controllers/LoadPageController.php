<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Size;
use App\Product;
use App\Order;
use Validator;
use App\Mail\SendMail;
use Mail;
class LoadPageController extends Controller
{

    // order product
    public function order(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'tel'=>'numeric|required',
            'email'=>'email|required',
            'address'=>'required'
        ],[
            'name.required'=>'Name không được để trống',
            'tel.required'=>'Tel không được để trống',
            'email.required'=>'Email không được để trống',
            'address.required'=>'Address không được để trống',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $data = $request->except('size','productID','quantity');
            $data['status']=1;
            $size = explode(';', $request->get('size'));
            $quantity = explode(';', $request->get('quantity'));
            $product= explode(';', $request->get('productID'));
            $user= \Auth::user();
            $userID = $user->id;
            $data['user_id']=$userID;
            for ($i=0; $i <count($size)-1 ; $i++) { 
                $sizeID[$i]= Size::select('id')->where('name','=',str_replace(array('{','}'), array('',''), $size[$i]))->get();
                $sl[$i]=str_replace(array('{','}'), array('',''),$quantity[$i]);
                $priceID[$i]=Product::select('price')->where('id','=',str_replace(array('{','}'), array('',''),$product[$i]))->get();
                $productID[$i]=str_replace(array('{','}'), array('',''),$product[$i]);
            }
            foreach ($productID as $key => $value) {
                $order[$value]=['quantity'=>$sl[$key],'price'=>$priceID[$key][0]['price'],'size'=>$sizeID[$key][0]['id']];
                $productName[$key]=Product::findOrFail($value);
               
            }
            foreach ($productName as $key => $value) {
                 $pathImage[$key]=$value->images;
            }
            foreach ($sizeID as $key => $value) {
                $sizeName[$key]=Size::findOrFail($value);
            }
            if(Order::create($data)->products()->sync($order)){
                $sessionUser = Session()->get('user');
                $email = $sessionUser['email'][0];
                $mailOrder=$user->name;
                if(Mail::to($request->email)->send(new SendMail($mailOrder,$productName,$pathImage,$sizeName,$priceID,$sl)))
                {
                     $result="Bạn đã đặt hàng thành công!";
                     Session()->forget('user.'.$email);
                }
                else{
                    $result="Gặp vấn đề trong việc gởi mail!";
                }
            }
            return response()->json($result);
        }

        
    }


    //view checkout
    public function checkout(Request $request){
        $quantity = explode(';',$request->get('quantity'));
        $price = explode(';',trim($request->get('size')));
        $nameProduct = explode(';',trim($request->get('nameProduct')));
        $total =$request->get('total');
        $size = explode(';', $request->get('sizeAll'));
        $productID = explode(';', $request->get('productID'));
        $user = \Auth::user();
        return view('user.checkout',compact('quantity','price','nameProduct','total','productID','size','user'));
    }


    // delete product khỏi giỏ hàng
    public function deleteCart(Request $request){
        $productID = $request->get('id');
        $user = \Auth::user();
        $id = $user->id;
        $result= Session()->forget('user.'.$id.'.cart.'.$productID);
        $cart =$request->session()->get('user');
        foreach ($cart as $key => $value) {
            if($key == $id){
                $result = $value;
            }
        }
        $count = count($result['cart']);
        return response()->json($count);       
    }



    // load page cartDetail
    public function cartDetail(){
        $user = \Auth::user();
        $id = $user->id;
        $product = Session()->get('user');
        foreach ($product as $key => $value) {
            if($key == $id){
                $productID = $value;
            }
        }
        if(!empty($productID)){
            foreach ($productID['cart'] as $key => $value) {
               $allPro[]= Product::findOrFail($value[0]);
            }
            if(empty($allPro)){
                return view('user.cart');
            }else{
                $sizeAll=Size::all();
                return view('user.cart',compact('allPro','sizeAll'));
            }
        }else{
            return view('user.cart');
        }
        
        
    }


    // cartShopping
    public function cartShopping(Request $request){
        $user = \Auth::user();
        $id = $user->id;
        $idProduct = $request->get('id');
        $request->session()->push('user.'.$id.'.cart',[$idProduct,$request->get('size')]);
        $cart =$request->session()->get('user');
        foreach ($cart as $key => $value) {
            if($key == $id){
                $result = $value;
            }
        }
        $count = count($result['cart']);
        return response()->json($count);
    }


    // change price when change quantity at view order product
    public function showPrice(Request $request){
        $data = $request->get('quantity');
        $id = $request->get('id');
        $product = Product::findOrFail($id);
        $end =  $product->promotion->end;
        $start =  $product->promotion->start;
        $today = date('Y-m-d');
        if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today)){
            $quantity = $product->price-($product->price* $product->promotion->unit/100);
        }
        if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today)){
            $quantity = Product::findOrFail($id)->price;
        }
        $out = number_format($quantity*$data);
        return response()->json($out);
    }




    // search all product with name product and prices
    public function search(Request $request){
        $data = $request->get('value');
        if(!empty($data)){
            $product = Product::where('name','like','%'.$data.'%')->orWhere('price','like','%'.$data.'%')->get();
        }else{
            $product =Product::paginate(12);
        }
        $count = count($product);
        $out="";
        if($count>=1){
            foreach ($product as $key => $value) {
                foreach ($value->images as $key => $val) {
                   $img = $val->path;
                   break;
                }
               $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$value->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$value->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$value->id.'" data-id="'.$value->id.'">'.$value->name.'</a></h3>
                          <p class="text-primary font-weight-bold text-left text-danger mt-2" data-id="'.$value->id.'">'.$value->price.' vnđ</p>
                          <p class="text-left"><strike>1000 <u>đ</u></strike> -50%</p>
                        </div>
                      </div>
                    </div>
                    
                    ';
            }
             return response()->json($out,200);
        }else{
            $err = "không tìm thấy sản phẩm";
             return response()->json($err,400);
        }
    }


    // search category
    public function searchCategory($id){
        $products = Product::where('category_id','=',$id)->get();
        $count = count($products);
        $out="";
        if($count>=1){
            foreach ($products as $key => $value) {
                $end =  $value->promotion->end;
                $start =  $value->promotion->start;
                $today = date('Y-m-d');
                foreach ($value->images as $key => $val) {
                   $img = $val->path;
                   break;
                }
                if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today)){
                    $quantity = $value->price-($value->price* $value->promotion->unit/100);
                    $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$value->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$value->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$value->id.'" data-id="'.$value->id.'">'.$value->name.'</a></h3>
                          <p class="text-primary font-weight-bold text-left text-danger mt-3" data-id="'.$value->id.'">'.number_format($quantity).' vnđ</p>
                          <p class="text-left"><strike>đ '.number_format($value->price).'</strike>&nbsp;&nbsp;-'.$value->promotion->unit.'%</p>
                        </div>
                      </div>
                    </div>
                    
                    ';
                }
                if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today)){
                    $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$value->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$value->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$value->id.'" data-id="'.$value->id.'">'.$value->name.'</a></h3>
                          <p class="text-primary font-weight-bold text-left text-danger mt-3" data-id="'.$value->id.'">'.number_format($value->price).' vnđ</p>
                        </div>
                      </div>
                    </div>
                    
                    ';
                }
            }
        }else{
            $out.='<p>Sản phẩm này không được tìm thấy!</p>';
        }
        return response()->json($out);
    }


    // start seach with size
    public function searchSize($id){
        $size=Size::findOrFail($id);
        foreach ($size->products as $key => $value) {
            $productID[]=$value->pivot->product_id;
        }
        $out='';
        if(count($productID)>=1){
            foreach ($productID as $key => $value) {
                $product = Product::findOrFail($value);
                 foreach ($product->images as $key => $vl) {
                     $img = $vl->path;
                     break;
                 }

                $end =  $product->promotion->end;
                $start =  $product->promotion->start;
                $today = date('Y-m-d');

                if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today)){
                    $quantity = $product->price-($product->price* $product->promotion->unit/100);
                    $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$product->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$product->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$product->id.'" data-id="'.$product->id.'">'.$product->name.'</a></h3>
                          <p class="text-primary font-weight-bold text-left text-danger mt-3" data-id="'.$product->id.'">'.number_format($quantity).' vnđ</p>
                          <p class="text-left"><strike>đ '.number_format($product->price).'</strike>&nbsp;&nbsp;-'.$product->promotion->unit.'%</p>
                        </div>
                      </div>
                    </div>
                    
                    ';
                }
                
                if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today)){
                    $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$product->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$product->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$product->id.'" data-id="'.$product->id.'">'.$product->name.'</a></h3>
                          <p class="text-primary font-weight-bold text-left text-danger mt-3" data-id="'.$product->id.'">'.number_format($product->price).' vnđ</p>
                        </div>
                      </div>
                    </div>
                    
                    ';
                    
                }

            }
        }else{
             $out.='<p>Sản phẩm này không được tìm thấy!</p>';
        }
        return response()->json($out);
    }


    // start click button view product
    public function view($id){
        $product=Product::find($id);
        $category=$product->category_id;
        $categoryAll = Product::where('category_id','like',$category)->get();
        return view('user.view',compact('product','categoryAll'));
    }


    // start show detail product
    public function showDetail($id){
        $product=Product::find($id);
        $category=$product->category_id;
        $categoryAll = Product::where('category_id','like',$category)->get();
        return view('user.detailProduct',compact('product','categoryAll'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product =Product::paginate(12);
        $category = Category::all();
        $size = Size::all();
        return view('user.home',compact('category','size','product'));
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
