<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function data()
    {
        $user = User::with('roles')->orderBy('id', 'desc')->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('role_badge', function ($user) {
                $role = $user->roles->first()->name ?? ($user->level == 1 ? 'admin' : 'cashier');
                if ($role == 'admin') {
                    return '<span class="badge" style="background:#dc2626;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">Admin / Owner</span>';
                } elseif ($role == 'manager') {
                    return '<span class="badge" style="background:#ea580c;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">Manager</span>';
                } elseif ($role == 'waiter') {
                    return '<span class="badge" style="background:#0284c7;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">Waiter</span>';
                }
                return '<span class="badge" style="background:#16a34a;color:#fff;padding:4px 8px;border-radius:4px;font-weight:700;">Cashier / Staff</span>';
            })
            ->addColumn('aksi', function ($user) {
                return '
                <div class="table-actions-group">
                    <button type="button" onclick="editForm(`'. route('user.update', $user->id) .'`)" class="btn-table-action btn-edit" title="Edit Staff"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('user.destroy', $user->id) .'`)" class="btn-table-action btn-delete" title="Delete Staff"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['role_badge', 'aksi'])
            ->make(true);
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
    public function store(Request $request)
    {
        $roleName = $request->role ?? 'cashier';
        $level = ($roleName === 'admin') ? 1 : 2;

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->level = $level;
        $user->foto = '/img/user.png';
        $user->save();

        $user->assignRole($roleName);

        return response()->json('Data saved successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $user->role = $user->roles->first()->name ?? ($user->level == 1 ? 'admin' : 'cashier');

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->has('role')) {
            $roleName = $request->role;
            $user->level = ($roleName === 'admin') ? 1 : 2;
            $user->syncRoles([$roleName]);
        }

        if ($request->has('password') && $request->password != "") {
            $user->password = bcrypt($request->password);
        }
        $user->update();

        return response()->json('Data saved successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return response(null, 204);
    }

    public function profil()
    {
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        
        $user->name = $request->name;
        if ($request->has('password') && $request->password != "") {
            if (Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Confirm password is incorrect', 422);
                }
            } else {
                return response()->json('The old password is incorrect', 422);
            }
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            $user->foto = "/img/$nama";
        }

        $user->update();

        return response()->json($user, 200);
    }
}
