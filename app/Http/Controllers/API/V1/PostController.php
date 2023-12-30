<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\ApiController;
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
        //
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

            // 'images'        =>  ['required', 'array'],
            // 'images.*'      =>  ['mimes:jpg,jpeg,png,svg,webp']

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
            'post' =>   $post
        ], __('The new post was successfully created.'),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
