<?php

namespace App\Http\Controllers\Api\V1\Auth\Password;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        return response()->json([
            'message' => __METHOD__,
            'input' =>$request->all()
        ]);
    }
}
