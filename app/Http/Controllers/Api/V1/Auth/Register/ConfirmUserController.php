<?php

namespace App\Http\Controllers\Api\V1\Auth\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auth\ConfirmUser;

class ConfirmUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ConfirmUser $request)
    {
        return response()->json([
            'message' => __METHOD__,
            'request' => $request->route('token'),
            'uuid' =>  \Uuid::v5('ghgh'),
        ]);
    }
}
