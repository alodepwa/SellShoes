<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Shoppers &mdash; Colorlib e-Commerce Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="/venderUser/fonts/icomoon/style.css">

    <link rel="stylesheet" href="/venderUser/css/bootstrap.min.css">
    <link rel="stylesheet" href="/venderUser/css/magnific-popup.css">
    <link rel="stylesheet" href="/venderUser/css/jquery-ui.css">
    <link rel="stylesheet" href="/venderUser/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/venderUser/css/owl.theme.default.min.css">
  
    <link rel="stylesheet" href="/css/user.css">

    <link rel="stylesheet" href="/venderUser/css/aos.css">

    <link rel="stylesheet" href="/venderUser/css/style.css">
    @yield('header')
  </head>
  <body>
  <div class="site-wrap">
    <header class="site-navbar" role="banner">
      <div class="site-navbar-top">
        <div class="container">
          <div class="row align-items-center">

            <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
            </div>

            <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
              <div class="site-logo">
                <a href="index.html" class="js-logo-clone">Lê Văn Trung</a>
              </div>
            </div>

            <div class="col-6 col-md-4 order-3 order-md-3 text-right">
              <div class="site-top-icons">
                <ul>
                  @if(Session()->has('user.email'))
                  <?php 
                    $user = \Auth::user();
                    $name= $user->name;
                   ?>
                    <li id="usr">
                      <a href="" id="user" ><span class="icon icon-person"></span>{{$name}}</a>
                      <ul id="userChild">
                        <li><a href="{{route('mngOrders.index')}}">Đơn Hàng</a></li>
                        <li><a href="{{route('logout')}}">Đăng Xuất</a></li>
                      </ul>
                    </li>
                  @endif
                   @if(!Session()->has('user.email'))
                    <li><a href="{{route('register')}}"><span class="icon icon-person">Register</span></a></li>
                  @endif
                 
                  @if(!Session()->has('user.email'))
                    <li><a href="{{route('formLogin')}}"><span class="icon icon-person">Login</span></a></li>
                  @endif
                  <!-- <li><a href="#"><span class="icon icon-heart-o"></span></a></li> -->
                  
                  <li>
                    <a href="{{route('cartDetail')}}" class="site-cart">
                      <span class="icon icon-shopping_cart"></span>
                      <span class="count">
                        <?php 
                         $id = Session()->get('user.email.0');
                         $session = Session()->get('user');
                         if($session){
                            foreach ($session as $key => $value) {
                              if($key==$id){
                                $cart = $value;
                              }
                           }
                           if(!empty($cart)){
                             echo  $cartNumber = count($cart['cart']);
                           }else{
                            echo '0';
                           }
                         }else{
                          echo '0';
                         }
                             
                        ?>
                      </span>
                    </a>
                  </li> 
                  <li class="d-inline-block d-md-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
                </ul>
              </div> 
            </div>

          </div>
        </div>
      </div> 
      
    </header>

    
    
    @yield('content')

    <footer class="site-footer border-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="row">
              <div class="col-md-12">
                <h3 class="footer-heading mb-4">Navigations</h3>
              </div>
              <div class="col-md-6 col-lg-4">
                <ul class="list-unstyled">
                  <li><a href="#">Sell online</a></li>
                  <li><a href="#">Features</a></li>
                  <li><a href="#">Shopping cart</a></li>
                  <li><a href="#">Store builder</a></li>
                </ul>
              </div>
              <div class="col-md-6 col-lg-4">
                <ul class="list-unstyled">
                  <li><a href="#">Mobile commerce</a></li>
                  <li><a href="#">Dropshipping</a></li>
                  <li><a href="#">Website development</a></li>
                </ul>
              </div>
              <div class="col-md-6 col-lg-4">
                <ul class="list-unstyled">
                  <li><a href="#">Point of sale</a></li>
                  <li><a href="#">Hardware</a></li>
                  <li><a href="#">Software</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
            <h3 class="footer-heading mb-4">Promo</h3>
            <a href="#" class="block-6">
              <h3 class="font-weight-light  mb-0">Finding Your Perfect Shoes</h3>
              <p>Promo from  nuary 15 &mdash; 25, 2019</p>
            </a>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="block-5 mb-5">
              <h3 class="footer-heading mb-4">Contact Info</h3>
              <ul class="list-unstyled">
                <li class="address">203 Fake St. Mountain View, San Francisco, California, USA</li>
                <li class="phone"><a href="tel://23923929210">+2 392 3929 210</a></li>
                <li class="email">emailaddress@domain.com</li>
              </ul>
            </div>

            <div class="block-7">
              <form action="#" method="post">
                <label for="email_subscribe" class="footer-heading">Subscribe</label>
                <div class="form-group">
                  <input type="text" class="form-control py-4" id="email_subscribe" placeholder="Email">
                  <input type="submit" class="btn btn-sm btn-primary" value="Send">
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <p>
           
            Copyright &copy;<script data-cfasync="false" src=""></script><script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> 
           
            </p>
          </div>
          
        </div>
      </div>
    </footer>
  </div>

  <script src="/venderUser/js/jquery-3.3.1.min.js"></script>
  <script src="/venderUser/js/jquery-ui.js"></script>
  <script src="/venderUser/js/popper.min.js"></script>
  <script src="/venderUser/js/bootstrap.min.js"></script>
  <script src="/venderUser/js/owl.carousel.min.js"></script>
  <script src="/venderUser/js/jquery.magnific-popup.min.js"></script>
  <script src="/venderUser/js/aos.js"></script>

  <script src="/venderUser/js/main.js"></script>
    
  </body>
</html>