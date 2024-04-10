<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *      @OA\Response(response=200, description="All Users" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $users = User::with('phone')->get();
        return $users;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
    * @OA\Post(
        *     path="/api/users",
        *     summary="Create a new user",
        *     description="Create a new user with the provided username, email, and password",
        *     tags={"Users"},
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\JsonContent(
        *             required={"name", "email", "password"},
        *             @OA\Property(property="name", type="string", example="honglam"),
        *             @OA\Property(property="email", type="string", format="email", example="example@gmail.com"),
        *             @OA\Property(property="number", type="string", example="0987654321"),
        *             @OA\Property(property="password", type="string", example="password123")
        *         )
        *     ),
        *    @OA\Response(response=200, description="Create New User" ),
       *     @OA\Response(response=400, description="Bad request"),
       *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
        ], [
            'name.required' => 'Bắt buộc',
            'name.string' => 'Bắt buộc là chuỗi',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'email.string' => 'Email bắt buộc là string',
            'password.required' => 'Password bắt buộc phải nhập',
            'password.string' => 'Password bắt buộc là string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
            if($request->has('number')){
                $phone = new Phone();
                $phone->number = $request->number;
                $phone->user_id = $user->id;
                $phone->save();
            }
            $userData = $user->toArray();
            $userData['phone'] = isset($phone) ? $phone->toArray() : null;
            return response()->json([
                'message' => 'Người dùng đã được tạo thành công',
                'data'=>$userData
            ],201);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User Detail" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(Request $id)
    {
        $user=User::with('phone')->find($id);
        if(!$user){
            return response()->json([
                'message'=>'Người dùng không tồn tại',
            ],404);
        }
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                  @OA\Property(property="name", type="string", example="john_doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *             )
     *         )
     *     ),
     *      @OA\Response(response=200, description="Update user" ),
    *      @OA\Response(response=400, description="Bad request"),
    *      @OA\Response(response=404, description="Resource Not Found"),
    *     security={{"bearerAuth":{}}}
    * )
    */
    public function update(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|min:3|max:15',
            'email' => 'string|email|unique:users,email',
        ], [
            'name.string' => 'Họ và tên bắt buộc là string',
            'name.min' => 'Họ và tên phải từ :min ký tự trở lên',
            'name.max' => 'Họ và tên phải nhỏ hơn :max ký tự',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'email.string' => 'Email bắt buộc là string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } else{
            // Tìm người dùng dựa trên id
            $user = User::with('phone')->find($userId);

            if (!$user) {
                return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
            }
            
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }
            if ($request->has('number')) {
                $phone = $user->phone;
                if (!$phone) {
                    $phone = new Phone();
                    $phone->user_id = $user->id;
                }
                // Cập nhật thông tin số điện thoại
                $phone->number = $request->number;
                $phone->save();
            }

            $user->save();
            $userData = $user->toArray();
            $userData['phone'] = isset($phone) ? $phone->toArray() : null;
            return response()->json([
                'message' => 'Thông tin của người dùng đã được cập nhật thành công',
                'data'=>$userData
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a specific user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Delate user" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return "Delete user success";
    }
}