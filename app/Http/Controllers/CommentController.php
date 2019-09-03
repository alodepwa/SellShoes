<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Product;
use App\Size;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //commentPosst
    public function commentPost(Request $request){
        $user = \Auth::user();
        $data=['rate'=>$request->get('rating'),'content'=>$request->get('description'),'status'=>1,'user_id'=>$user->id,'product_id'=>$request->get('productID')];
        if(Comment::create($data)){
            return redirect()->route('mngOrders.index')->with('success','Đánh giá thành công!');
        }
    }
    // comment
    public function comment($id,$id1){
        $product = Product::findOrFail($id);
        $size = Size::where('id','=',$id1)->get('name');
        return view('user.comment',compact('product','size'));
    }

    public function read(){
        $comment =Comment::where('status','=',2)->get();
        $users;
        foreach ($comment as $key=> $value){
             $users[$key]=$value->user->name;
        }
        return response()->json(['data'=>$comment,'user'=>$users]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comment =Comment::where('status','=',1)->paginate(7);
        return view('admin.listComent',compact('comment'));

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
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment=Comment::findOrFail($id);
        $comment->status = 1;
        if($comment->save()){
            return response()->json("Update Success!");
        }
        return response()->json("Update False!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        ($comment->delete())?$result = ['dataSuccess'=>'Delete Success!']:$result = ['dataSuccess'=>'Delete False!'];
        return response()->json($result);   
    }
}
