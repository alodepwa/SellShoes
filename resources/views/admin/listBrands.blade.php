@extends('layouts.admin')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<script src="/js/app.js"></script>
<script src="/js/brands.js"></script>


<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="/css/admin.css">
@endsection

@section('content')
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>List Brands</h1>
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
		<table class="table" id="brandCreate" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="bodyTable" >
                @foreach($brands as $value)
					<tr>
						<td width="10%">{{$value->id}}</td>
						<td width="25%">{{$value['name']}}</td>
						<td width="40%">{{$value['description']}}</td>
						<td width="25%">
							<a class="btn btn-danger delete_Cate" data-id="{{$value->id}}">Delete</a>
							 <a href=""  data-id="{{$value->id}}" data-name="{{$value->name}}" data-target="#myModal2" data-toggle="modal" class="btn btn-info rounded-pill edit_Cate">Edit</a>
						</td>

					</tr>
                @endforeach
            </tbody>
        </table>

		<div class="row">
        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
        		{{$brands->links()}}
        	</div>
		</div> <!-- phân trang -->

	</div>
</div>

  
<div class="modal fade" id="myModal">
	<div class="modal-dialog" role="document">
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

				<form method="post" action="{{route('brand.store')}}">
					@csrf
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Brand Name<small>*</small></label>
						<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Category Name...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Brand Description</label>
						<input type="text" name="description" class="form-control" id="formGroupExampleInput" placeholder="Category Name...">
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
				<form id="form_edit">
					@csrf
					<fieldset class="form-group">
						<label for="name">Brand Name <small>*</small></label>
						<input type="text" name="name" id="ediCate" class="form-control" value="">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Brand Description</label>
						<input type="text" name="description" class="form-control" id="formGroupExampleInput" placeholder="Category Name...">
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