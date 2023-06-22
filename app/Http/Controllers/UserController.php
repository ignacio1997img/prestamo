<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Models\Role;
use App\Models\Route;

class UserController extends Controller
{
    public function index(){

        $user = User::where('role_id', '!=', 1)->get();
        return view('user.browse', compact('user'));
    }

    public function list($search = null){
        $user = Auth::user();

        $paginate = request('paginate') ?? 10;
        // $data = Route::where(function($query) use ($search){
        //             $query->OrWhereRaw($search ? "id = '$search'" : 1)
        //             ->OrWhereRaw($search ? "name like '%$search%'" : 1)
        //             ->OrWhereRaw($search ? "description like '%$search%'" : 1);
        //             })
        //             ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        //             // $data = 1;
        //             // dd($data->links());

        $data = User::where(function($query) use ($search){
                $query->OrWhereRaw($search ? "id = '$search'" : 1)
                ->OrWhereRaw($search ? "name like '%$search%'" : 1)
                ->OrWhereRaw($search ? "email like '%$search%'" : 1);
                })
                ->where('role_id', '!=', 1)->orderBy('id', 'DESC')->paginate($paginate);

        return view('user.list', compact('data'));
    }

    public function create()
    {
        
        $role = Role::where('id', '!=', 1)->get();
        $data = '';
        return view('user.add', compact('role', 'data'));
    }

    public function edit($id)
    {
        $data = User::where('id', $id)->first();
        // return $data;
        $role = Role::where('id', '!=', 1)->get();

        return view('user.add', compact('role', 'data'));

    }


    public function store(Request $request)
    {
        // return $request;
        $data = User::where('email', $request->email)->first();
        if($data)
        {
            return redirect()->route('user.index')->with(['message' => 'El correo ya existe.', 'alert-type' => 'error']);
        }
        
        
        DB::beginTransaction();
        try {
            
            $user = User::create([
                'ci'=>$request->ci,
                'name' =>  $request->name,
                'role_id' => $request->role_id,
                'email' => $request->email,
                'avatar' => 'users/default.png',
                'password' => bcrypt($request->password),
                'registerUser_id' => Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('user.index')->with(['message' => 'Usuario registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('user.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }  

    }

    public function update(Request $request, User $user)
    {
        // return $request;
        DB::beginTransaction();
        try {
            
            $user->update([
                'ci'=>$request->ci,
                'name' =>  $request->name,
                'role_id' => $request->role_id,
                'email' => $request->email,
                'avatar' => 'users/default.png',
            ]);
            if($request->password)
            {
                // return $request;
                $user->update([
                    'password' => bcrypt($request->password)
                ]);
            }
            DB::commit();
            return redirect()->route('user.index')->with(['message' => 'Usuario actualizado exitosamente.', 'alert-type' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('user.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }  
    }


    public function inhabilitarUser($user)
    {
        DB::beginTransaction();
        try {
            User::where('id', $user)
                ->update([
                    'status'=>0,
                ]);
            DB::commit();
            return redirect()->route('user.index')->with(['message' => 'Inhabilitado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('user.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function habilitarUser($user)
    {
        DB::beginTransaction();
        try {
            User::where('id', $user)
                ->update([
                    'status'=>1,
                ]);
            DB::commit();
            return redirect()->route('user.index')->with(['message' => 'Habilitado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('user.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
