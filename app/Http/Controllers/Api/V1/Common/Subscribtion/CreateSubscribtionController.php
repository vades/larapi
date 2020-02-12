<?php

namespace App\Http\Controllers\Api\V1\Common\Subscribtion;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;

use App\Http\Requests\Subscription\CreateSubscriptionRequest as Request;
use App\Models\Subscription;

class CreateSubscribtionController extends Controller
{
    /**
     * Handle the incoming request.
     * TODO: Check for unique email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'status' => 422,
            'message' => trans('httpstatus.422')
        ],422);
        
        return response()->json([
            'message' => __METHOD__,
            'input' =>$request->all()
        ]);
        
        return response()->json([
            'status' => 201,
            'message' => trans('httpstatus.201')
        ],201);

       
    }
}
