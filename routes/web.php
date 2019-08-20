<?php
// admin 




Route::group(['prefix'=>'admin','middleware'=>['checkLogin','checkAdmin','web']],function(){

	Route::get('/home',function(){
		return view('admin.home');
	})->name('homeAdmin');

	Route::get('orderList',function(){
		return view('admin.orderList');
	})->name('OrderList');

	// user
	Route::resource('user','UserController');

	//category
	Route::resource('category','CategoryController');
	// Route::get('reload','CategoryController@load');


	Route::resource('brand','BrandController');


	// size
	Route::resource('size','SizeController');


	//promotion
	Route::resource('promotion','PromotionController');
	Route::get('promotion/show/{id}','PromotionController@ShowInfo');
	Route::get('promotion/ShowInfo/{id}','PromotionController@ShowInfoAll');

	//product
	Route::resource('product','ProductController');
	Route::get('product/editPro/{id}','ProductController@ShowInfo');
	Route::get('product/popover/{id}','ProductController@ShowPopover');
	Route::get('product/search','ProductController@Search');
	Route::post('product/updateQuantity','ProductController@UpdateQuantity');
	Route::post('product/searchPoduct','ProductController@SearchProduct');
	Route::post('product/searchPoductQuickly','ProductController@SearchProductQuickly');
	// Route::post('product/capnhat','ProductController@capnhat');	

	//images
	Route::resource('image','ImageController');
	Route::get('image/show/{id}','ImageController@ShowInfo');
	Route::post('image/upload/{id}','ImageController@UpLoadImage');

	//comments
	Route::resource('comments','CommentController');
	Route::post('comments/approve','CommentController@read');

	//order
	Route::resource('orders','OrderController');
	Route::post('order/yes','OrderController@approveOrder');
});




	// view login user
	Route::get('login','LoginController@index')->name('formLogin');

	//logout user
	Route::get('logout','LoginController@logout')->name('logout');

	// send data from form login
	Route::post('login','LoginController@login')->name('login');


	Route::post('loginUser','LoginController@loginUser')->name('loginUser');

	//view register user
	Route::get('register','LoginController@create')->name('register');

	// send data from form register 
	Route::post('register','LoginController@store')->name('registerUser');

	// view home user
	Route::resource('user/page','LoadPageController');

	// send data search from users
	Route::post('user/search','LoadPageController@search');

	// search category
	Route::get('user/searchCategory/{id}','LoadPageController@searchCategory');

	// search size
	Route::get('user/searchSize/{id}','LoadPageController@searchSize');

	// view detail product not login
	Route::get('user/view/{id}','LoadPageController@view')->name('view');

	// view details product need login
	Route::get('user/showDetail/{id}','LoadPageController@showDetail')->name('showDetail');

	//filter prices product
	Route::post('user/filterPrice','filterPriceController@filterPrice');




Route::group(['prefix'=>'user','middleware'=>['web','checkLogin']],function(){

	// change price when change quantity at view order product
	Route::post('showPrice','LoadPageController@showPrice');

	// shopping cart
	Route::post('cartShopping','LoadPageController@cartShopping')->name('shopDetail');

	// details product shopping cart
	Route::get('cartDetail','LoadPageController@cartDetail')->name('cartDetail');

	// delete product in cart shopping
	Route::post('cartDetail','LoadPageController@deleteCart')->name('deleteCart');

	// send data from cart to checkout
	Route::post('checkout','LoadPageController@checkout')->name('checkout');

	// send data from user to admin
	Route::post('order','LoadPageController@order');

	// view management order
	Route::resource('mngOrders','ManagementOrderController');

	Route::get('sendmail','ManagementOrderController@sendMail')->name('sendMail');

});
