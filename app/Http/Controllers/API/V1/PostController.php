<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\API\V1\PostResponce;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->orderBy('id','desc')->paginate(1);
        return $this->successResponce([
            "Data"  =>  PostResponce::collection($posts->load('images')->load('category')->load('tags')->load('user')),
            "Links" =>  PostResponce::collection($posts)->response()->getData()->links,
            "Meta"  =>  PostResponce::collection($posts)->response()->getData()->meta
        ], '', 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'user_id'       =>  ['required', 'integer'],
            'category_id'   =>  ['required', 'integer'],
            'title'         =>  ['required', 'string', 'min:3', 'max:100'],
            'primary_image' =>  ['required', 'mimes:jpg,jpeg,png,svg,webp'],
            'content'       =>  ['required', 'string', 'min:100', 'max:2000'],
            'is_active'     =>  ['required', 'boolean'],

            'tag_ids.*'     =>  ['required', 'integer'],

            'images'        =>  ['required', 'array'],
            'images.*'      =>  ['mimes:jpg,jpeg,png,svg,webp']

        ]);

        if($validator->fails())
        {
            return $this->errorResponce(422, $validator->messages());
        }


        try {
            DB::beginTransaction();

            $primary_image_name = generateFileName($request->primary_image);
            uploadFileImage($request->primary_image,'posts',$primary_image_name);

            $post = Post::create([
                'user_id'       =>  $request->input('user_id'),
                'category_id'   =>  $request->input('category_id'),
                'title'         =>  $request->input('title'),
                'primary_image' =>  $primary_image_name,
                'content'       =>  $request->input('content'),
                'is_active'     =>  $request->input('is_active'),
            ]);

            foreach ($request->tag_ids as $value) {
                PostTag::create([
                    'post_id' =>    $post->id,
                    'tag_id'  =>    $value
                ]);
            }

            foreach($request->images as $image)
            {
                $imageName = generateFileName($image);
                uploadFileImage($image,'posts',$imageName);

                PostImage::create([
                    'post_id'   =>  $post->id,
                    'name'      =>  $imageName
                ]);

            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->errorResponce('500', $ex->getMessage());
        }

        return $this->successResponce([
            new PostResponce($post)
        ], __('The new post was successfully created.'),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $this->successResponce([
            "Data"  =>  new PostResponce($post->load('images')->load('category')->load('tags')->load('user')),
        ], '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        $validator = Validator::make($request->all(), [

            'user_id'       =>  ['required', 'integer'],
            'category_id'   =>  ['required', 'integer'],
            'title'         =>  ['required', 'string', 'min:3', 'max:100'],
            'primary_image' =>  ['required', 'mimes:jpg,jpeg,png,svg,webp'],
            'content'       =>  ['required', 'string', 'min:100', 'max:2000'],
            'is_active'     =>  ['required', 'boolean'],

            'tag_ids.*'     =>  ['required', 'integer'],

            'images'        =>  ['required', 'array'],
            'images.*'      =>  ['mimes:jpg,jpeg,png,svg,webp']

        ]);

        if($validator->fails())
        {
            return $this->errorResponce(422, $validator->messages());
        }


        try {
            DB::beginTransaction();

            if($request->has('primary_image'))
            {
                $primary_image_name = generateFileName($request->primary_image);
                uploadFileImage($request->primary_image,'posts',$primary_image_name);
            }

            $post->update([
                'user_id'       =>  $request->input('user_id'),
                'category_id'   =>  $request->input('category_id'),
                'title'         =>  $request->input('title'),
                'primary_image' =>  $request->has('primary_image') ? $primary_image_name : $post->primary_image,
                'content'       =>  $request->input('content'),
                'is_active'     =>  $request->input('is_active'),
            ]);

            foreach($post->postTags as $tag)
            {
                $tag->delete();
            }

            foreach ($request->tag_ids as $value) {
                PostTag::create([
                    'post_id' =>    $post->id,
                    'tag_id'  =>    $value
                ]);
            }
            foreach($post->images as $image)
            {
                $image->delete();
            }
            foreach($request->images as $image)
            {
                $imageName = generateFileName($image);
                uploadFileImage($image,'posts',$imageName);

                PostImage::create([
                    'post_id'   =>  $post->id,
                    'name'      =>  $imageName
                ]);

            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->errorResponce('500', $ex->getMessage());
        }

        return $this->successResponce([
            new PostResponce($post)
        ], __('The new post was successfully updated.'),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            DB::beginTransaction();
            foreach($post->images as $image)
            {
                $image->delete();
            }

            foreach($post->postTag as $tag)
            {
                $tag->delete();
            }

            $post->delete();

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->errorResponce('500', $ex->getMessage());
        }

        return $this->successResponce(new PostResponce($post->load('images')->load('tags')->load('user')->load('category')),'',200);
    }
}
