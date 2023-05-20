<?php

namespace App\Http\Controllers;

use App\Models\Calon;
use App\Models\Comp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class CalonController extends Controller
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
            $data = Calon::get();
            if ($request->name) {
                $data = Calon::where('name', 'like', "%{$request->name}%")->get();
            }
            return DataTables::of($data)->toJson();
        }
        return view('calon.data')->with(['comp' => $this->comp, 'title' => 'Data Calon']);
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
            'name'      => 'required|max:25|min:3',
            'gender'    => 'required|in:male,female',
            'img'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'partai'    => 'required|max:30',
            'address'   => 'max:150',
        ]);
        $img = null;
        if ($files = $request->file('img')) {
            $destinationPath = 'images/calon/';
            $img = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $img);
        }
        $calon = Calon::create([
            'name'      => $request->name,
            'gender'    => $request->gender,
            'image'     => $img,
            'partai'    => $request->partai,
            'address'   => $request->address,
        ]);
        if ($calon) {
            return response()->json(['status' => true, 'message' => 'Success Insert Data', 'data' => '']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed Insert Data', 'data' => '']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calon  $calon
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Calon $calon)
    {
        if ($request->ajax()) {
            $calon = Calon::find($calon->id);
            return response()->json(['status' => true, 'message' => '', 'data' => $calon]);
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calon  $calon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calon $calon)
    {
        $this->validate($request, [
            'name'      => 'required|max:25|min:3',
            'gender'    => 'required|in:male,female',
            'img'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'partai'    => 'required|max:30',
            'address'   => 'max:150',
        ]);
        $img = $calon->image;
        if ($files = $request->file('img')) {
            //delete old file
            File::delete('images/calon/' . $img);
            //insert new file
            $destinationPath = 'images/calon/'; // upload path
            $img = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $img);
        }
        $calon->update([
            'name'      => $request->name,
            'gender'    => $request->gender,
            'image'     => $img,
            'partai'    => $request->partai,
            'address'   => $request->address,
        ]);
        if ($calon) {
            return response()->json(['status' => true, 'message' => 'Success Update Data', 'data' => '']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed Update Data', 'data' => '']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calon  $calon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $this->validate($request, ['id' => 'required|array']);
            if ($request->id) {
                $count = count($request->id);
                $counter = 0;
                foreach ($request->id as $id) {
                    $calon = Calon::findOrFail($id);
                    if ($calon->img != null) {
                        File::delete('images/calon/' . $calon->img);
                    }
                    $calon->delete();
                    if ($calon) {
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
