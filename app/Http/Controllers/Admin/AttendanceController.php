<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Branch;
use App\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceStoreRequest;
use App\Traits\UploadTrait;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use JeroenDesloovere\Distance\Distance;

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
        $users = User::all();
        return view('admin.attendance.index', compact("users"));
    }

    /**
     * Datatables Ajax Data
     *
     * @return mixed
     * @throws \Exception
     */
    public function datatables(Request $request)
    {

        if ($request->ajax() == true) {

            $model = Attendance::with('branch','creator','editor');

            // Where condition on Role and Branch, If role super admin then show all records, other than only user branch records show.
            if(!auth()->user()->hasRole('superadmin')){
                $branch_id = auth()->user()->getBranchIdsAttribute();
                $model->whereIn('branch_id',$branch_id);
            }
            
            return Datatables::eloquent($model)
                    ->addColumn('action', function (Attendance $data) {
                        $html='';
                        if (auth()->user()->can('edit attendance')){
                            $html.= '<a href="'.  route('admin.attendance.edit', ['attendance' => $data->id]) .'" class="btn btn-success btn-sm float-left mr-3"  id="popup-modal-button"><span tooltip="Edit" flow="left"><i class="fas fa-edit"></i></span></a>';
                        }

                        if (auth()->user()->can('delete attendance')){
                            $html.= '<form method="post" class="float-left delete-form" action="'.  route('admin.attendance.destroy', ['attendance' => $data->id ]) .'"><input type="hidden" name="_token" value="'. Session::token() .'"><input type="hidden" name="_method" value="delete"><button type="submit" class="btn btn-danger btn-sm"><span tooltip="Delete" flow="right"><i class="fas fa-trash"></i></span></button></form>';
                        }

                        return $html; 
                    })

                    ->addColumn('activity', function (Attendance $data) {
                        if($data->status=='punch_in'){ $status='<span class="text-success"><i class="fas fa-sign-in-alt"></i></span> In at'; }else{ $status='<span class="text-danger"><i class="fas fa-sign-out-alt"></i></span> Out at'; }
                        return $status .' '. date_format (date_create($data->time), "g:ia").' On '.date_format (date_create($data->time), "l jS F Y");
                    })

                    ->addColumn('branch', function (Attendance $data) {
                        return $data->branch->company->name.' - '.$data->branch->name;
                    })

                    ->addColumn('username', function (Attendance $data) {
                        return '<img src="'.$data->creator->getImageUrlAttribute($data->creator->id).'" alt="user_id_'.$data->creator->id.'" class="profile-user-img-small img-circle"> '. $data->creator->name;
                    })
                    
                    ->addColumn('search_username', function (Attendance $data) {
                        return 'user_id_'.$data->creator->id;
                    })
                    ->addColumn('editor', function (Attendance $data) {
                        return '<img src="'.$data->editor->getImageUrlAttribute($data->editor->id).'" alt="Admin" class="profile-user-img-small img-circle"> '. $data->editor->name;
                    })
                    
                    ->rawColumns(['activity', 'username', 'editor', 'action'])

                    ->make(true);
        }
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
            $user = User::find(auth()->user()->id);
            $branches = $user->branches;
            foreach ($branches as $key => $branch) {
                $branch_latitude = $branch->latitude;
                $branch_longitude = $branch->longitude;

                $distance = Distance::between(
                    $branch_latitude,
                    $branch_longitude,
                    $request->latitude,
                    $request->longitude
                );

                $distance = $distance*1000; // distance convert into kilometers to meters

                // dump data
                //echo 'Distance between the two locations = ' . $distance . ' m';
                if($branch->radius >= $distance){

                    $attendance = new Attendance();
                    $attendance->status = $request->status;
                    $attendance->distance = $distance;
                    $attendance->latitude = $request->latitude;
                    $attendance->longitude = $request->longitude;
                    $attendance->ip_address = $request->ip();
                    $attendance->branch_id = $branch->id;
                    $attendance->created_by = auth()->user()->id;
                    $attendance->updated_by = auth()->user()->id;
                    $attendance->save();

                    Session::flash('success', 'Your attendance has been confirmed successfully.');
                    return redirect()->back();
                }else{
                    Session::flash('failed', 'You are away from your branch.');
                    return redirect()->back()->withErrors('You are away from your branch.');
                }
            }

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
