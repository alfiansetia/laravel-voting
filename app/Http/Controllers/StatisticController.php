<?php

namespace App\Http\Controllers;

use App\Models\Comp;
use App\Models\Dtevent;
use App\Models\Event;
use App\Models\Vote;
use Illuminate\Http\Request;

class StatisticController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'event' => 'required|integer|exists:event,id'
        ]);
        $event = Event::find($request->event);
        $calon = Dtevent::with('calon')->where('event_id', $request->event)->get();
        return view('statistic.data', compact(['event', 'calon']))->with(['comp' => $this->comp, 'title' => 'Statistic']);
    }

    public function event(Request $request)
    {
        $this->validate($request, [
            'event' => 'required|integer|exists:event,id',
        ]);
        $data['status']['valid'] = Vote::where('event_id', $request->event)->where('status', 'valid')->count();
        $data['status']['invalid'] = Vote::where('event_id', $request->event)->where('status', 'invalid')->count();
        $data['event'] = Event::find($request->event);
        $data['detail'] = Vote::select('calon_id', Vote::raw('COUNT(*) as total'))
            ->groupBy('calon_id')
            ->with('calon')
            ->where('event_id', $request->event)
            ->where('calon_id', '!=', null)
            ->get();
        return response()->json(['status' => true, 'message' => '', 'data' => $data]);
    }
}
