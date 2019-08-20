$(document).ready(function(){
	$.ajaxSetup({

		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		} 

	});

	$(".createUser .notification").hide();
	$('.createUser input[type="button"]').click(function(e){
		e.preventDefault();
		var name = $('.createUser input[name="name"]').val();
		console.log('alo');
		$(".createUser .notification").show();
		$.ajax({
				url: '/admin/user',
				type: 'POST',
				dataType:'json',
				data:{
					'name':name,
					'email':$('.createUser input[name="email"]').val(),
					'tel':$('.createUser input[name="tel"]').val(),
					'password':$('.createUser input[name="password"]').val(),
					'address':$('.createUser input[name="address"]').val(),
					'role_id':$('.createUser select[name="role_id"]').val(),
					'gender':$('.createUser input[name="gender"]').val(),
					'dayofbirth':$('.createUser input[name="dayofbirth"]').val()
				},
				success:function(data){

					$('.createUser .alertCreate').html(data.message);
					var tal = "";
					var dulieu = data['data'];
						for (var i = 0; i < dulieu.length; i++) {
							console.log(dulieu[i].name);
							tal+="<tr>";
							tal+="<td>"+dulieu[i]['name']+"</td>";
							tal+="<td>"+dulieu[i]['email']+"</td>";
							tal+="<td>"+dulieu[i]['tel']+"</td>";
							tal+="<td>"+dulieu[i]['address']+"</td>";
							tal+="</tr>";
						}
						
					$('.createUser .tableCreate').append(tal);
					alert(data['message']);
				},
				error:function(error,statusText){
					var info =error.statusText;
					$('.createUser .alertCreate').append(info);
				}
		});

	});
});

$(document).ready(function(){

	$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		}
	});


 	$(document).on('click','.deleteUser',function(e){
			e.preventDefault();
			var urlDelete = $(this).attr("data-url");
			console.log(urlDelete);
			var id = $(this).attr("data");
			if(confirm('Bạn chắc xóa tài khoản này?')){
				$.ajax({
					url:'/admin/user/'+id,
					type:'delete',
					dataType:'json',
					data:{
					},
					success:function(data){
						console.log('success');
						console.log(data);
						alert(data.message);
						var number=data.data[0]['countUser'];
						var showNumber = "<p >"+number+"</p>"
						$('#numberUser').html(showNumber);
					}
				});
			}else{
				return false;
			}
	});



	$('.breadcrumbs input[type="button"]').click(function(e){
 		e.preventDefault();
 		console.log('alo');
 		console.log($('input[name="name"]').val());
 		$.ajax({
 			url:'/admin/user/search',
 			type:"POST",
 			dataType:'json',
 			data:{
 				'name':$('input[name="name"]').val()
 			},
 			success:function(data){
 				console.log(data);
 			}

 		});
 	});
});



