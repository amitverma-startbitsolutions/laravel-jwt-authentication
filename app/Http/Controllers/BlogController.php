<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * blogController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
	 * @return mixed
	 */
	public function index()
	{
	    $blogs = $this->user->blogs()->get(['title', 'description'])->toArray();

	    return $blogs;
	}

	 /**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
	    $blog = $this->user->blogs()->find($id);

	    if (!$blog) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, blog with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    return $blog;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request)
	{
	    $this->validate($request, [
	        'title' => 'required',
	        'description' => 'required',
	    ]);

	    $blog = new blog();
	    $blog->title = $request->title;
	    $blog->description = $request->description;

	    if ($this->user->blogs()->save($blog))
	        return response()->json([
	            'success' => true,
	            'blog' => $blog
	        ]);
	    else
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, blog could not be added.'
	        ], 500);
	}


	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, $id)
	{
	    $blog = $this->user->blogs()->find($id);

	    if (!$blog) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, blog with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    $updated = $blog->fill($request->all())->save();

	    if ($updated) {
	        return response()->json([
	            'success' => true
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, blog could not be updated.'
	        ], 500);
	    }
	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
	    $blog = $this->user->blogs()->find($id);

	    if (!$blog) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, blog with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    if ($blog->delete()) {
	        return response()->json([
	            'success' => true
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'blog could not be deleted.'
	        ], 500);
	    }
	}


}