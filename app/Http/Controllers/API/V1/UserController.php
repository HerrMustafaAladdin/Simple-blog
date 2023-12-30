<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // user model
        $users = User::query()->orderBy('created_at')->paginate(10);

        return $this->successResponce($users, '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                      =>  ['required', 'string', 'min:3', 'max:100'],
            'email'                     =>  ['required', 'email'],
            'password'                  =>  ['required', 'string', 'min:6', 'max:32'],
            'password_confirmation'     =>  ['required','same:password']
        ]);

        if($validator->fails())
        {
            return $this->errorResponce(422, $validator->messages());
        }


        $user = User::create([
            'name'      =>  $request->input('name'),
            'email'     =>  $request->input('email'),
            'password'  =>  Hash::make($request->password),
        ]);


        return $this->successResponce($user, __('The new user was successfully created.'),200);


    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
