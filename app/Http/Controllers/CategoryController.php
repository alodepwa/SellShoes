<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Validator;
class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listCate = Category::all();
        $page = Category::orderBy('id','DESC')->paginate(7);
        return view('admin.listCategory',compact('listCate','page'));
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
            'name'=>'required'
        ],[
            'name.required'=>'Category name not null!'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else{
            $data = $request->all();
            $name = $request->get('name');
            $find = Category::where('name','=',$name)->first();
            if(empty($find)){
                $cate = Category::create($data);
                $result = ['dataSuccess'=>'Create Category Success!'];
            }else{
                $result=['dataFail'=>"Category Already Exists!"];
            }
            
            return response()->json($result);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->all();
        $Cate = Category::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name'=>'required'
        ],[
            'name.required'=>'Category name not null!'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            if($Cate->update($name)){
                $result = ['dataSuccess'=>"Update Success!!!"];
            }else{
                $result=['dataSuccess'=>'Update False!!!'];
            }
            return response()->json($result);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cate = Category::findOrFail($id);
        if($cate->delete()){
            foreach ($cate->products as $value) {
               $value->delete();
            }
            $listCategory = Category::all();
            $result = ['message'=>'Xóa thành công!!!','data'=>$listCategory];
        }else{
            $result = ['message'=>'Xóa thất bại!!!'];
        }
        return response()->json($result,200);
    }

}
