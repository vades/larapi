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
        

        try{
            $subscribtion = new Subscription;
            $subscribtion->email = $request->email;
            $subscribtion->type ='voiciqsoon';
            $subscribtion->ip_address = $request->ip();
            $subscribtion->save();
            return response()->json([
                'status' => 201,
                'message' => trans('httpstatus.201')
            ],201);
         }
         catch(\Exception $e){
            // do task when error
          abort(500, trans('errors.sql'));
         }

       
    }
}
