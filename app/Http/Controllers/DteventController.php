<?php

namespace App\Http\Controllers;

use App\Models\Dtevent;
use Illuminate\Http\Request;

class DteventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'event' => 'required|integer|exists:event,id',
            'calon' => 'required|integer|exists:calon,id|unique:dtevent,calon_id,NULL,id,event_id,' . $request->input('event'),
        ]);
        $data = Dtevent::create([
            'event_id' => $request->event,
            'calon_id' => $request->calon,
        ]);
        if ($data) {
            return response()->json(['status' => true, 'message' => 'Success Insert Data', 'data' => '']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed Insert Data', 'data' => '']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Dtevent::find($id);
            return response()->json(['status' => true, 'message' => '', 'data' => $data]);
        } else {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Request $request)
    // {
    //     $this->validate($request, [
    //         'event' => 'required|integer|exists:dtevent,event_id',
    //         'calon' => 'required|integer|exists:dtevent,calon_id',
    //     ]);
    //     $data = Dtevent::where('event_id', $request->event)->where('calon_id', $request->calon)->first();
    //     $data->delete();
    //     if ($data) {
    //         return response()->json(['status' => true, 'message' => 'Success Delete Data', 'data' => '']);
    //     } else {
    //         return response()->json(['status' => false, 'message' => 'Failed Delete Data', 'data' => '']);
    //     }
    // }

    public function destroy(Dtevent $dtevent)
    {
        $dtevent = Dtevent::findOrFail($dtevent->id);
        $dtevent->delete();
        if ($dtevent) {
            return response()->json(['status' => true, 'message' => 'Success Delete Data', 'data' => '']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed Delete Data', 'data' => '']);
        }
    }
}
