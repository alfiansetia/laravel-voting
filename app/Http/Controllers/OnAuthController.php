<?php

namespace App\Http\Controllers;

use App\Models\Comp;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OnAuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $comp;

    public function __construct()
    {
        $this->comp = Comp::first();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $event = Event::latest()->get();
        return view('event.list', compact('event'))->with(['comp' => $this->comp, 'title' => 'List Event']);
    }
}
