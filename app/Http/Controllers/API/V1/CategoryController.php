<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->orderBy('created_at', 'desc')->paginate(10);
        return $this->successResponce($categories, '', 200);
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


        $category = Category::create([
            'name'       =>  $request->input('name'),
            'is_active'  =>  $request->input('is_active'),
        ]);


        return $this->successResponce($category, __('The new category was successfully created.'),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->successResponce($category, '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        
        $validator = Validator::make($request->all(), [
            'name'        =>  ['required', 'string', 'min:3', 'max:100'],
            'is_active'   =>  ['required', 'boolean'],
        ]);

        if($validator->fails())
        {
            return $this->errorResponce(422, $validator->messages());
        }


        $category->update([
            'name'       =>  $request->input('name'),
            'is_active'  =>  $request->input('is_active'),
        ]);


        return $this->successResponce($category, __('The desired category was edited correctly.'),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successResponce($category, __('The desired category was deleted correctly.'),200);
    }
}
