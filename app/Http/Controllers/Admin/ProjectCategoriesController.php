<?php

namespace App\Http\Controllers\Admin;

use App\project_categories;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectCategoryStoreRequest;
use App\Http\Requests\ProjectCategoryUpdateRequest;
use App\Traits\UploadTrait;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class ProjectCategoriesController extends Controller
{
    use UploadTrait;
    function __construct()
    {
        $this->middleware('can:create Project Category', ['only' => ['create', 'store']]);
        $this->middleware('can:edit Project Category', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete Project Category', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projectCategories = project_categories::select('*')->get();
        return view('admin.projectCategory.index',compact('projectCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        return view('admin.projectCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectCategoryStoreRequest $request)
    {
        try {

            $project_categories = new project_categories();
            $project_categories->name = $request->name;
            $project_categories->status = 'Active';
            $project_categories->created_by = auth()->user()->id;
            $project_categories->updated_by = auth()->user()->id;
            $project_categories->save();

            //$project_categories->users()->attach($request->user_id);
            //Session::flash('success', 'Project Categories was created successfully.');
            //return redirect()->route('project_categories.index');

            return response()->json([
                'success' => 'Project Categories was created successfully.' // for status 200
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
     * @param  \App\project_categories  $project_categories
     * @return \Illuminate\Http\Response
     */
    public function show(project_categories $project_categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\project_categories  $project_categories
     * @return \Illuminate\Http\Response
     */
    public function edit(project_categories $projectCategory)
    {
        return view('admin.projectCategory.edit', compact('projectCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\project_categories  $project_categories
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectCategoryUpdateRequest $request, project_categories $projectCategory)
    {
        try {

            if (empty($projectCategory)) {
                //Session::flash('failed', 'Project Category Update Denied');
                //return redirect()->back();
                return response()->json([
                    'error' => 'Project Category update denied.' // for status 200
                ]);   
            }

            $projectCategory->name = $request->name;
            $projectCategory->status = $request->status;
            $projectCategory->updated_by = auth()->user()->id;
            $projectCategory->save();
            //Session::flash('success', 'A Project Category updated successfully.');
            //return redirect('admin/projectCategory');

            return response()->json([
                'success' => 'Project Category update successfully.' // for status 200
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
     * @param  \App\project_categories  $project_categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(project_categories $projectCategory)
    {
        // delete related blog   
        $projectCategory->projects()->delete();

        // delete Project Category
        $projectCategory->delete();

        //return redirect('admin/project_categories')->with('delete', 'Project Category deleted successfully.');
        return response()->json([
            'delete' => 'Project Category deleted successfully.' // for status 200
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

            $project_category = project_categories::find($request->id);
            if (empty($project_category)) {
                //Session::flash('failed', 'Project Category Update Denied');
                //return redirect()->back();
                return response()->json([
                    'error' => 'Project category update denied.' // for status 200
                ]);   
            }

            if($request->status==0){
                $status='Inactive';
            }else{
                $status='Active';
            }
            $project_category->status = $status;
            $project_category->save();

            //Session::flash('success', 'A Project Category updated successfully.');
            //return redirect('admin/project_category');

            return response()->json([
                'success' => 'Project category update successfully.' // for status 200
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
