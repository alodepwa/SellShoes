@extends('layouts.admin')

@section('header')
<link rel="stylesheet" href="/css/admin.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<!-- <script src="/js/app.js"></script> -->
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






<script src="{{asset('/js/createUser.js')}}"></script>
@endsection
