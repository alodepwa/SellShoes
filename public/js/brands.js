$(document).ready(function(){

	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});

	$('.notification').hide();

	// start add brands
	$('#save').click(function(e){
		$('.mess').html('');
		e.preventDefault();
		console.log('alo');
		$('.notification').show();
		$.ajax({
			url:'/admin/brand',
			type:"POST",
			dataType:'json',
			data:{
				'name':$('input[name="name"]').val(),
				'description':$('input[name="description"]').val()
			},
			success:function(data){
				if(data !=undefined && data.errors !=undefined){
					$.each(data.errors,function(key,value){
						$('.notification').show();
						$('.mess').append(value+'</br>');
					});
				}
				else{
					$('.notification').hide();
					alert(data['success']);
				}
				$("#brandCreate").load(' #brandCreate');
				$("#pageAdd").load(" #pageAdd");
			},
			error:function(error){
				$('.mess').html("ERROR!!!");
			}

		});

	});  

		// start edit brand
		$(document).on('click','.edit_Cate' ,function(){
			var id = $(this).attr("data-id");
			var name = $(this).attr("data-name");
			$('input[name="name"]').val(name);
			$('#save_Edit_Cate').on("click", function(){
				$.ajax({
					url:'/admin/brand/'+id,
					type:'PUT',
					dataType:'json',
					data:{
						'name': $('#form_edit input[name="name"]').val(),
						'description':$('#form_edit input[name="description"]').val()
					},
					success:function(data){
						if(data !=undefined && data.errors !=undefined){
							$.each(data.errors,function(key,value){
								$('.notification').show();
								$('.mess').append(value);
							});
						}
						else{
							alert(data['success']);
						}
						$("#brandCreate").load(' #brandCreate');
					}
				});
		});

	});

});






$(document).ready(function(){
	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});

	$('.notification').hide();

	// start delete brands
	$(document).on('click','.delete_Cate',function(e){
		e.preventDefault();
		console.log('alo');
		var id =$(this).attr("data-id");
		console.log(id);
		if(confirm("Bạn có muốn xóa?")){
			$.ajax({
				url:'/admin/brand/'+id,
				type: 'DELETE',
				dataType:'json',
				data:{},
				success:function(data){
					alert(data['success']);
					$("#brandCreate").load(' #brandCreate');
					$("#pageAdd").load(" #pageAdd");
				},
				error:function(error){
					$('.mess').html(error);
				}
			}).done(function(data){
				// console.log(data);
				// alert(data['message']);
				// var dl = data['data'];
				// var info="";
				// for (var i = 0; i < dl.length; i++) {
				// 	info+="<tr>";
				// 	info+='<td width="10%">'+dl[i]["id"]+'</td>';
				// 	info+='<td width="25%">'+dl[i]["name"]+'</td>';
				// 	info+='<td width="40%">'+dl[i]["description"]+'</td>';
				// 	info+='<td width="25%">';
				// 	info+='<a class="btn btn-danger delete_Cate" data-id="'+dl[i]["id"]+'">Delete</a>';
				// 	info+='<a href=""  data-id="{{$value->id}}" data-name="'+dl[i]["name"]+'" data-target="#myModal2" data-toggle="modal" class="btn btn-info rounded-pill edit_Cate">Edit</a>';
				// 	info+='</td>';
				// 	info+="</tr>";
				// }
				// $('#bodyTable').html(info);
			});
		}

	});
});

