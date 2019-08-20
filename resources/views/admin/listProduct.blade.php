@extends('layouts.admin')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<script src="/js/app.js"></script>
<script src="/js/product.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
 <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script> -->
<link rel="stylesheet" href="/css/admin.css">
<style>
    .twitter-typeahead,
    .tt-hint,
    .tt-input,
    .tt-menu{
         width: auto ! important;
         font-weight: normal;
     }
</style>
@endsection

@section('content')
<div class="breadcrumbs d-flex align-items-center">
            <div class="col-sm-3">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>List Product</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 ">
            	<div class="container box" style="position: absolute;">
            		<form>
	            		@csrf
	        			<input class="form-control" style="margin-top: -20px;" id="search" type="text" placeholder="Search..." width="100%">
	        			<div id="searchProduct"></div>
	            	</form>
            	</div>
            </div>
            <div class="col-sm-3 ">
                <div class="page-header float-right">
                    <div class="page-title ">
                        <ol class="breadcrumb  text-right">
                            <li class="active "><a href="" data-toggle="modal" data-target="#myModal" id="add" class="btn btn-success">Add Products</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


<div class="col-sm-12" >
	<div class="container-fluid category">
		<table class="table"id="table_Cate" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product as $value)
					<tr>
						<td width="10%">{{$value->id}}</td>
						<td width="20%%"><a class="hover" productID="{{$value->id}}">{{$value['name']}}</a></td>
						<td width="20%"><a class="hover" productID="{{$value->id}}">
							@foreach($value->sizes as $val)
								{{$val->name}}
							@endforeach
						</a></td>
						<td width="20%"><a class="hover" productID="{{$value->id}}">{{$value['price']}}</a></td>
						<td width="30%">
							<a class="btn btn-danger delete_Cate" data-id="{{$value->id}}">Delete</a>
							 <a href=""  data-id="{{$value->id}}" data-target="#myModal2" data-toggle="modal" class="btn btn-info rounded-pill editPro">Edit</a>
							 <a class="btn btn-success updateQuantity" data-target="#myModal3" data-toggle="modal" data-id="{{$value->id}}">Quantity</a>
						</td>

					</tr>
                @endforeach
            </tbody>
        </table>

		<div class="row">
        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
        		{{$product->links()}}
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
						<div class="container "><h4 class="modal-title">Create Brand</h4></div>
					</div>
						<div class="row notificationS">
							<div class="container-fluid">
								<div class="alert alert-success messS"></div>
							</div>
						</div>
						<div class="row notificationE">
							<div class="container-fluid">
								<div class="alert alert-danger messE"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">

				<form method="post" id ="addProduct">
					@csrf
					<fieldset class="form-group">
						<div class="row d-flex align-items-center">
							<div class="col-6">
								<label for="formGroupExampleInput">Product Name<small>*</small></label>
								<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Product Name...">
							</div>
							<div class="col-6">
								<label for="formGroupExampleInput1">Status</label>
								<input type="text" name="status" class="form-control" id="formGroupExampleInput1" placeholder="Status...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput2">Price<small>*</small></label>
								<input type="number" step="0.001" min="0" name="price" class="form-control" id="formGroupExampleInput2" placeholder="Price...">
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput2">Quantity<small>*</small></label>
								<input type="number" name="quantity" min="0" class="form-control" id="formGroupExampleInput2" placeholder="Quantity...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<label >Description<small>*</small></label>
						<textarea name="description" id="descripton" class="form-control" placeholder="Description..."></textarea>
					</fieldset>
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput3">Category ID<small>*</small></label>
								<select name="category_id" class="form-control"  id="formGroupExampleInput3">
									@foreach($listCategory as $value)
										<option value="{{$value->id}}">{{$value->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput4">Brand ID<small>*</small></label>
								<select name="brand_id" class="form-control"  id="formGroupExampleInput4">
									@foreach($listBrand as $value)
										<option value="{{$value->id}}">{{$value->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput5">Size ID<small>*</small></label>
						<select name="size_id[]" class="form-control"  id="size" multiple>
							@foreach($listSize as $value)
								<option value="{{$value->id}}">{{$value->name}}</option>
							@endforeach
						</select>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="save">Add</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- modal edit categories -->
<div class="modal fade" id="myModal2">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="container "><h4 class="modal-title">Edit Product</h4></div>
					</div>
						<div class="row notification">
							<div class="container-fluid">
								<div class="alert alert-danger mess"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">
				<form id="formEdit">
					@csrf
					<fieldset class="form-group">
						<div class="row d-flex align-items-center">
							<div class="col-6">
								<label for="formGroupExampleInput">Product Name<small>*</small></label>
								<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Product Name...">
							</div>
							<div class="col-6">
								<label for="formGroupExampleInput1">Status</label>
								<input type="text" name="status" class="form-control" id="formGroupExampleInput1" placeholder="Status...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput2">Price<small>*</small></label>
								<input type="number" step="0.001" min="0" name="price" class="form-control" id="formGroupExampleInput2" placeholder="Price...">
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput2">Quantity<small>*</small></label>
								<input type="number" name="quantity" min="0" class="form-control" id="formGroupExampleInput2" placeholder="Quantity...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<label >Description<small>*</small></label>
						<textarea name="description" id="descripton" class="form-control" placeholder="Description..."></textarea>
					</fieldset>
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput3">Category ID<small>*</small></label>
								<select name="category_id" class="form-control"  id="formGroupExampleInput3">
									@foreach($listCategory as $value)
										<option value="{{$value->id}}">{{$value->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput4">Brand ID<small>*</small></label>
								<select name="brand_id" class="form-control"  id="formGroupExampleInput4">
									@foreach($listBrand as $value)
										<option value="{{$value->id}}">{{$value->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput5">Size ID<small>*</small></label>
						<select name="size_id[]" class="form-control"  id="formGroupExampleInput5" multiple>
							@foreach($listSize as $value)
								<option value="{{$value->id}}">{{$value->name}}</option>
							@endforeach
						</select>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="save_Edit_Cate">Save changes</button>
				<button type="button" class="btn btn-secondary" id="close_Edit" data-dismiss="modal">Close</button>
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="myModal3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="container "><h4 class="modal-title">Update Quantity</h4></div>
					</div>
						<div class="row notification">
							<div class="container-fluid">
								<div class="alert alert-danger mess"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">
				<form id="formUpdateQuantity">
					@csrf
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Product Name<small>*</small></label>
						<input type="text" name="name" class="form-control" id="formGroupExampleInput" disabled placeholder="Product Name...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput2">Quantity<small>*</small></label>
						<input type="number" name="quantity" min="0" class="form-control" id="formGroupExampleInput2" placeholder="Quantity...">
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="updateQuantity">Save changes</button>
				<button type="button" class="btn btn-secondary" id="close_Edit" data-dismiss="modal">Close</button>
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection