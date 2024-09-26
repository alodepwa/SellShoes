<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::paginate(10);
        $numberUser = User::count();
        return view('admin.listUser',compact('user','numberUser'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.createUser');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->all();
        $email =User::where('email','=',$request->only('email')['email'])->first();
        if(!$email){
            $data['password']=bcrypt($request->get('email'));
            $user = User::create($data);
            $tenUser = User::orderBy('id','DESC')->take(5)->get();
            $respone =[
                'message'=>'Create Success',
                'data'=>$tenUser,
                'status'=>201];
        }else{
            $respone =[
                'message'=>'Create Failed'
                        ];
        }
        return response()->json($respone);
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
        $user = User::find($id);
        if( $user->delete()){
            $count = User::selectRaw('count(id)as countUser')->get();
            return response()->json(['message'=>'Delete success','data'=>$count],200);
        }
    }

}
