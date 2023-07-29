<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('PostIndex');
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('PostCreate');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {
        $data=$request->validated();
        dd($data);
        if($data['image']){
            $imageName = time().'.'.$request->image->extension();  
            $request->image->storeAs('images', $imageName);
        }



        try{
            $response=DB::transaction(function()use($data){
                $post=Post::create($data);
                return response([
                    'status'=>200,
                    'post'=>$post
                ]);
            });
            

        }catch(\Exception $ex){
            return response(
                [
                    'status'=>500,
                    'error'=>$ex->getMessage()
                ]
                );
        }
        catch(\Error $er){
            return response(
                [
                    'status'=>500,
                    'error'=>$er->getMessage()
                ]
                );
        }

        
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('ShowPost')->with('Post',$post);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('EditPost')->with('Post',$post);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search_posts(Request $request){
        $posts=Post::query()
        ->when($request->search_data,function($query) use($request){
            $query->where('title', 'LIKE', '%'.$request->search_data.'%')
            ->orWhereHas('Tag',function($query1)use($request){
                $query1->where('name', 'LIKE', '%'.$request->search_data.'%');
            });
        })
        ->with('Tag')
        ->get();

        return response([
            'posts'=>$posts
        ]);

    }
}
