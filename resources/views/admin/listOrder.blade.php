@extends('layouts.admin')


@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<script src="/js/app.js"></script>
<script src="/js/order.js"></script>


<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="/css/admin.css">
@endsection

@section('content')
	<div class="contentCate " >
		<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>List Orders</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right mr-5">
                    <form action="" class="mt-3">
                    	<select name="" id="listOrder">
                    		<option value="2">appove order</option>
                    		<option value="3">cancel order</option>
                    		<option value="1"selected>list order</option>
                    	</select>
                    </form>
                </div>
            </div>
        </div>

<div class="col-sm-12" >
	<div class="container-fluid category">
		<table class="table" id="table_Cate">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Prices</th>
                    <th>Size</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
            	@foreach($order as $value)
                    @foreach($value->products as $val)
                    @if($val->pivot->status==1)
            		<tr>
            			<td>{{$value->id}}</td>
            			<td>{{$value->name}}</td>
            			<td>{{$value->email}}</td>
            			<td >
                            
                            <p class="{{$value->id}}product" data-id="{{$val->id}}">{{$val->name}}</p>
            				
            			</td>
            			<td >
                            <p class="{{$value->id}}quantity" data-id="{{$val->pivot->quantity}}">{{$val->pivot->quantity}}</p>
            			</td>
            			<td>
            				<p>{{$val->pivot->price}}</p>
            			</td>
            			<td >
            				<?php 
                                $checkSize=$val->pivot->size;
                                foreach ($size as $key => $vl) {
                                    if($checkSize == $vl->id){
                                        echo '<p class="'.$value->id.'size" data-id='.$vl->id.'>'.$vl->name.'</p>';
                                    }
                                }
            				 ?>
            			</td>
            			<td>
            				<button class="btn-info yes" data-id="{{$value->id}}" data-product ="{{$val->id}}">yes</button>
            				<button class="btn-danger no" data-id="{{$value->id}}" data-product ="{{$val->id}}">no</button>
            			</td>
            		</tr>
                    @endif
            	   @endforeach
                @endforeach
            </tbody>
        </table>
		<div class="row page">
        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
        		
        	</div>
		</div> <!-- phân trang -->

	</div>
</div>

  

</div>


@endsection