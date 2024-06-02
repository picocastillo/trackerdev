<?php

namespace App\Http\Controllers;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    function contactForm(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required',
            'message' => 'required',
        ]);
        Mail::to('castillo.cesar.pico@gmail.com')
                ->send(new Contact($request->message ,$request->name,$request->email));
        return redirect('/');
    }

}
