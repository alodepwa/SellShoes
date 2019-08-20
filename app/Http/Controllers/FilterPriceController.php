<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
class FilterPriceController extends Controller
{
    // filter prices home user 
    public function filterPrice(Request $request){

        $min = $request->get('min');
        $max = $request->get('max');
        if(!empty($min) && !empty($max)){
            $product = Product::whereBetween('price',[$min,$max])->paginate(12);
        }
        $count = count($product);
        $out="";
        if($count>=1){
            foreach ($product as $key => $value) {
                foreach ($value->images as $key => $val) {
                   $img = $val->path;
                   break;
                }
               $out.='
                        <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                        <figure class="block-4-image" data-id="'.$value->id.'">
                            <a href="http://phpshoes.com/user/showDetail/'.$value->id.'"><img src="http://phpshoes.com/upImage/'.$img.'" alt="Image placeholder" class="img-fluid"></a>
                        </figure>
                        <div class="block-4-text p-4">
                          <h3><a href="http://phpshoes.com/user/showDetail/'.$value->id.'" data-id="'.$value->id.'">'.$value->name.'</a></h3>
                          <p class="text-primary font-weight-bold" data-id="'.$value->id.'">'.$value->price.' vnđ</p>
                        </div>
                      </div>
                    </div>
                    
                    ';
            }
             return response()->json($out,200);
        }else{
            $err = "không tìm thấy sản phẩm";
             return response()->json($err,400);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
