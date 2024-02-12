<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("Asia/Jakarta");
        $this->middleware('auth');
        $this->middleware('role:2');
    }

    public function index()
    {
        $data['title'] = 'Users';
        return view('content.users.index', $data);
    }

    public function datatables(Request $request){
        try{
            if ($request->ajax()) :
                $users = User::get();
                
                return DataTables::of($users)
                    ->addIndexColumn() //memberikan penomoran
                    ->addColumn('role_name', function ($row) {
                        if($row->role == '0'){
                            $role_name = 'User';
                        }else if($row->role == '1'){
                            $role_name = 'Admin';
                        }else{
                            $role_name = 'Super Admin';
                        }

                        return $role_name;
                    })
                    ->addColumn('action', function ($row) {
                        
                        $btn = ' <a href="'.route('users.edit', $row->id).'" class="btn btn-sm btn-outline-info" title="edit"><i class="fa fa-edit"></i></a>';

                        $btn .= ' <a href="javascript:remove(\'' .  $row->id . '\')" class="btn btn-sm btn-outline-danger" title="edit"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['role_name', 'action'])   //merender content column dalam bentuk html
                    ->escapeColumns()  //mencegah XSS Attack
                    ->toJson(); //merubah response dalam bentuk Json
            endif;
        }
        catch (Throwable $e) {
            report($e);

            return false;
        }
    }
    
    public function create(){
        $data['title'] = 'Add users';
        return view('content.users.create', $data);
    }

    public function store(Request $request){
        $users = new User;
        $users->name       = $request->user_name;
        $users->email     = $request->email;
        $users->password = Hash::make($request->password);
        $users->role     = $request->role;

        $users->save();

        return redirect()->route('users')->with('success', 'New users Has Been Saved!');
    }

    public function edit(string $id)
    {
        $users = User::findOrFail($id);
        $title = 'Edit User';
        return view('content.users.edit', compact('users', 'title'));
    }

    public function update(Request $request, $id){
        if($request->password != ''){
            $data = [
                'name'     => $request->input('user_name', $request->user_name),
                'email'    => $request->input('email', $request->email),
                'role'     => $request->input('role', $request->role),
                'password' => Hash::make($request->input('password', $request->password))
            ];
        }else{
            $data = [
                'name'     => $request->input('user_name', $request->user_name),
                'email'    => $request->input('email', $request->email),
                'role'     => $request->input('role', $request->role)
            ];
        }

        $users = User::findOrFail($id);

        $users->update($data);

        return redirect()->route('users')
            ->with('success', 'users updated successfully');

    }

    public function delete($id)
    {
        $users = User::findOrFail($id);
        $users->delete();

        return response()->json([
            'success' => true,
            'message' => 'users Has Been Removed!',
        ]); 
    }
}
