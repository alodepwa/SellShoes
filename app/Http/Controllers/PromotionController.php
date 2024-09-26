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
            'unit'=>'required|numeric|max:99|min:0',
            'start'=>'required|date|after:yesterday',
            'end'=>'required|date|after:start',
            'product_id'=>'required'
        ],[
            'name.required'=>'1.Name Promotion không được để trống!',
            'unit.required'=>'2.Unit  không được để trống!',
            'unit.numeric'=>'2.Unit phải là số!',
            'unit.max'=>'2.Unit nhỏ hơn 100',
            'unit.min'=>'2.Unit lớn hơn 0',
            'start.required'=>'3.Start Day Promotion không được để trống!',
            'start.after'=>'3.Start Day Promotion phải sau ngày hôm qua!',
            'end.required'=>'4.End Day Promotion không được để trống!',
            'end.after'=>'4.End Day Promotion phải sau Start Day!',
            'product_id.required'=>'Product_id not null!',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else{
            $data = $request->all();
            $productId = Promotion::Where('product_id','=',$request->get('product_id'))->first();
            if(!empty($productId)){
                $result = ['dataFail'=>'Product ID Already Exists!'];
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
            'name'=>'required|unique:promotions,name,'.$id,
            'unit'=>'required|numeric|min:0|max:99',
            'start'=>'required|date|after:yesterday',
            'end'=>'required|date|after:start',
            'product_id'=>'required'
        ],[
            'name.required'=>'1.Name Promotion không được để trống!',
            'unit.required'=>'2.Unit  không được để trống!',
            'unit.numeric'=>'2.Unit phải là số!',
            'unit.max'=>'2.Unit nhỏ hơn 100',
            'unit.min'=>'2.Unit lớn hơn 0',
            'start.required'=>'3.Start Day Promotion không được để trống!',
            'start.after'=>'3.Start Day Promotion phải sau ngày hôm qua!',
            'end.required'=>'4.End Day Promotion không được để trống!',
            'end.after'=>'4.End Day Promotion phải sau Start Day!',
            'product_id.required'=>'Product_id not null!',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $promotion = Promotion::findOrFail($id);
            $data=$request->all();
            // $check = Promotion::where('name','=',$request->get('name'))->where('product_id','<>',$id)->first();
            // if(empty($check)){
                if($promotion->update($data)){
                    $result = ['message'=>"Update Success!"];
                }else{
                     $result = ['messageFail'=>"Update False!"];
                }
            // }else{
            //      $result = ['message'=>"Promotion Already Exists!"];
            // }
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
