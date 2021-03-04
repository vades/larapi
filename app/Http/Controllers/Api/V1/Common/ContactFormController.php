<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
//use App\Mail\ContactFormMail;

class ContactFormController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->isValidRequest($request);

        $data = array('content' => $request->input('message'));
        try {
            Mail::send('emails.contact-form',  $data, function ($message) use($request) {
                $message->to(config('mail.contact_form.address'), config('mail.contact_form.name'))
                ->subject($request->input('subject'))
                ->from($request->input('email'), $request->input('name'));
            });
            return response()->json([
                'message' =>  __('app.success_email_send'),
            ],201);
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            return response()->json([
                'message' =>  __('app.error_email_send'),
                'errors' => (config('app.debug') ?  $e->getMessage() : '')
            ], 500);
        }
    }

    private function isValidRequest(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);
    }
}
