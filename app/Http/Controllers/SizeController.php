<?php

namespace App\Http\Controllers;

use App\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Product;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $size=Size::paginate(7);
        return view('admin.listSize',compact('size'));
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
        $data = $request->all();
        $name=$request->get('name');
        $check=Size::where('name','=',$name)->first();
        $validator=Validator::make($data,[
            'name'=>'required'
        ]);
        if($validator->fails()){
             return Response()->json(['errors'=>$validator->errors()->all()]);
        }
       if(empty($check)){
            Size::create($data);
            $result=['datasuccess'=>'Create Success!!!'];
        }else{
            $result=['datasuccess'=>'Data Already Exists!!!'];
        }  
        return Response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(Size $size)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);
        $nameUpdate = $request->get('name');
        $validator = Validator::make($request->all(),[
            'name'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $name = Size::where('name','=',$request->name)->where('id','<>',$id)->first();
        if(!empty($name)){
            $result=['errors'=>'Data Already Exists!!!'];
        }else{
            if($size->update(['name'=>$request->name])){
                $result=['datasuccess'=>'Update Success!!!'];
            }else{
               $result=['errors'=>'Update False!!!'];
            }
        }
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        if($size->delete()){
            $product = Product::where('size_id','=',$id);
            $product->delete();
            $size->products()->sync([]);
            $result=['message'=>"Delete Success!!!"];
        }else{
            $result =['message'=>'Delete False!!!'];
        }
        return response()->json($result);
    }
}
