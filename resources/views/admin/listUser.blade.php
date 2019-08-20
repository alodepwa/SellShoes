@extends('layouts.admin')

@section('header')
<link rel="stylesheet" href="/css/admin.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<script src="/js/app.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="/css/admin.css">

<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="breadcrumbs d-flex align-items-center">
            <div class="col-sm-9">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>List User</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                        	<li class="active "><a href="" data-toggle="modal" data-target="#myModal" id="addUser" class="btn btn-success">Add User</a></li>	
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
        </div>
<div class="col-sm-12 listUser">
		<div class="container text-center">
			<table class="table" id="tableUser">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Email</th>
						
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($user as $value)
						<tr id="tableUser">
							<td width="10%">{{$value['id']}}</td>
							<td width="30%">{{$value['name']}}</td>
							<td width="30%">{{$value['email']}}</td>
							<td width="30%">
							<a class="btn btn-danger delete_Cate" data-id="{{$value->id}}">Delete</a>
							 <a href=""  data-id="{{$value->id}}" data-target="#myModal2" data-toggle="modal" class="btn btn-info rounded-pill editPro">Edit</a>
						</td>
						</tr>	
					@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="container-fluid text-center">
					<p class="text-center">
						{{ $user->links()}}
					</p>			
				</div>
			</div>
			
			
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
						<div class="row notificationE">
							<div class="container-fluid">
								<div class="alert alert-danger mess"></div>
							</div>
						</div>
				</div>
				
			</div>
			<div class="modal-body">

				<form method="post" id ="addProduct">
					@csrf
					<fieldset class="form-group">
						<label for="formGroupExampleInput">Name<small>*</small></label>
						<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Name...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput1">Email</label>
						<input type="email" name="email" class="form-control" id="formGroupExampleInput1" placeholder="Email...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput2">Password<small>*</small></label>
						<input type="password" name="password" class="form-control" id="formGroupExampleInput2" placeholder="Password...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput2">role<small>*</small></label>
						<select name="role_id"  class="form-control" >
							<option value="1">user</option>
							<option value="2">admin</option>
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
						<label for="formGroupExampleInput">Product Name<small>*</small></label>
						<input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Product Name...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput1">Status</label>
						<input type="text" name="status" class="form-control" id="formGroupExampleInput1" placeholder="Status...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput2">Price<small>*</small></label>
						<input type="number" step="0.001" min="0" name="price" class="form-control" id="formGroupExampleInput2" placeholder="Price...">
					</fieldset>
					<fieldset class="form-group">
						<label for="formGroupExampleInput2">Quantity<small>*</small></label>
						<input type="number" name="quantity" min="0" class="form-control" id="formGroupExampleInput2" placeholder="Quantity...">
					</fieldset>
					<fieldset class="form-group">
						<label >Description<small>*</small></label>
						<textarea name="description" id="descripton" class="form-control" placeholder="Description..."></textarea>
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
				<form id="formUpdate">
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


<script src="{{asset('/js/createUser.js')}}"></script>
@endsection
