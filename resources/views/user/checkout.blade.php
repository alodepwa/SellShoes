@extends('layouts.user')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/css/detail.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="/js/cart.js"></script>
@endsection

@section('content')

<div class="site-wrap">

    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.html">Home</a> <span class="mx-2 mb-0">/</span> <a href="{{route('cartDetail')}}">Cart</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Checkout</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row">
          <!-- <div class="col-md-6 mb-5 mb-md-0">
            <h2 class="h3 mb-3 text-black">Billing Details</h2>
            <div class="p-3 p-lg-5 border">
              <div class="alert alert-danger err"></div>
              <div class="form-group">
                <label for="c_country" class="text-black">City <span class="text-danger">*</span></label>
                <select id="city" class="form-control">
                  <option value="1">Select a City</option>    
                  <option value="2">Đà Nẵng</option>    
                  <option value="3">Hồ Chí Minh</option>    
                  <option value="4">Hà Nội</option>    
                  <option value="5">Nha Trang</option>    
                  <option value="6">Tam Kỳ</option>    
                  <option value="7">Hội An</option>    
                </select>
              </div>
              <div class="form-group row">
                <div class="col-md-12">
                  @if($errors->any())
                    @foreach($errors->all() as $err)
                    <li>$err</li>
                    @endforeach
                  @endif
                  <label for="c_fname" class="text-black">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="c_fname" name="name">
                </div>
                
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="c_address" name="address" placeholder="Street address">
                </div>
              </div>
              <div class="form-group row mb-5">
                <div class="col-md-6">
                  <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="email">
                </div>
                <div class="col-md-6">
                  <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="tel" placeholder="Phone Number">
                </div>
              </div>

            </div>
          </div> -->

          <div class="col-md-6 mb-5 mb-md-0">
            <h2 class="h3 mb-3 text-black">Billing Details</h2>
            <div class="p-3 p-lg-5 border">
              <div class="alert alert-danger err"></div>
              <div class="form-group">
                <label for="c_country" class="text-black">Country <span class="text-danger">*</span></label>
                <select id="city" class="form-control">
                  <option value="1">Select a City</option>    
                  <option value="2">Đà Nẵng</option>    
                  <option value="3">Hồ Chí Minh</option>    
                  <option value="4">Hà Nội</option>    
                  <option value="5">Nha Trang</option>    
                  <option value="6">Tam Kỳ</option>    
                  <option value="7">Hội An</option>    
                </select>
              </div>
              <div class="form-group row">
                <div class="col-md-12">
                  @if($errors->any())
                    @foreach($errors->all() as $err)
                    <li>$err</li>
                    @endforeach
                  @endif
                  <label for="c_fname" class="text-black">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" value="{{$user->name}}" id="c_fname" name="name">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12">
                  <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" value="{{$user->address}}" id="c_address" name="address" >
                </div>
              </div>
              <div class="form-group row mb-5">
                <div class="col-md-6">
                  <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" value="{{$user->email}}" name="email">
                </div>
                <div class="col-md-6">
                  <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="tel" value="{{$user->tel}}">
                </div>
              </div>

              <div class="form-group">
                <label for="c_ship_different_address" class="text-black" data-toggle="collapse" href="#ship_different_address" role="button" aria-expanded="false" aria-controls="ship_different_address"><input type="checkbox" value="1" id="c_ship_different_address"> Ship To A Different ?</label>
                <div class="collapse" id="ship_different_address">
                  <div class="py-2">

                    <div class="form-group">
                      <label for="c_diff_country" class="text-black">Country <span class="text-danger">*</span></label>
                      <select id="city_diff" class="form-control">
                        <option value="1">Select a City</option>    
                        <option value="2">Đà Nẵng</option>    
                        <option value="3">Hồ Chí Minh</option>    
                        <option value="4">Hà Nội</option>    
                        <option value="5">Nha Trang</option>    
                        <option value="6">Tam Kỳ</option>    
                        <option value="7">Hội An</option>    
                      </select>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-12">
                        @if($errors->any())
                          @foreach($errors->all() as $err)
                          <li>$err</li>
                          @endforeach
                        @endif
                        <label  class="text-black">Name <span class="text-danger">*</span></label> 
                        <input type="text" class="form-control" placeholder="Name" name="dname">
                      </div>
                    </div>
                    
                   <div class="form-group row">
                      <div class="col-md-12">
                        <label  class="text-black">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value=""  name="daddress" placeholder="Street address">
                      </div>
                    </div>
                    <div class="form-group row mb-5">
                      <div class="col-md-6">
                        <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
                        <input type="email" placeholder="Email" class="form-control" name="demail">
                      </div>
                      <div class="col-md-6">
                        <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="dtel" placeholder="Phone Number">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            
            <div class="row mb-5">
              <div class="col-md-12">
                <h2 class="h3 mb-3 text-black">Your Order</h2>
                <div class="p-3 p-lg-5 border">
                  <table class="table site-block-order-table mb-5">
                    <thead>
                      <th>Product</th>
                      <th>Total</th>
                    </thead>
                    <tbody>

                     <?php 
                        $count = count($nameProduct);
                        for ($i=0; $i<$count-1 ; $i++){ 
                          echo "<tr>";
                          echo "<td class='name' data-size='{{$size[$i]}}' data-number='{{$quantity[$i]}}' data-product='{{$productID[$i]}}'>$nameProduct[$i]<strong class='mx-2'> X $quantity[$i]</strong> </td>";
                          echo "<td class='text-black'>$price[$i]</td>";
                          echo "</tr>";
                        }
                      ?>
                      <tr>
                        <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                        <td class="text-black">{{$total}}</td>
                      </tr>
                      <tr>
                        <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                        <td class="text-black font-weight-bold"><strong>{{$total}}</strong></td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="form-group">
                    <button class="btn btn-primary btn-lg py-3 btn-block" id="order">Place Order</button>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- </form> -->
      </div>
    </div>

    
  </div>








@endsection