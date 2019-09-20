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
<div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-0"><a href="{{route('page.index')}}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
          <div class="col-md-6 mb-0"><strong class="text-black">@if(session('success')){{session('success')}} @endif</strong></div>
        </div>
      </div>
     
    </div>
  
  <div class="container" id="autoload">
    <table class="table ">
      @foreach($orderID as $key => $value)
        <?php 
          foreach ($value->products as $key => $val) {
           $dateOrder = $val->pivot->created_at;
            $quantity = $val->pivot->quantity;
            $checkSize = $val->pivot->size;
          }
         ?>
         
              <?php 
        foreach ($value->products as $key => $val) {
          $name = $val->name;
          $productID= $val->pivot->product_id;
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
        @foreach($value->products as $val)
          <tr>
            <td width="30%">{{$val->name}}</td>
            @foreach($val->images as $vl)
              <td width="15%">
                <img src='{{asset("/upImage/$vl->path")}}' alt="">
              </td>
            @endforeach
            <td>Qti: {{$val->pivot->quantity}}</td>

            @foreach($size as $vl)
              @if($vl->id == $val->pivot->size)
                <td>
                  {{$vl->name}}
                </td>
                <?php $sizeId = $vl->id; ?>
              @endif
            @endforeach
            @if($val->pivot->status==1)
             <td><a href="#" data-id="{{$value->id}}" data-size="{{$sizeId}}" data-product ="{{$val->id}}" class="text-danger cancleOrder">Hủy đơn hàng</a></td>
             @endif
             @if($val->pivot->status==2)
             <td class="text-body">Đang giao</td>
             @endif
             @if($val->pivot->status==3)
             <td class="text-body">Đã hủy</td>
             @endif
            @if($val->pivot->status==4)
             <td class="text-success">Giao hàng thành công<br><a href="{{route('comment',[$val->id,$sizeId])}}" class="btn btn-warning">Viết nhận xét</a></td>
            @endif
          </tr>
        @endforeach

      @endforeach
   </table>
  </div>
   

@endsection