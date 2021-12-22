<?php

namespace App\Http\Controllers\Admin;

use App\projects;
use App\project_categories;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Traits\UploadTrait;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\Dat

class ProjectsController extends Controller
{
    use UploadTrait;
    function __construct()
    {
        $this->middleware('can:create Project', ['only' => ['create', 'store']]);
        $this->middleware('can:edit Project', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete Project', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = projects::select('*')->get();
        return view('admin.project.index',compact('projects'));
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

            $data = wikiBlogs::with('category','parent');

            return Datatables::eloquent($data)
                ->addColumn('action', function ($data) {
                    
                    $html='';
                    if (auth()->user()->can('edit Project')){
                        $html.= '<a href="'.  route('admin.project.edit', ['projects' => $data->id]) .'" class="btn btn-success btn-sm float-left mr-3"  id="popup-modal-button"><span tooltip="Edit" flow="left"><i class="fas fa-edit"></i></span></a>';
                    }

                    if (auth()->user()->can('delete Project')){
                        $html.= '<form method="post" class="float-left delete-form" action="'.  route('admin.project.destroy', ['projects' => $data->id ]) .'"><input type="hidden" name="_token" value="'. Session::token() .'"><input type="hidden" name="_method" value="delete"><button type="submit" class="btn btn-danger btn-sm"><span tooltip="Delete" flow="up"><i class="fas fa-trash"></i></span></button></form>';
                    }

                    return $html; 
                })

                ->addColumn('status', function ($data) {
                        if($data->status=='Active'){ $class= 'text-success';$status= 'Active';}else{$class ='text-danger';$status= 'Inactive';}
                        return '<div class="dropdown action-label">
                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-dot-circle-o '.$class.'"></i> '.$status.' </a>
                                <div class="dropdown-menu dropdown-menu-right" style="">
                                    <a class="dropdown-item" href="#" onclick="funChangeStatus('.$data->id.',1); return false;"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                    <a class="dropdown-item" href="#" onclick="funChangeStatus('.$data->id.',0); return false;"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                </div>
                            </div>';
                    })

                ->addColumn('category', function ($data) {
                        return $data->category->name;
                    })

                ->rawColumns(['action','status','category'])
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
        $status = ['Active', 'Inactive'];
        $project_categories = project_categories::all();
        return view('admin.project.create', compact("project_categories","status"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectStoreRequest $request)
    {
        try {

            $projects = new projects();
            $projects->title = $request->title;
            $projects->description = $request->description;
            $projects->category_id = $request->category_id;
            $projects->start_date = $request->start_date;
            $projects->end_date = $request->end_date;
            $projects->status = 'Active';
            $projects->created_by = auth()->user()->id;
            $projects->updated_by = auth()->user()->id;
            $projects->save();

            //Session::flash('success', 'Project was created successfully.');
            //return redirect()->route('project.index');

            return response()->json([
                'success' => 'Project was created successfully.' // for status 200
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
     * @param  \App\wikiBlogs  $wikiBlogs
     * @return \Illuminate\Http\Response
     */
    public function show(wikiBlogs $wikiBlog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\wikiBlogs  $wikiBlogs
     * @return \Illuminate\Http\Response
     */
    public function edit(projects $project)
    {
        $status = ['Active', 'Inactive'];
        $project_categories = project_categories::all();
        return view('admin.project.edit', compact('project', 'project_categories', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\wikiBlogs  $wikiBlogs
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectUpdateRequest $request, projects $project)
    {
        try {

            if (empty($project)) {
                //Session::flash('failed', 'Wiki Blog Update Denied');
                //return redirect()->back();
                return response()->json([
                    'error' => 'Project update denied.' // for status 200
                ]);   
            }

            $project->title = $request->title;
            $project->description = $request->description;
            $project->category_id = $request->category_id;
            $projects->start_date = $request->start_date;
            $projects->end_date = $request->end_date;
            $project->status = $request->status;
            $project->updated_by = auth()->user()->id;
            $project->save();

            //Session::flash('success', 'A Wiki Blog updated successfully.');
            //return redirect('admin/wikiBlog');

            return response()->json([
                'success' => 'Project update successfully.' // for status 200
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
     * Remove the specified resource from storage.
     *
     * @param  \App\projects  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(projects $project)
    {
        // delete wiki blog
        $project->delete();

        //return redirect('admin/project')->with('delete', 'project deleted successfully.');
        return response()->json([
            'delete' => 'Project deleted successfully.' // for status 200
        ]);
    }


    /**
     * Datatables Ajax Data
     *
     * @return mixed
     * @throws \Exception
     */
    public function change_status(Request $request)
    {
        try {

            $project = projects::find($request->id);
            if (empty($project)) {
                //Session::flash('failed', 'Wiki Blogs Update Denied');
                //return redirect()->back();
                return response()->json([
                    'error' => 'Project update denied.' // for status 200
                ]);   
            }

            if($request->status==0){
                $status='Inactive';
            }else{
                $status='Active';
            }

            $project->status = $status;
            $project->save();

            //Session::flash('success', 'A Wiki Blogs updated successfully.');
            //return redirect('admin/print_buttons');

            return response()->json([
                'success' => 'Project update successfully.' // for status 200
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
}
