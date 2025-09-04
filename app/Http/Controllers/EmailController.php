<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'to' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        Mail::raw($data['message'], function ($mail) use ($data) {
            $mail->to($data['to'])->subject($data['subject']);
        });

        return response()->json(['message' => 'Email sent successfully']);
    }
}
