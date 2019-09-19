<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use Illuminate\Http\Request;
use Validator;

class ImageController extends Controller
{   
    // start upload image
    public function UpLoadImage(Request $request,$id){
           $validator = Validator::make($request->all(),[
            'name'=>'required',
            'image'=>'required|mimes:jpg,png,jpeg'
        ],[
            'name.required'=>'1.Tên ảnh không được để trống!',
            'image.required'=>'2.File ảnh không được để trống!',
            'image.mimes'=>'2.Đuôi ảnh không đúng định dạng!'
        ]);

        if($validator->fails()){
            return Response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $image = Image::findOrFail($id);
            $name = $request->get('name');
            $product_id = $request->get('product_id');
            $img =$request->file('image')->getClientOriginalExtension();
            $nameImage = time().'_'.$name.'.'.$img;
            $data =['name'=>$name,'path'=>$nameImage,'product_id'=>$product_id];
            $upload = $request->file('image')->move('upImage',$nameImage);
            if($image->update($data)){
                $result =['dataSuccess'=>"Cập nhật thành công!"];
            }else{
                $result =['dataFail'=>"Cập nhật thất bại!"];
            }
            return Response()->json($result);
        }
    }

    public function ShowInfo($id){
        $img = Image::find($id);
        return response()->json($img);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $image = Image::paginate(7);
        $product = Product::select('id','name')->get();
        return view('admin.listImage',compact('image','product'));
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
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'image'=>'required|mimes:jpg,png,jpeg',
        ],[
            'name.required'=>'1.Tên ảnh không được để trống!',
            'image.required'=>'2.File ảnh không được để trống!',
            'image.mimes'=>'2.Đuôi ảnh không đúng định dạng!'
           
        ]);
        if($validator->fails()){
            return Response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $name = $request->get('name');
            $product_id = $request->get('product_id');
            $img =$request->file('image')->getClientOriginalExtension();
            $nameImage = time().'_'.$name.'.'.$img;
            $data =['name'=>$name,'path'=>$nameImage,'product_id'=>$product_id];
            $upload = $request->file('image')->move('upImage',$nameImage);
            Image::create($data)? $result =['dataSuccess'=>"Thêm mới thành công!"]: $result =['dataSuccess'=>"Thêm mới thất bại!"];
            return Response()->json($result);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $img = Image::findOrFail($id);
        $img->delete()? $result =['dataSuccess'=>"Xóa thành công!"]: $result =['dataSuccess'=>"Xóa thất bại!"];
        return Response()->json($result);
    }
}
