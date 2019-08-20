<?php

namespace App\Http\Controllers;

use App\Promotion;
use App\Product;
use Illuminate\Http\Request;
use Validator;
class PromotionController extends Controller
{

    // show infomation promotion
    public function ShowInfoAll($id){
        $promotion=Promotion::find($id);
        return response()->json($promotion);
    }

    // show infomation promotion
    public function ShowInfo($id){
        $promotion =Promotion::findOrFail($id);
        return response()->json($promotion);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotion=Promotion::paginate(7);
        $listProduct = Product::select('id','name')->get();
        return view('admin.listPromotion', compact('promotion','listProduct'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'unit'=>'required',
            'start'=>'required',
            'end'=>'required',
            'product_id'=>'required'
        ],[
            'name.required'=>'Promotion name not null!',
            'unit.required'=>'Unit not null!',
            'unit.max'=>'Unit less 100!',
            'start.required'=>'Start day not null!',
            'end.required'=>'End day not null!',
            'product_id.required'=>'Product_id not null!',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else{
            $data = $request->all();
            $productId = Promotion::Where('product_id','=',$request->get('product_id'))->first();
            if(!empty($productId)){
                $result = ['dataSuccess'=>'Product ID Already Exists!'];
            }else{
                $promotion = Promotion::create($data);
                $result = ['dataSuccess'=>'Promotion Create Success!'];
            }
            return response()->json($result);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit(Promotion $promotion,$id)
    {

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
         $validator = Validator::make($request->all(),[
            'name'=>'required',
            'code'=>'required',
            'unit'=>'required',
            'start'=>'required',
            'end'=>'required',
            'product_id'=>'required'
        ],[
            'name.required'=>'Promotion name not null!',
            'code.required'=>'Code not null!',
            'unit.required'=>'Unit not null!',
            'start.required'=>'Start day not null!',
            'end.required'=>'End day not null!',
            'product_id.required'=>'Product_id not null!',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $promotion = Promotion::findOrFail($id);
            $data=$request->all();
            $check = Promotion::where('name','=',$request->get('name'))->where('product_id','<>',$id)->first();
            if(empty($check)){
                if($promotion->update($data)){
                    $result = ['message'=>"Update Success!"];
                }else{
                     $result = ['message'=>"Update False!"];
                }
            }else{
                 $result = ['message'=>"Promotion Already Exists!"];
            }
            return response()->json($result);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promotion=Promotion::findOrFail($id);
        if($promotion->delete()){
            $result = ['message'=>"Delete Promotion Success!!!"];
        }else{
             $result = ['message'=>"Delete Promotion False!!!"];
        }
        return response()->json($result);
    }
}
