<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use DB;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = User::orderBy('id', 'DESC');

        if ($request->email) {
            $users = $users->where('email', $request->email);
        }

        if ($request->per_page) {
            $users = $users->paginate($request->per_page);
        }else{
            $users = $users->get();
        }

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'email' => 'email|unique:users,email',
        ],
        [

        ]
        );

        if ($validator->fails()) {
            return response()->json([
                'messages' => $validator->errors()
            ], 400);
        }

        $response = DB::transaction(function () use($request) {

            $user = User::create($request->only('name', 'password', 'email'));
            // $user->address()->create([
            //     'user_id' => $user->id,
            //     'type' => '123/4',

            // ]);

            return response()->json([
                'message' => 'เพิ่มผู้ใช้งานสำเร็จ',
                'data' => $user
            ], 200);
        });

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'nullable',
            'email' => ['required', Rule::unique('users','email')->ignore($user)],
        ], 
        [
            'name.required' => 'กรุณาใส่ชื่อ'
        ] 
        );

        if ($validator->fails()) {
            return response()->json([
                'messages' => $validator->errors()->all()
            ], 400);
        }

        $response = DB::transaction(function () use($request ,$user) {

             $user->update($request->only('name', 'password', 'email'));

            return response()->json([
                'message' => 'แก้ไขสำเร็จเเล้ว',
                // 'data' => $user
            ], 200);
        });

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();  
        return response()->noContent();
    }
}
