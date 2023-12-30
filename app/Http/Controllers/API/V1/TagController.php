<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::query()->orderBy('created_at', 'desc')->paginate(10);
        return $this->successResponce($tags, '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        =>  ['required', 'string', 'min:3', 'max:100'],
            'is_active'   =>  ['required', 'boolean'],
        ]);

        if($validator->fails())
        {
            return $this->errorResponce(422, $validator->messages());
        }


        $tag = Tag::create([
            'name'       =>  $request->input('name'),
            'is_active'  =>  $request->input('is_active'),
        ]);


        return $this->successResponce($tag, __('The new Tag was successfully created.'),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return $this->successResponce($tag, '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {

        $validator = Validator::make($request->all(), [
            'name'        =>  ['required', 'string', 'min:3', 'max:100'],
            'is_active'   =>  ['required', 'boolean'],
        ]);

        if($validator->fails())
        {
            return $this->errorResponce(422, $validator->messages());
        }


        $tag->update([
            'name'       =>  $request->input('name'),
            'is_active'  =>  $request->input('is_active'),
        ]);


        return $this->successResponce($tag, __('The desired Tag was edited correctly.'),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return $this->successResponce($tag, __('The desired Tag was deleted correctly.'),200);
    }
}
