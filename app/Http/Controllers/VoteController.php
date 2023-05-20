<?php

namespace App\Http\Controllers;

use App\Models\Comp;
use App\Models\Event;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $comp;

    public function __construct()
    {
        $this->middleware('auth');
        $this->comp = Comp::first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Vote::query();
            // if ($request->name) {
            //     $data->where('name', 'like', "%{$request->name}%");
            // }
            $result = $data->with('event', 'calon')->get();
            return DataTables::of($result)->toJson();
        }
        return view('vote.data')->with(['comp' => $this->comp, 'title' => 'Data Vote']);
    }

    public function create()
    {
        return view('vote.create')->with(['comp' => $this->comp, 'title' => 'New Votes']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'event'     => 'required|integer|exists:event,id',
            'status'    => 'required|in:valid,invalid',
            'calon'     => 'nullable|required_if:status,valid|integer|exists:dtevent,calon_id,event_id,' . $request->input('event'),
        ]);
        $vote = Vote::create([
            'event_id' => $request->event,
            'calon_id' => $request->calon,
            'status'   => $request->status,
        ]);
        if ($vote) {
            return response()->json(['status' => true, 'message' => 'Success Insert Data', 'data' => '']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed Insert Data', 'data' => '']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->id) {
                $count = count($request->id);
                $counter = 0;
                foreach ($request->id as $id) {
                    $table = Vote::findOrFail($id);
                    $table->delete();
                    if ($table) {
                        $counter = $counter + 1;
                    }
                }
                return response()->json(['status' => true, 'message' => 'Success Delete ' . $count . '/' . $counter . ' Data', 'data' => '']);
            } else {
                return response()->json(['status' => false, 'message' => 'No Selected Data', 'data' => '']);
            }
        } else {
            abort(404);
        }
    }

    public function statistic(Request $request)
    {
        $this->validate($request, [
            'event' => 'required|exists:event,id',
        ]);
        // $data['status'] = Vote::select('status', Vote::raw('COUNT(*) as total'))
        //     ->groupBy('status')
        //     ->where('event_id', $request->event)
        //     ->get();
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
