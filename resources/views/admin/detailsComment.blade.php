@extends('layouts.admin')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

<!-- <script src="/js/app.js"></script> -->
<script src="/js/comment.js"></script>


<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<!-- <link rel="stylesheet" href="/css/admin.css"> -->
@endsection

@section('content')
<div class="contentCate " >
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>List Comments/ {{$nameProduct->name}}</h1>
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
                    <th>Vote</th>
                    <th>Comments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
               @foreach($comment as $value)
               		<tr>
               			<td width="10%">{{$value->id}}</td>
               			<td width="10%">{{$value->rate}}</td>
               			<td width="70%">{{$value->content}}</td>
               			<td width="10%"><a class="btn btn-danger deleteComment" data-id="{{$value->id}}">Delete</a></td>
               		</tr>
               @endforeach
            </tbody>
        </table>

		<div class="row">
        	<div class="col-12 d-flex justify-content-center" id="pageAdd">
        		{{$comment->links()}}
        	</div>
		</div> <!-- phân trang -->

	</div>
</div>

</div>
@endsection