<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all posts",
     *     tags={"Posts"},
     *     @OA\Response(response=200, description="All Post"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     description="Create a new post with the provided title and description",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="New Post Title"),
     *             @OA\Property(property="description", type="string", example="This is a new post description")
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:100|min:5',
            'description' => 'required|max:50|min:10',
        ], [
            'title.required' => 'Title bắt buộc phải nhập',
            'title.min' => 'Title phải từ :min ký tự trở lên',
            'title.max' => 'Title phải từ :max ký tự trở lên',
            'title.unique' => 'Title đã tồn tại trên hệ thống',
            'description.required' => 'Description bắt buộc phải nhập',
            'description.min' => 'Description phải từ :min ký tự trở lên',
            'description.max' => 'Description phải từ :max ký tự trở lên',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        }
        return Post::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get one posts",
     *     tags={"Posts"},
     *          @OA\Parameter(
     *              name="id",
     *               in="path",
     *              description="Post ID",
     *              required=true,
     *              @OA\Schema(type="integer")
     *          ),
     *     @OA\Response(response=200, description="One Post"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(Post $post)
    {
        return $post;
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
     *     path="/api/posts/{id}",
     *     summary="Update post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="New Post Title"),
     *                 @OA\Property(property="description", type="string", example="This is a new post description")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Update Post"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:100|min:5',
            'description' => 'required|max:50|min:10',
        ], [
            'title.required' => 'Title bắt buộc phải nhập',
            'title.min' => 'Title phải từ :min ký tự trở lên',
            'title.max' => 'Title phải từ :max ký tự trở lên',
            'title.unique' => 'Title đã tồn tại trên hệ thống',
            'description.required' => 'Description bắt buộc phải nhập',
            'description.min' => 'Description phải từ :min ký tự trở lên',
            'description.max' => 'Description phải từ :max ký tự trở lên',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        }
        $post->update($request->all());
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),    
     *     @OA\Response(response=200, description="Delete Post"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return "Delete success";
    }
}