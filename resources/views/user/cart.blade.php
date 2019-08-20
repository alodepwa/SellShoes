@extends('layouts.user')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/css/detail.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="/js/cart.js"></script>
@endsection

@section('content')
<?php 
  $size = Session::get('user');
  $email = $size['email'][0];
  foreach ($size as $key => $value) {
      if($key == $email){
        $sizeProduct = $value;
      }
  }
?>
<div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="{{route('page.index')}}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row mb-5">
          <form class="col-md-12" method="post">
            <div class="site-blocks-table">
              <table class="table table-bordered" id="tableCart">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Image</th>
                    <th class="product-name">Product</th>
                    <th class="product-name">Size</th>
                    <th class="product-price">Price</th>
                    <th class="product-quantity">Quantity</th>
                    <th class="product-total">Total</th>
                    <th class="product-remove">Remove</th>
                  </tr>
                </thead>
                <tbody >
          				@if(!empty($allPro))
          				@foreach($allPro as $key => $value)
                   <?php 
                           $sizeCheck= $size[$email]['cart'][$key][1];
                            foreach ($sizeAll as $key => $vl) {
                               if($sizeCheck==$vl->id)
                                 $nameSize= $vl->name;
                            }
                          ?>
                    <tr>
                      <td class="product-thumbnail">
                        @foreach($value->images as $vl)
                          <img src='{{asset("upImage/$vl->path")}}' alt="Image" class="img-fluid">
                          @break
                        @endforeach
                      </td>
                      <td class="product-name">
                        <h2 class="h5 text-black nameProduct" data-id="{{$value->id}}">{{$value->name}}</h2>
                      </td>
                      <td class="product-name">
                        <p class="size" data-name={{$nameSize}}>{{$nameSize}}</p>
                      </td>
                      <td  class="{{$value->id}}price price">
                        <?php 
                            $end =  $value->promotion->end;
                            $start =  $value->promotion->start;
                            $today = date('Y-m-d');
                            if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today)){
                               echo $price= number_format($value->price-($value->price* $value->promotion->unit/100));
                            }
                            if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today)){
                                echo $price = number_format($value->price);
                            }
                         ?>
                      </td>

                      <td width="13%">
                        <div class="input-group mb-3" style="max-width: 120px;">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-primary js-btn-minus " data-click="{{$value->id}}" data-size="{{$sizeCheck}}" type="button">&minus;</button>
                          </div>
                          <input type="text" class="form-control text-center quantity {{$value->id}}and{{$sizeCheck}}" data-id="{{$value->id}}" disabled value="1" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                          <div class="input-group-append">
                            <button class="btn btn-outline-primary js-btn-plus" data-size="{{$sizeCheck}}" data-click="{{$value->id}}" type="button">&plus;</button>
                          </div>
                        </div>
                      </td>
                      <td class="{{$value->id}}and{{$sizeCheck}}money total">{{$price}}</td>
                      <td><a href="#" class="btn btn-primary btn-sm delete"data-id="{{$value->id}}">X</a></td>
                    </tr>
          				@endforeach
          				@endif
                </tbody>
              </table>
            </div>
          </form>
        </div>

        <div class="row">
          <div class="col-md-6">
          </div>
          <div class="col-md-6 pl-5">
            <div class="row justify-content-end">
              <div class="col-md-7">
                <div class="row">
                  <div class="col-md-12 text-right border-bottom mb-5">
                    <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <span class="text-black">Subtotal</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black" id="total"></strong>
                  </div>
                </div>
                <div class="row mb-5">
                  <div class="col-md-6">
                    <span class="text-black">Total</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black" ></strong>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <form action="{{route('checkout')}}" method="post">
                      @csrf
                      <input type="hidden" value="" name="size">
                      <input type="hidden" value="" name="nameProduct">
                      <input type="hidden" value="" name="quantity">
                      <input type="hidden" value="" name="total">
                      <input type="hidden" value="" name="productID">
                      <input type="hidden" value="" name="sizeAll">
                      <input type="submit"  class="btn btn-primary" value=" To Checkout ">
                    </form>
                    <!-- <button class="btn btn-primary" id="checkout"> To Checkout</button> -->
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection