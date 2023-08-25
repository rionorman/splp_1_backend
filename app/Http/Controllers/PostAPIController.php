<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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
		$ext = explode(';base64', $request->image);
		$ext = explode('/', $ext[0]);
		$ext = $ext[1];

		$image = str_replace('data:@image/' . $ext . ';base64,', '',  $request->image);
		$image = str_replace(' ', '+', $image);
		$image_name = time() . '.' . $ext;

		File::put(public_path('images') . '/' . $image_name, base64_decode($image));

		$post = new Post;
		$post->user_id = 3;
		$post->cat_id = $request->cat_id;
		$post->title = $request->title;
		$post->content = $request->content;
		$post->image =  asset('/images/' . $image_name);
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
		// get value request 
		$post = Post::find($request->id);
		$post->user_id = 3;
		$post->cat_id = $request->cat_id;
		$post->title = $request->title;
		$post->content = $request->content;

		// check file new file image
		if ($request->image != NULL) {

			// delete old file
			$images = explode('/', $post->image, 5);
			$image_path = public_path('images' . '/' . $images[4]);
			if (file_exists($image_path)) {
				unlink($image_path);
			}

			// add new file
			$ext = explode(';base64', $request->image);
			$ext = explode('/', $ext[0]);
			$ext = $ext[1];
			$image = str_replace('data:@image/' . $ext . ';base64,', '',  $request->image);
			$image = str_replace(' ', '+', $image);
			$image_name = time() . '.' . $ext;
			File::put(public_path('images') . '/' . $image_name, base64_decode($image));

			// set image field
			$post->image =  asset('/images/' . $image_name);
		}

		$post->save();

		return response()->json([
			'success' => true,
			'data' => $post
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
