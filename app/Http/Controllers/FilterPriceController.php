<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
class FilterPriceController extends Controller
{
    // filter prices home user 
    public function filterPrice(Request $request){
        $value = $request->get('value');
        if($value==1){
            $product = Product::orderBy('price','asc')->paginate(12);
        }
        else if($value==2){
             $product = Product::orderBy('price','desc')->paginate(12);
        }
        $count = count($product);

        $out="";
        if($count>=1){
            foreach ($product as $key => $value) {
                $end =  $value->promotion->end;
                $start =  $value->promotion->start;
                $today = date('Y-m-d');
                foreach ($value->images as $key => $val) {
                   $img = $val->path;
                   break;
                }
                $total=0;
                foreach ($value->orders as $key => $val) {
                  if($value->id == $val->pivot->product_id){
                    $total+=$val->pivot->quantity;
                  }
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
            return response()->json($out,200);
        }else{
            $err = "không tìm thấy sản phẩm";
             return response()->json($err,400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
