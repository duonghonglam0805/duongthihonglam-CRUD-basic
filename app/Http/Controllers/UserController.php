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
     * @OA\GET(
     *     path="/api/users",
     *     tags={"GET"},
     *     summary="Get users List",
     *     description="Get users List as Array",
     *     operationId="indexUser",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get users List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function index()
    {
        $users = User::all();
        return response()->json($users);
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
    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     description="Create a new user with the provided title and description",
     *     tags={"POST"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="New User Title"),
     *             @OA\Property(property="email", type="string", example="This is a new user email"),
     *             @OA\Property(property="password", type="string", example="This is a new user password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return response()->json($user, 200);
    }


    /**
     * Display the specified resource.
     */
            /**
     * @OA\GET(
     *     path="/api/users/{id}",
     *     tags={"GET{id}"},
     *     summary="Show User Details",
     *     description="Show User Details",
     *     operationId="showUser",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Product Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
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
    /**
     * @OA\PUT(
     *     path="/api/users/{id}",
     *     tags={"PUT"},
     *     summary="Update User",
     *     description="Update User",
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="User 1"),
     *              @OA\Property(property="email", type="string", example="honglam#gmail.com"),
     *              @OA\Property(property="password", type="string", example="1233"),
     *          ),
     *      ),
     *     operationId="updateUser",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Update User"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json('ok', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\DELETE(
     *     path="/api/users/{id}",
     *     tags={"DELETE"},
     *     summary="Delete User",
     *     description="Delete User",
     *     operationId="destroyUser",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete User"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return response()->json("Deleted", 200);
    }
}