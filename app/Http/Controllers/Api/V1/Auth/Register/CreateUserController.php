<?php

namespace App\Http\Controllers\Api\V1\Auth\Register;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\CreateUserRequest;

class CreateUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreateUserRequest $request)
    {
        return response()->json([
            'message' => __METHOD__,
            'input' =>$request->all()
        ]);
    }
}
