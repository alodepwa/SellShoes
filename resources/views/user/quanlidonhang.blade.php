@extends('layouts.user')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/css/detail.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="/js/cart.js"></script>
<style>
  img{
    width: 80px;
    height: 80px;
  }
</style>
@endsection

@section('content')
<a href="{{route('sendMail')}}" class="btn btn-success">send mail</a>
<div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="{{route('page.index')}}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
        </div>
      </div>
    </div>
  
  <div class="container">
    <table class="table table-striped">
      @foreach($orderID as $key => $value)
        <?php 
          foreach ($value->products as $key => $val) {
           $dateOrder = $val->pivot->created_at;
            $quantity = $val->pivot->quantity;
            $checkSize = $val->pivot->size;
          }
         ?>
         <thead>
          <tr>
            <th class="product-thumbnail">Đặt ngày:{{$dateOrder}}</th>
          </tr>
        </thead>
              <?php 
        foreach ($value->products as $key => $val) {
          $name = $val->name;
          $productID = $val->pivot->product_id;
        }
        foreach ($product as $key => $vl) {
          if($vl->id == $productID){
            foreach ($vl->images as $key => $vll) {
              $img = $vll->path;
              break;
            }
          }
        }

       ?>
      <tbody>
       <tr>
         <td width="30%">{{$name}}</td>
         <td width="15%"><img src='{{asset("/upImage/$img")}}' alt=""></td>
         <td>Qti: {{$quantity}}</td>
         <td>
            <?php 
              foreach ($size as $key => $val) {
                if($val->id == $checkSize)
                  echo $val->name;
              }
            ?>
        </td>
         @if($value->status==1)
         <td><a href="" class="text-danger">Hủy</a></td>
         @endif
         @if($value->status==2)
         <td class="text-info">Đang giao</td>
         @endif
       </tr>
     </tbody>
      @endforeach
   </table>
  </div>
   

@endsection