<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Branch;
use App\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchStoreRequest;
use App\Traits\UploadTrait;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    use UploadTrait;

    function __construct()
    {
        $this->middleware('can:create attendance', ['only' => ['create', 'store']]);
        $this->middleware('can:edit attendance', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete attendance', ['only' => ['destroy']]);
    }

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceStoreRequest $request)
    {
        try {

            $branch = new Branch();
            $branch->status = $request->status;
            $branch->distance = $request->distance;
            $branch->latitude = $request->latitude;
            $branch->longitude = $request->longitude;
            $branch->ip_address = $request->ip_address;
            $branch->branch_id = $request->branch_id;
            $branch->created_by = auth()->user()->id;
            $branch->updated_by = auth()->user()->id;
            $branch->save();

            $branch->users()->attach($request->user_id);

            //Session::flash('success', 'branch was created successfully.');
            //return redirect()->route('branch.index');

            return response()->json([
                'success' => 'branch was created successfully.' // for status 200
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            //Session::flash('failed', $exception->getMessage() . ' ' . $exception->getLine());
            /*return redirect()->back()->withInput($request->all());*/

            return response()->json([
                'error' => $exception->getMessage() . ' ' . $exception->getLine() // for status 200
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function show(attendances $attendances)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function edit(attendances $attendances)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, attendances $attendances)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\attendances  $attendances
     * @return \Illuminate\Http\Response
     */
    public function destroy(attendances $attendances)
    {
        //
    }
}
