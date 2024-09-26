<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Size;
use App\Product;
use App\Order;
use App\Comment;
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
            'name.required'=>'1.Name không được để trống',
            'tel.required'=>'2.Tel không được để trống',
            'tel.numeric'=>'2.Tel cần nhập là số',
            'email.required'=>'3.Email không được để trống',
            'email.email'=>'3.Email không hợp lý',
            'address.required'=>'4.Address không được để trống',
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
                $ProID[$i]=str_replace(array('{','}'), array('',''),$product[$i]);
                $priceID[$i]=$this->promotionDetail($ProID[$i]);
                $productID[$i]=str_replace(array('{','}'), array('',''),$product[$i]);
                $fill = $this->filterQuantity($productID[$i],$sizeID[$i][0],$sl[$i]);
            }
            if($fill){
              foreach ($productID as $key => $value) {
                  $order[$value]=['quantity'=>$sl[$key],'price'=>$priceID[$key],'size'=>$sizeID[$key][0]['id'],'status'=>1];
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
                  Mail::to($request->email)->send(new SendMail($mailOrder,$productName,$pathImage,$sizeName,$priceID,$sl));
                 $result="Bạn đã đặt hàng thành công!";
                 Session()->forget('user.'.$email);
              }
            }else{
              $result="Số lượng đặt hàng vượt quá số lượng trong kho!";
            }
            return response()->json($result);
        }        
    }

    // filter quantity
    public function filterQuantity($id,$size,$quantity){
      $product = Product::findOrFail($id);
      foreach ($product->sizes as $key => $value){
        if($value->pivot->size_id == $size->id){
          $allQuantity = $value->pivot->quantity;
        }
      }
      if($allQuantity-$quantity>=0){
        return true;
      }else{
        return false;
      }

    }

    //view checkout
    public function checkout(Request $request){
        $quantity = explode(';',$request->get('quantity'));
        $price = explode(';',trim($request->get('size')));
        $nameProduct = explode(';',trim($request->get('nameProduct')));
        // $total =$request->get('total');
        $size = explode(';', $request->get('sizeAll'));
        $productID = explode(';', $request->get('productID'));
        $user = \Auth::user();
        $total=0;
        for($i = 0; $i < count($productID)-1;$i++){
          $pricePro= $this->promotionDetail($productID[$i]);
          $total+=$pricePro*$quantity[$i];
        }
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
               $allPro[$key]= Product::findOrFail($value[0]);
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

    public function promotionDetail($id){
        $product = Product::findOrFail($id);
        $end =  isset($product->promotion->end)?$product->promotion->end:0;
        $start =  isset($product->promotion->start)?$product->promotion->start:0;
        $today = date('Y-m-d');
        if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today)){
            $quantity = $product->price-($product->price* $product->promotion->unit/100);
        }
        if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today) ||$end=0 || $start==0){
            $quantity = Product::findOrFail($id)->price;
        }
        return $quantity;
    }

    // change price when change quantity at view order product
    public function showPrice(Request $request){
        $data = $request->get('quantity');
        $id = $request->get('id');
        $product = Product::findOrFail($id);
        $end =  isset($product->promotion->end)?$product->promotion->end:0;
        $start =  isset($product->promotion->start)?$product->promotion->start:0;
        $today = date('Y-m-d');
        if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today)){
            $quantity = $product->price-($product->price* $product->promotion->unit/100);
        }
        if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today) || $start ==0 || $end ==0){
            $quantity = Product::findOrFail($id)->price;
        }
        // $number = str_replace(",",".",$quantity*$data);
        $out = number_format($quantity*$data);
        return response()->json($out);
    }




    // search all product with name product and prices
    public function search(Request $request){
        $data = $request->get('value');
        $sortPrice = $request->get('price');
        if(!empty($data)){
          if($request->get('price')==1){
            $product = Product::where('name','like','%'.$data.'%')->orderBy('price','asc')->paginate(12);
          }
          else if($request->get('price')==2){
            $product = Product::where('name','like','%'.$data.'%')->orderBy('price','desc')->paginate(12);
          }else{
            $product = Product::where('name','like','%'.$data.'%')->paginate(12);
          }
        }else{
            $product =Product::paginate(12);
        }
        $count = count($product);
        $out='';
        if($count>=1){
            foreach ($product as $key => $value) {
                $end =  isset($value->promotion->end)?$value->promotion->end:0;
                $start =  isset($value->promotion->start)?$value->promotion->start:0;
                $today = date('Y-m-d');
                foreach ($value->images as $key => $val) {
                   // $img = $val->path;
                  $imgs =$val->path;
                   break;
                }
                $total=0;
                foreach ($value->orders as $key => $val) {
                  if($value->id == $val->pivot->product_id){
                    $total+=$val->pivot->quantity;
                  }
                }
                 $img = isset($imgs)? $imgs:'1563271041_gym.jpg';
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
                        
                    ';
                    if($value->comments->avg('rate')>=1 && $value->comments->avg('rate')<2 ){
                        $out.='<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                    }
                    else if($value->comments->avg('rate')>=2 && $value->comments->avg('rate')<3 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=3 && $value->comments->avg('rate')<4 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($value->comments->avg('rate')>=4 && $value->comments->avg('rate')<5 ){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($value->comments->avg('rate')==5){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                        $out.='</div>
                              </div>
                            </div>';

                }
                if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today) || $end ==0 || $start ==0){
                    $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$value->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$value->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$value->id.'" data-id="'.$value->id.'">'.$value->name.'</a></h3>
                          <p class="text-primary font-weight-bold text-left text-danger mt-3" data-id="'.$value->id.'">'.number_format($value->price).' vnđ</p>
                        
                    
                    ';
                    if($value->comments->avg('rate')>=1 && $value->comments->avg('rate')<2 ){
                        $out.='<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                    }
                    else if($value->comments->avg('rate')>=2 && $value->comments->avg('rate')<3 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=3 && $value->comments->avg('rate')<4 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($value->comments->avg('rate')>=4 && $value->comments->avg('rate')<5 ){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($value->comments->avg('rate')==5){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                            $out.='</div>
                              </div>
                            </div>';
                }
                
            }

        }else{
            $out = "không tìm thấy sản phẩm";
             
        }
        return response()->json($out);
    }


    // search category
    public function searchCategory(Request $request){
        if($request->get('price')==1){
           $product = Product::where('category_id','=',$request->get('id'))->orderBy('price','asc')->paginate(12);
        }else if($request->get('price')==2){
          $product = Product::where('category_id','=',$request->get('id'))->orderBy('price','desc')->paginate(12);
        }else if(empty($request->get('price'))){
           $product = Product::where('category_id','=',$request->get('id'))->paginate(12);
        }
        $count = count($product);
        $out='';
        if($count>=1){
            foreach ($product as $key => $value) {
                $end =  isset($value->promotion->end)?$value->promotion->end:0;
                $start =  isset($value->promotion->start)?$value->promotion->start:0;
                $today = date('Y-m-d');
                foreach ($value->images as $key => $val) {
                   $imgs = $val->path;
                   break;
                }
                $total=0;
                foreach ($value->orders as $key => $val) {
                  if($value->id == $val->pivot->product_id){
                    $total+=$val->pivot->quantity;
                  }
                }
                $img = isset($imgs)? $imgs:'1563271041_gym.jpg';
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
                    ';
                    if($value->comments->avg('rate')>=1 && $value->comments->avg('rate')<2 ){
                        $out.='<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                    }
                    else if($value->comments->avg('rate')>=2 && $value->comments->avg('rate')<3 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=3 && $value->comments->avg('rate')<4 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($value->comments->avg('rate')>=4 && $value->comments->avg('rate')<5 ){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($value->comments->avg('rate')==5){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                        $out.='</div>
                              </div>
                            </div>';

                }
                if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today) || $start ==0 || $end==0){
                    $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$value->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$value->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$value->id.'" data-id="'.$value->id.'">'.$value->name.'</a></h3>
                          <p class="text-primary font-weight-bold text-left text-danger mt-3" data-id="'.$value->id.'">'.number_format($value->price).' vnđ</p>
                        
                    
                    ';
                    if($value->comments->avg('rate')>=1 && $value->comments->avg('rate')<2 ){
                        $out.='<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                    }
                    else if($value->comments->avg('rate')>=2 && $value->comments->avg('rate')<3 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=3 && $value->comments->avg('rate')<4 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($value->comments->avg('rate')>=4 && $value->comments->avg('rate')<5 ){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($value->comments->avg('rate')==5){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                            $out.='</div>
                              </div>
                            </div>';
                }
            }
        }else{
            $out.='<p>Sản phẩm này không được tìm thấy!</p>';
        }
        return response()->json(['out'=>$out,'product'=>$product]);
    }


    // start seach with search size
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
                $total=0;
                foreach ($product->orders as $key => $vl) {
                    if($vl->pivot->size==$id){
                        $total+=$vl->pivot->quantity;
                    }
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
                    ';
                    if($product->comments->avg('rate')>=1 && $product->comments->avg('rate')<2 ){
                        $out.='<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                    }
                    else if($product->comments->avg('rate')>=2 && $product->comments->avg('rate')<3 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($product->comments->avg('rate')>=3 && $product->comments->avg('rate')<4 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($product->comments->avg('rate')>=4 && $product->comments->avg('rate')<5 ){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($product->comments->avg('rate')==5){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                    $out.='</div>
                      </div>
                    </div>';
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
                    ';
                    if($product->comments->avg('rate')>=1 && $product->comments->avg('rate')<2 ){
                        $out.='<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                    }
                    else if($product->comments->avg('rate')>=2 && $product->comments->avg('rate')<3 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($product->comments->avg('rate')>=3 && $product->comments->avg('rate')<4 ){
                          $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($product->comments->avg('rate')>=4 && $product->comments->avg('rate')<5 ){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($product->comments->avg('rate')==5){
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                $out.= '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                    $out.='</div>
                      </div>
                    </div>';
                    
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
        $categoryAll = Product::where('category_id','=',$category)->get();
        return view('user.view',compact('product','categoryAll'));
    }


    // start show detail product
    public function showDetail($id){
        $product=Product::find($id);
        $comment = Comment::where('product_id','=',$id)->orderBy('id','desc')->paginate(4);
        $comments=Comment::where('product_id','=',$id)->get();
        $category=$product->category_id;
        $categoryAll = Product::where('category_id','=',$category)->get();

        // sản phẩm bán chạy
         $promotion = Product::join('promotions','products.id','=','promotions.product_id')->get('product_id');

        // danh sách khuyến mãi
        foreach ($promotion as $key => $value) {
          $idProduct[]=$value->product_id;
        }
        $promotions = Product::findOrFail($idProduct);
        return view('user.detailProduct',compact('product','categoryAll','comment','comments','promotions'));
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
        $date = date('Y-m-d');
        $promotion = Product::join('promotions','products.id','=','promotions.product_id')->get('product_id');

        // danh sách khuyến mãi
        foreach ($promotion as $key => $value) {
          $id[]=$value->product_id;
        }
        $promotions = Product::findOrFail($id);
        $products = Product::all();
        return view('user.home',compact('category','size','product','products','promotions'));
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
