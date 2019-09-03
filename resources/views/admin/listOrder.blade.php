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
            		<tr>
            			<td>{{$value->id}}</td>
            			<td>{{$value->name}}</td>
            			<td>{{$value->email}}</td>
            			<td >
            				@foreach($value->products as $vl)
            					<p class="{{$value->id}}product" data-id="{{$vl->id}}">{{$vl->name}}</p>
            				@endforeach
            			</td>
            			<td >
            				@foreach($value->products as $quantity)
            					<p class="{{$value->id}}quantity" data-id="{{$quantity->pivot->quantity}}">{{$quantity->pivot->quantity}}</p>
            				@endforeach
            			</td>
            			<td>
            				@foreach($value->products as $quantity)
            					<p>{{$quantity->pivot->price}}</p>
            				@endforeach
            			</td>
            			<td >
            				<?php 
            					foreach ($value->products as $vl) {
            						$checkSize = $vl->pivot->size;
            						foreach ($size as $key => $vl) {
            						if($checkSize == $vl->id){
            							echo '<p class="'.$value->id.'size" data-id='.$vl->id.'>'.$vl->name.'</p>';
            						}
            					}
            						
            					}

            				 ?>
            			</td>
            			<td>
            				<button class="btn-info yes" data-id="{{$value->id}}">yes</button>
            				<button class="btn-danger no" data-id="{{$value->id}}">no</button>
            			</td>
            		</tr>
            	@endforeach
            </tbody>
        </table>

		<div class="row">
        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
        		{{$order->links()}}
        	</div>
		</div> <!-- phân trang -->

	</div>
</div>

  
<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="container "><h4 class="modal-title">Comments</h4></div>
					</div>
				</div>
				
			</div>
			<div class="modal-body">
				<table class="table table-inverse">
					<thead>
						<tr>
							<th>#</th>
		                    <th>User</th>
		                    <th>Email</th>
		                    <th>Product</th>
		                    <th>Quantity</th>
		                    <th>Prices</th>
		                    <th>Size</th>
						</tr>
					</thead>
					<tbody id="approve">
						@foreach($list as $value)
		            		<tr>
		            			<td>{{$value->id}}</td>
		            			<td>{{$value->name}}</td>
		            			<td>{{$value->email}}</td>
		            			<td >
		            				@foreach($value->products as $vl)
		            					<p class="{{$value->id}}product" data-id="{{$vl->id}}">{{$vl->name}}</p>
		            				@endforeach
		            			</td>
		            			<td >
		            				@foreach($value->products as $quantity)
		            					<p class="{{$value->id}}quantity" data-id="{{$quantity->pivot->quantity}}">{{$quantity->pivot->quantity}}</p>
		            				@endforeach
		            			</td>
		            			<td>
		            				@foreach($value->products as $quantity)
		            					<p>{{$quantity->pivot->price}}</p>
		            				@endforeach
		            			</td>
		            			<td >
		            				<?php 
		            					foreach ($value->products as $vl) {
		            						$checkSize = $vl->pivot->size;
		            						foreach ($size as $key => $vl) {
		            						if($checkSize == $vl->id){
		            							echo '<p class="'.$value->id.'size" data-id='.$vl->id.'>'.$vl->name.'</p>';
		            						}
		            					}
		            						
		            					}

		            				 ?>
		            			</td>
		            		</tr>
		            	@endforeach
					</tbody>
					<div class="row">
			        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
			        		{{$list->links()}}
			        	</div>
					</div> 
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>


@endsection