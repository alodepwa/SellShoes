<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use Validator;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::orderBy('id',"DESC")->paginate(7);
        return view('admin.listBrands',compact('brands'));
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
            'description'=>'required'
        ],
        [
            'name.required'=>'Brand name not null!',
            'description.required'=>'Description not null!'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $dt = $request->all();
            $data =Brand::where('name','=',$request->name)->first();
            if(empty($data)){
                $brand =Brand::create($dt);
                $result = ['success'=>'Create Success!'];
            }else{
                $result = ['success'=>'Brand Already Exists!'];
            }
            return response()->json($result);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   $validator = Validator::make($request->all(),[
            'name'=>'required'
        ],
        [
            'name.required'=>'Brand name not null!'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $data = Brand::findOrFail($id);
            $name = Brand::where('name','=',$request->name)->where('id','<>',$id)->first();
            if(!empty($name)){
                $result=['success'=>'Update False!!!'];
            }else{
                if($data->update(['name'=>$request->name,'description'=>$request->description])){
                    $result=['success'=>'Update Success!!!'];
                }else{
                   $result=['success'=>'Update False!!!'];
                }
            }
            
            return response()->json($result);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        if($brand->delete()){
            foreach ($brand->products as $value) {
                $value->delete();
            }
            $allBrand = Brand::orderBy('id',"DESC")->get();
            $result=['success'=>'Delete  Success!!!','data'=>$allBrand];
        }else{
            $result = ['success'=>'Delete False!!!'];
        }
        return response()->json($result);
    }
}
