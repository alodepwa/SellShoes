@extends('layouts.admin')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<script src="/js/app.js"></script>
<script src="/js/images.js"></script>

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script> -->
<link rel="stylesheet" href="/css/admin.css">
@endsection

@section('content')
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>List Image</h1>
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


<div class="col-sm-12" >
	<div class="container-fluid category">
		<table class="table" id="table_Cate">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product ID</th>
                    <th>Name Product</th>
                    <th>Images</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody >
                @foreach($image as $value)
					<tr>
						<td width="10%">{{$value->id}}</td>
						<td width="15%">{{$value->product_id}}</td>
						<td width="20%">{{$value->name}}</td>
						<td width="25%"><img src='{{asset("/upImage/$value->path")}}' width="50px" height="50px" alt=""></td>
						<td width="30%">
							<a class="btn btn-danger delete_Cate" data-id="{{$value->id}}">Delete</a>
							 <a href=""  data-id="{{$value->id}}" data-target="#myModal2" data-toggle="modal" class="btn btn-info rounded-pill edit_Cate">Edit</a>
						</td>
					</tr>
                @endforeach
            </tbody>
        </table>

		<div class="row">
        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
        		{{$image->links()}}
        	</div>
		</div> 
		<!-- phân trang -->

	</div>
</div>

  
<div class="modal fade" id="myModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="container "><h4 class="modal-title">Create Image</h4></div>
					</div>
						<div class="row notification">
							<div class="container-fluid">
								<div class="alert alert-success mess"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">
				<form method="post" id="formImage" >
					@csrf
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Image Name<small>*</small></label>
						<br>
						<label for="" class="1 text-danger"></label>
						<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Category Name...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Product Name<small>*</small></label>
						<select name="product_id" id=""class="form-control">
							@foreach($product as $value)
								<option value="{{$value->id}}">{{$value->name}}</option>
							@endforeach
						</select>
					</fieldset>
					<fieldset class="form-group mt-2">
						<label for="" class="2 text-danger"></label>
						<input type="file" name="image" id="image" placeholder="Category Name...">
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="save">Add</button>
				<button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
				
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
						<div class="container "><h4 class="modal-title">Edit Image</h4></div>
					</div>
						<div class="row notificationS">
							<div class="container-fluid">
								<div class="alert alert-success messS"></div>
							</div>
						</div>
						<div class="row notificationF">
							<div class="container-fluid">
								<div class="alert alert-danger messF"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">
				<form method="post" id="editImage" >
					@csrf
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Image Name<small>*</small></label>
						<br>
						<label for="" class="11 text-danger"></label>
						<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Category Name...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Product Name<small>*</small></label>
						<select name="product_id" id=""class="form-control">
							@foreach($product as $value)
								<option value="{{$value->id}}">{{$value->name}}</option>
							@endforeach
						</select>
					</fieldset>
					<fieldset class="form-group mt-2">
						<label for="" class="22 text-danger"></label>
						<input type="file" name="image" id="img" placeholder="Category Name...">
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
@endsection