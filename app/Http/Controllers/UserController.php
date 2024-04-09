<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
 * @OA\Get(
 *   path="/api/users",
 *   summary="Get all users",
 *   description="Get all users without specifying an ID",
 *   tags={"User"},
 *   @OA\Response(
 *       response=200,
 *       description="OK",
 *       @OA\JsonContent(
 *           type="array",
 *           @OA\Items(
 *               type="object",
 *               @OA\Property(property="id", type="integer"),
 *               @OA\Property(property="name", type="string"),
 *               @OA\Property(property="email", type="string"),
 *               @OA\Property(property="password", type="string")
 *           )
 *       )
 *   )
 *)
 *
 * @OA\Post(
 *     path="/api/users",
 *     summary="Create a new users",
 *     description="Create a new users with the provided name, email and password",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="New User Name"),
 *             @OA\Property(property="email", type="string", example="user.1@gmail.com"),
 *             @OA\Property(property="password", type="string", example="user123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/api/users/{id}",
 *     summary="Get user by ID",
 *     description="Get a user by its ID",
 *     tags={"User"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user to retrieve",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 * 
 * @OA\PUT(
 *     path="/api/users/{id}",
 *     summary="Update user",
 *     description="Update an existing user",
 *     tags={"User"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user to update",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="Updated User Name"),
 *             @OA\Property(property="email", type="string", example="updated.user@gmail.com"),
 *             @OA\Property(property="password", type="string", example="updated123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/api/users/{id}",
 *     summary="Delete user",
 *     description="Delete an existing user",
 *     tags={"User"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
    public function index()
    {
        $users = User::all();
        return response()->json($users,200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required|min:3|max:15',
            'email' => 'string|required|email|unique:users',
            'password' => 'string|required|confirmed'
            ]);
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'string|required|min:3|max:15',
            'email' => 'string|required|email|unique:users',
            'password' => 'string|required'
            ]);
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}