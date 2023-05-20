<?php

namespace App\Http\Controllers;

use App\Models\Comp;
use App\Models\Dtevent;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{

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
        if ($request->ajax()) {
            $data = Event::query();
            if ($request->name) {
                $data->where('name', 'like', "%{$request->name}%");
            }
            $result = $data->with('dtevent')->get();
            return DataTables::of($result)->toJson();
        }
        return view('event.data')->with(['comp' => $this->comp, 'title' => 'Data Event']);
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
            'name'      => 'required|max:50,min:3',
            'date'      => 'required|date_format:Y-m-d',
            'expired'   => 'required|date_format:Y-m-d|after_or_equal:date',
            'desc'      => 'max:150',
            'calon'     => 'array',
        ]);
        DB::beginTransaction();
        try {
            $event = Event::create([
                'name'      => $request->name,
                'date'      => $request->date,
                'expired'   => $request->expired,
                'desc'      => $request->desc,
            ]);
            $calon = $request->calon;
            if (count($calon) > 0) {
                foreach ($calon as $c) {
                    Dtevent::create([
                        'event_id' => $event->id,
                        'calon_id' => $c['id'],
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status'    => true,
                'message'   => 'Success Insert Data',
                'data'      => [],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => 'Failed Insert Data',
                'data'      => [],
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Event $event)
    {
        if ($request->ajax()) {
            $event = Event::with('dtevent.calon')->find($event->id);
            return response()->json(['status' => true, 'message' => '', 'data' => $event]);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $this->validate($request, [
            'name'      => 'required|max:50,min:3',
            'date'      => 'required|date_format:Y-m-d',
            'expired'   => 'required|date_format:Y-m-d|after_or_equal:date',
            'desc'      => 'max:150',
        ]);

        $event = Event::findOrFail($event->id);
        $event->update([
            'name'      => $request->name,
            'date'      => $request->date,
            'expired'   => $request->expired,
            'desc'      => $request->desc,
        ]);
        if ($event) {
            return response()->json(['status' => true, 'message' => 'Success Update Data', 'data' => '']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed Update Data', 'data' => '']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            if ($request->id) {
                $count = count($request->id);
                $counter = 0;
                foreach ($request->id as $id) {
                    $table = Event::findOrFail($id);
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
}
