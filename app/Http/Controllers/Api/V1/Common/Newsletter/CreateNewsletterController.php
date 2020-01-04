<?php

namespace App\Http\Controllers\Api\V1\Common\Newsletter;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Http\Requests\Newsletter\CreateNewsletterRequest as Request;

class CreateNewsletterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'message' => __METHOD__,
            'input' =>$request->all()
        ]);
    }
}
