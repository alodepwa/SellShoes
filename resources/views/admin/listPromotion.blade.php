@extends('layouts.admin')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<script src="/js/app.js"></script>
<script src="/js/promotion.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
 <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>


<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script> -->
<link rel="stylesheet" href="/css/admin.css">
@endsection

@section('content')
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>List Promotions</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title ">
                        <ol class="breadcrumb  text-right">
                            <li class="active "><a href="" data-toggle="modal" data-target="#myModal" id="add" class="btn btn-success">Add</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


<div class="col-sm-12" id="table_Cate">
	<div class="container-fluid category">
		<table class="table" >
            <thead>
                <tr>
                    <th >#</th>
                    <th >Name</th>
                    <th >Product</th>
                    <th >Action</th>
                </tr>
            </thead>
            <tbody >
                @foreach($promotion as $value)
					<tr>
						<td width="10%">{{$value->id}}</td>
						<td width="35%">
							<a href="" class="hover" promotionId="{{$value->id}}">
							{{$value['name']}}
						</a>
						</td>
						<td width="35%"><a href="" class="hover" promotionId="{{$value->id}}">
							{{$value->product->name}}
						</a>
						</td>
						<td width="20%">
							<a class="btn btn-danger delete_Cate" data-id="{{$value->id}}">Delete</a>
							 <a href=""  data-id="{{$value->id}}" data-target="#myModal2" data-toggle="modal" class="btn btn-info rounded-pill edit_Cate">Edit</a>
						</td>

					</tr>
                @endforeach
            </tbody>
        </table>

		<div class="row">
        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
        		{{$promotion->links()}}
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
						<div class="row notification">
							<div class="container-fluid">
								<div class="alert alert-danger mess"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">

				<form method="post" action="" id="addPromotion">
					@csrf
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput">Promotion Name<small>*</small></label>
								<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Category Name...">
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput2">Unit<small>*</small></label>
								<input type="number" name="unit" class="form-control" id="formGroupExampleInput2" placeholder="Category Name...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput3">Start Day Promotion<small>*</small></label>
								<input type="date" name="start" class="form-control" id="formGroupExampleInput3" placeholder="Category Name...">
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput4">End Day Promotion<small>*</small></label>
								<input type="date" name="end" class="form-control" id="formGroupExampleInput4" placeholder="Category Name...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput5">Product ID<small>*</small></label>
						<select name="product_id"class="form-control" placeholder="Category Name...">
							@foreach($listProduct as $value)
							{{$value}}
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
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="container "><h4 class="modal-title">Edit Brand</h4></div>
					</div>
						<div class="row notification">
							<div class="container-fluid">
								<div class="alert alert-success mess"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">
				<form id="editPromotion">
					@csrf
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput">Promotion Name<small>*</small></label>
								<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Category Name...">
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput2">Unit<small>*</small></label>
								<input type="number" name="unit" max="100" class="form-control" id="formGroupExampleInput2" placeholder="Category Name...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<label for="formGroupExampleInput3">Start Day Promotion<small>*</small></label>
								<input type="date" name="start" class="form-control" id="formGroupExampleInput3" placeholder="Category Name...">
							</div>
							<div class="col-sm-6">
								<label for="formGroupExampleInput4">End Day Promotion<small>*</small></label>
								<input type="date" name="end" class="form-control" id="formGroupExampleInput4" placeholder="Category Name...">
							</div>
						</div>
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput5">Product ID<small>*</small></label>
						<select name="product_id"class="form-control" placeholder="Category Name...">
							@foreach($listProduct as $value)
							{{$value}}
							<option value="{{$value->id}}">{{$value->name}}</option>
							@endforeach
						</select>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveEditPromotion">Save changes</button>
				<button type="button" class="btn btn-secondary" id="close_Edit" data-dismiss="modal">Close</button>
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection