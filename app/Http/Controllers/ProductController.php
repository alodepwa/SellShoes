<?php

namespace App\Http\Controllers;

use App\Product;
use App\Brand;
use App\Size;
use App\Category;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    // search product with direction
    public function SearchProductQuickly(Request $request){
        $value = $request->get('value');
        $out="";
        if(!empty($value)){
            $product = Product::where('name','like','%'.$value.'%')->orWhere('status','like','%'.$value.'%')->orWhere('price','like','%'.$value.'%')->get();
            $out='<ul class="dropdown-menu fluid" width="100%" style="display:block;position:relative">';
            $count = count($product);
            if($count>=1){
                foreach ($product as $dl) {
                    $out.='<li width="500px"><a href="#">'.$dl["name"].'</a></li>';
                }
                $out.='</ul>';
            }
        }
        return response()->json($out);
    }

    // search product with name product or status or price(admin)
    public function SearchProduct(Request $request){
        $value = $request->get('value');
        if(!empty($value)){
            $product = Product::where('name','like','%'.$value.'%')->orWhere('status','like','%'.$value.'%')->orWhere('price','like','%'.$value.'%')->get();
        }else{
            $product= $product=Product::orderBy('id','DESC')->paginate(7);
        }
        $count = count($product);
        $outTable='';
        if($count>=1){
            $outTable="
                 <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";
            foreach ($product as $key=> $dl) {
                foreach ($dl->sizes as $key => $value) {
                    $size = $value->name;
                }
                $outTable.="<tr>";
                $outTable.= '<td width="10%">'.$dl["id"].'</td>';
                $outTable.= '<td width="20%%"><a class="hover" productID="'.$dl["id"].'">'.$dl['name'].'</a></td>';
               $outTable.='<td width="20%"><a class="hover" productID="'.$dl['id'].'">
                           '.$size.'
                        </a></td>';
                $outTable.= '<td width="20%"><a class="hover" productID="'.$dl["id"].'">'.$dl['price'].'</a></td>';
                $outTable.= '<td width="30%">
                            <a class="btn btn-danger delete_Cate" data-id="'.$dl["id"].'">Delete</a>
                             <a href=""  data-id="'.$dl["id"].'" data-target="#myModal2" data-toggle="modal" class="btn btn-info rounded-pill editPro">Edit</a>
                             <a class="btn btn-success updateQuantity" data-target="#myModal3" data-toggle="modal" data-id="'.$dl["id"].'">Quantity</a>
                        </td>';
                $outTable.="</tr>";
            }
        }
        $outTable.="</tbody>";
        return response()->json($outTable);
    }

    // search name product in admin
    public function Search(Request $request){
        $product = Product::where('name','LIKE','%'.$request->name.'%')->get();
        return response()->json($product);
    }


    // show popover
    public function ShowPopover($id){
        $info = Product::find($id);
        foreach ($info->sizes as $value) {
            $quantity = $value->pivot->quantity;
        }
        $img = $info->images()->first();
        $infoImg = $img->path;
        $brand=$info->brand();
        if(!empty($infoImg)){
            $out='<p><lable><img src="/upImage/'.$infoImg.'" width="100px"height="100px" alt=""></label></p>
                <p><lable>Category: '.$info->category->name.'</label></p>
                <p><lable>Brand: '.$info->brand->name.'</label></p>
                <p><lable>Quantity: '.$quantity.'</label></p>
                <p><lable>Description: '.$info->description.'</label></p>';
        }
        if(empty($infoImg)){
            $out='<p><lable>Category: '.$info->category->name.'</label></p>
                <p><lable>Brand: '.$info->brand->name.'</label></p>
                <p><lable>Quantity: '.$quantity.'</label></p>
                <p><lable>Description: '.$info->description.'</label></p>';
        }
        return Response()->json($out);
        
    }

    // update quantity with size and id product
    public function UpdateQuantity(Request $request){
        $id = $request->get('id');
        $size_id = $request->get('size_id');
        $validator=Validator::make($request->all(),[
            'quantity'=>'required|numeric'
        ],
        [
            'quantity.required'=>'Số lượng sản phẩm không được để trống!',
            'quantity.numeric'=>'Giá trị cần nhập vào là số!'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $info=Product::findOrFail($id);
            $data = $request->get('quantity');
            foreach ($info->sizes as $value) {
                $old = $value->pivot->quantity;
                $size[]= $value->id;
            }
            $quantity = $old+$data;
            if($info){
                foreach ($size_id as $key => $value) {
                   $size[$value]=['quantity'=>$quantity];
                }
                    $info->sizes()->sync($size);
                $result=['dataSuccess'=>'Update Quantity Success!'];
            }else{
                 $result=['dataSuccess'=>'Update Quantity False!'];
            }
            return response()->json($result);
        }
    }


    public function ShowInfo($id){
        $info = Product::find($id);
       foreach ($info->sizes as $value) {
          $quantity = $value->pivot->quantity;
            $size[] = $value->id;
       }
        return response()->json(['data'=>$info,'quantity'=>$quantity,'size'=>$size]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product=Product::orderBy('id','DESC')->paginate(7);
        $listBrand = Brand::select('id','name')->get();
        $listSize = Size::select('id','name')->get();
        $listCategory = Category::select('id','name')->get();
        return view('admin.listProduct',compact('product','listBrand','listCategory','listSize'));
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
        $data = $request->except('quantity','size_id');
        $quantity = $request->get('quantity');
        $size_id= $request->get('size_id');

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'price'=>'required|numeric',
            'description'=>'required',
            'quantity'=>'required|numeric',
            'brand_id'=>'required',
            'size_id'=>'required',
            'category_id'=>'required'
        ],[
            'name.required'=>'Tên sản phẩm không được để trống!',
            'quantity.required'=>'Số lượng sản phẩm không được để trống!',
            'price.numeric'=>'Giá sản phẩm cần nhập là số!',
            'price.required'=>'Giá sản phẩm không được để trống!',
            'description.required'=>'Mô tả sản phẩm không được để trống!',
            'brand_id.required'=>'Brand không được để trống!',
            'size_id.required'=>'Size sản phẩm không được để trống!',
            'category_id.required'=>'Category sản phẩm không được để trống!',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $check = Product::where('name','=',$request->get('name'))->first();
            if(!$check){
                foreach ($size_id as $key => $value) {
                    $size[$value]= ['quantity'=>$quantity];
                }
                Product::create($data)->sizes()->sync($size); 
                $result = ['dataSuccess'=>'Create Product Success!!!'];
            }else{
                $result = ['dataSuccess'=>'Product Already Exists!!!'];
            }
            return Response()->json($result);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'price'=>'required|numeric',
            'description'=>'required',
            'quantity'=>'required|numeric',
            'brand_id'=>'required',
            'size_id'=>'required',
            'category_id'=>'required'
        ],
        [
            'name.required'=>'Tên sản phẩm không được để trống!',
            'quantity.required'=>'Số lượng sản phẩm không được để trống!',
            'price.numeric'=>'Giá sản phẩm cần nhập là số!',
            'price.required'=>'Giá sản phẩm không được để trống!',
            'description.required'=>'Mô tả sản phẩm không được để trống!',
            'brand_id.required'=>'Brand không được để trống!',
            'size_id.required'=>'Size sản phẩm không được để trống!',
            'category_id.required'=>'Category sản phẩm không được để trống!',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $data = $request->except('quantity');
            $product = Product::findOrFail($id);
            $size_id = $request->get('size_id');
            $quantity = $request->get('quantity');
            foreach ($size_id as $key => $value) {
                $size[$value]=['quantity'=>$quantity];
            }
            if($product->update($data)){
                $product->sizes()->sync($size);
                $result=["message"=>'Update Success!!!'];
            }else{
                $result=["message"=>'Update False!!!'];
            }
            return Response()->json($result);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $product=Product::find($id);
        if($product->delete()){
            $product->promotion()->delete();
            $product->sizes()->detach();
            $product->promotion()->delete();
            $product->userComments()->detach();
            $product->images()->delete();
            $result=['message'=>'Delete Success!!!'];
        }
        return Response()->json($result);
    }

}
