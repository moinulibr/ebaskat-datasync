<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Role::orderBy('id', 'desc')->get();
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('action', function (Role $data) {
                return '<div class="action-list">
                <a href="' . route('admin-role-edit', $data->id) . '"> <i class="fas fa-edit"></i>Edit</a>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.role.index');
    }

    //*** GET Request
    public function create()
    {
        $data['permissions'] = (new Permission)->all();
        return view('admin.role.create',$data);
    }

    //*** POST Request
    public function store(Request $r)
    {   
        //--- Validation Section
        $role_name = strtolower(trim($r->name));
        $validator = Validator::make(['name' => $role_name], [
            'name'      => ['required', 'unique:roles,name'],
        ]);
        
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $permissions = $r->except('_token','name');

        // removing first value of each array
        foreach ($permissions as $key => $item) {

            // if root permission is not selected
            if($permissions[$key][0] != "on")
            {
                unset($permissions[$key]);
                continue;
            }
            array_shift($permissions[$key]);
        }

        //--- Logic Section
        Role::create([
            'name' => $role_name,
            'permissions' => json_encode($permissions)
        ]);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.<a href="' . route('admin-role-index') . '">View Role Lists.</a>';
        return response()->json($msg);
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Role::findOrFail($id);
        $permissions = (new Permission)->all();

        // dd($data->permissionVerifyForEdit('products|add'));
        return view('admin.role.edit', compact('data','permissions'));
    }

    //*** POST Request
    public function update(Request $r, $id)
    {
        $rules = [
            'name'      => 'required', 'unique:roles,name',
        ];

        $validator = Validator::make($r->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $permissions = $r->except('_token','name');
        
        // removing first value of each array
        foreach ($permissions as $key => $item) {
            // if root permission is not selected
            if($permissions[$key][0] != "on")
            {
                unset($permissions[$key]);
                continue;
            }
            
            array_shift($permissions[$key]);
        }

        Role::whereId($id)->update([
            'name' => $r->name,
            'permissions' => json_encode($permissions)
        ]);

        $msg = 'Data Updated Successfully.<a href="' . route('admin-role-index') . '">View Role Lists.</a>';
        return response()->json($msg);
        //--- Redirect Section Ends    

    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Role::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends     
    }
}
