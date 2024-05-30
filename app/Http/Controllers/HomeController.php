<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    function contactForm(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required',
            'message' => 'required',
        ]);
        Mail::to('castillo.cesar.pico@gmail.com')
                ->send(new Contact("asd","asd","asd"));
        return redirect('/');
    }

}
