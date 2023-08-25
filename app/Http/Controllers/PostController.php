<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;


class PostController extends Controller
{

	public function index()
	{
		$rows = Post::orderby("updated_at", "Desc")->get();
		return view('post/postlist', ['rows' => $rows]);
	}

	public function create()
	{
		$rows = Category::orderby('category')->get();
		return view('post/postform', ['action' => 'insert', 'categories' => $rows]);
	}

	public function store(Request $request)
	{

		$this->validate($request, [
			'image' => 'required|mimes:jpg,png,jpeg',
		]);

		$image_ext = $request->image->extension();
		$image_name = time() . '.' . $image_ext;
		$request->image->move(public_path('images'), $image_name);

		$image = 'data:@image/' . $image_ext . ';base64,' . base64_encode(file_get_contents(public_path('images/') . $image_name));

		$ext = explode(';base64', $image);
		$ext = explode('/', $ext[0]);
		$ext = $ext[1];

		$image = str_replace('data:@image/' . $image_ext . ';base64,', '', $image);
		$image = str_replace(' ', '+', $image);
		File::put(public_path('images') . '/' . $image_name, base64_decode($image));

		$post = new Post;
		$post->user_id = Auth::user()->id;
		$post->cat_id = $request->cat_id;
		$post->title = $request->title;
		$post->content = $request->content;
		$post->image = asset('/images') . '/' . $image_name;
		$post->save();
		return redirect('/post');
	}

	public function show($id)
	{
		$post = Post::find($id);
		return view('post/postform', ['row' => $post, 'action' => 'detail']);
	}

	public function edit($id)
	{
		$post = Post::find($id);
		$rows = Category::orderby('category')->get();

		return view('post/postform', ['row' => $post, 'action' => 'update', 'categories' => $rows]);
	}

	public function update(Request $request)
	{
		$post = Post::find($request->id);
		// $post->id = $request->id;
		$post->user_id = Auth::user()->id;
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
		// $post->created_at = $request->created_at;
		// $post->updated_at = $request->updated_at;
		$post->save();
		return redirect('/post');
	}

	public function delete($id)
	{
		$post = Post::find($id);
		return view('post/postform', ['row' => $post, 'action' => 'delete']);
	}

	public function destroy($id)
	{
		$post = Post::find($id);
		$images = explode('/', $post->image, 5);
		$image_path = public_path('images/' . $images[4]);
		if (file_exists($image_path)) {
			unlink($image_path);
		}
		$post->delete();
		return redirect('/post');
	}
}
