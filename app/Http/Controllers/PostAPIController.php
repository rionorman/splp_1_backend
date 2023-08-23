<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostAPIController extends Controller
{

	public function indexPostAPI()
	{
		$rows = Post::orderby("updated_at", "Desc")->get();
		return PostResource::collection($rows);
	}

	public function storePostAPI(Request $request)
	{
		$imageName = time() . '.' . $request->image->extension();
		$request->image->move(public_path('images'), $imageName);

		$post = new Post;
		$post->user_id = 3;
		$post->cat_id = $request->cat_id;
		$post->title = $request->title;
		$post->content = $request->content;
		$post->image = asset('/images') . '/' . $imageName;
		$post->save();
		return response()->json([
			'success' => true,
			'data' => $post
		]);
	}

	public function showPostAPI($id)
	{
		$post = Post::find($id);
		return new PostResource($post);
	}

	public function updatePostAPI(Request $request)
	{
		$post = Post::find($request->id);
		$post->user_id = 3;
		$post->cat_id = $request->cat_id;
		$post->title = $request->title;
		$post->content = $request->content;
		if ($request->image != NULL) {
			$images = explode('/', $post->image, 5);
			$image_path = public_path('images' . '/' . $images[4]);
			if (file_exists($image_path)) {
				unlink($image_path);
			}
			$imageName = time() . '.' . $request->image->extension();
			$request->image->move(public_path('images'), $imageName);
			$post->image = asset('/images')  . '/' . $imageName;
		}
		$post->save();
		return response()->json([
			'success' => true,
			'data' => $post
		]);
	}

	public function searchPostAPI($search)
	{
		$posts = Post::select('id', 'title', 'content', 'image', 'updated_at')
			->where('content', 'like', '%' . $search . '%')->get();
		foreach ($posts as $post) {
			$post->content = substr($post->content, 0, 150);
			$post->url =  asset('detail') . '/' . $post->id;
			$post->sumber = 'Dinas Jawa Barat';
		}
		return response()->json([
			'success' => 1,
			'data' => $posts
		]);
	}

	public function destroyPostAPI($id)
	{
		$post = Post::find($id);
		$images = explode('/', $post->image, 5);
		$image_path = public_path('images/' . $images[4]);
		if (file_exists($image_path)) {
			unlink($image_path);
		}
		$post->delete();
		return response()->json([
			'success' => 1
		]);
	}
}
