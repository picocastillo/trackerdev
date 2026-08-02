<?php

namespace App\Http\Controllers;

use App\Mail\Contact;
use App\Models\PortfolioProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function welcome()
    {
        $portfolioProjects = PortfolioProject::active()
            ->ordered()
            ->get()
            ->map(fn (PortfolioProject $project) => $project->toPublicArray())
            ->values();

        return view('welcome', [
            'portfolioProjects' => $portfolioProjects,
        ]);
    }

    public function contactForm(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required',
            'message' => 'required',
        ]);
        Mail::to('castillo.cesar.pico@gmail.com')
            ->send(new Contact($request->message, $request->name, $request->email));

        return redirect('/');
    }
}
