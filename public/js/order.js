$(document).ready(function(){

	$.ajaxSetup({

		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		}

	});

	//list order approve order from user
	$(document).on('click','.yes',function(){
		var id = $(this).attr('data-id');
		var size=[];
		var product = [];
		var quantity=[];

		$('#table_Cate tbody tr').each(function(){
			$(this).find('.'+id+'product').each(function(){
				product+=$(this).attr('data-id')+";";
			});
			$(this).find('.'+id+'quantity').each(function(){
				quantity+=$(this).attr('data-id')+";";
			});
			$(this).find('.'+id+'size').each(function(){
				size+=$(this).attr('data-id')+";";
			});
		});

		$.ajax({
			url:'/admin/order/yes',
			type:'POST',
			dataType:'json',
			data:{
				'id':id,
				'size':size,
				'product':product,
				'quantity':quantity
			},success:function(data){
				console.log(data);
				alert(data);
				$('#table_Cate').load(' #table_Cate');
			}
		});

	});


	// disApprove oder from users
	$(document).on('click','.no',function(){
		var id = $(this).attr('data-id');
		if(confirm('bạn có muốn hủy đơn hàng này')){
			$.ajax({
				url:'/admin/orders/'+id,
				type:'DELETE',
				dataType:'json',
				success:function(data){
					alert(data);
					$('#table_Cate').load(' #table_Cate');
				}
			});
		}else{
			return false;
		}
		
	});

	$(document).on('change','#listOrder',function(){
		var value = $('#listOrder option:selected').val();
		$.ajax({
			url:'/admin/orders/list',
			type:'POST',
			dataType:'json',
			data:{
				'value':value
			},
			success:function(data){
				$('#table_Cate tbody').html(data['out']);
				$('#pageAdd').html(data['paginate']);
			}
		});
	});	


});