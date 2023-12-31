<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\ApiController;
use App\Http\Resources\CommentResponce;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends ApiController
{

    public function index()
    {

    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   =>  ['required', 'integer'],
            'post_id'   =>  ['required', 'integer'],
            'is_active' =>  ['nullable', 'integer'],
            'panret_id' =>  ['nullable', 'integer'],
            'content'   =>  ['required', 'string', 'min:3', 'max:1000']
        ]);

        if($validator->fails())
        {
            return $this->errorResponce('422', $validator->messages());
        }

        $comment = Comment::create([
            'user_id'       =>  $request->input('user_id'),
            'post_id'       =>  $request->input('post_id'),
            'parent_id'     =>  $request->input('parent_id'),
            'is_active'     =>  $request->input('is_active'),
            'content'       =>  $request->input('content'),
        ]);


        return $this->successResponce(new CommentResponce($comment->load('post')->load('parent')->load('children')), '', 200);


    }
}
