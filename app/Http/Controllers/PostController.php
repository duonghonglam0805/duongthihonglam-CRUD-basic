<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
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
    public function __construct()
    {
        $this->posts = new Post;
    }
    private $posts;
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     description="Create a new post with the provided title and description",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "user_id"},
     *             @OA\Property(property="title", type="string", example="New Post Title"),
     *             @OA\Property(property="description", type="string", example="This is a new post description"),
     *              @OA\Property(property="user_id", type="string", example="This is a new post description")
     *         )
     *     ),
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="Create New Post" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|min:5|max:100',
            'description' => 'required|min:5|max:50',
        ], [
            'title.required' => 'Post title is required.',
            'title.min' => 'Post title must be at least :min characters.',
            'title.unique' => 'Post title has already been taken.',
            'title.max' => 'Post title must be at most :max characters.',
            'description.required' => 'Post description is required.',
            'description.min' => 'Post description must be at least :min characters.',
            'description.max' => 'Post description must be at most :max characters.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        }

        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => $request->input('user_id')
        ];

        $post = Post::create($data);
        if ($post) {
            return response()->json(['message' => 'create succes'], 200);
        } else {

            return response()->json(['message' => 'error'], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Show Post Detail"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show(Request $request, string $id)
        {
            try {
                $post = Post::with('user')->find($id);
                return response()->json([
                    'post' => [
                        'id' => $post->id,
                        'title' => $post->title,
                        'description' => $post->description,
                        'created_by' => $post->user->name
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Post not found'], 404);
            }
        }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update a specific post",
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
     *                 @OA\Property(property="title", type="string", example="New Post Title"),
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
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|min:5|max:100',
            'description' => 'required|min:5|max:50',
        ], [
            'title.required' => 'Post title is required.',
            'title.min' => 'Post title must be at least :min characters.',
            'title.unique' => 'Post title has already been taken.',
            'title.max' => 'Post title must be at most :max characters.',
            'description.required' => 'Post description is required.',
            'description.min' => 'Post description must be at least :min characters.',
            'description.max' => 'Post description must be at most :max characters.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $dataUpdate = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        $post->update($dataUpdate);
        if (!$post) {
            return response()->json(['error' => 'Failed to update post'], 500);
        }

        return response()->json(['message' => 'Post updated successfully'], 200);
    }




    /**
     * Remove the specified resource from storage.
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
    public function destroy(string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        $post->user()->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}