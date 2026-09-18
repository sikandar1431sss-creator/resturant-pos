<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'asc')->get();
        return view('user.index', compact('roles'));
    }

    public function data()
    {
        $user = User::with('roles')->orderBy('id', 'desc')->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('role_badge', function ($user) {
                $roleObj = $user->roles->first();
                $role = $roleObj ? $roleObj->name : ($user->level == 1 ? 'admin' : 'cashier');
                
                if ($role === 'admin') {
                    return '<span class="badge" style="background:#dc2626;color:#fff;padding:4px 9px;border-radius:4px;font-weight:700;"><i class="fa fa-shield"></i> Admin / Owner</span>';
                } elseif ($role === 'manager') {
                    return '<span class="badge" style="background:#ea580c;color:#fff;padding:4px 9px;border-radius:4px;font-weight:700;"><i class="fa fa-briefcase"></i> Manager</span>';
                } elseif ($role === 'cashier') {
                    return '<span class="badge" style="background:#16a34a;color:#fff;padding:4px 9px;border-radius:4px;font-weight:700;"><i class="fa fa-shopping-cart"></i> Cashier</span>';
                } elseif ($role === 'kitchen') {
                    return '<span class="badge" style="background:#d97706;color:#fff;padding:4px 9px;border-radius:4px;font-weight:700;"><i class="fa fa-cutlery"></i> Kitchen Chef</span>';
                } elseif ($role === 'waiter') {
                    return '<span class="badge" style="background:#0284c7;color:#fff;padding:4px 9px;border-radius:4px;font-weight:700;"><i class="fa fa-user"></i> Waiter</span>';
                }
                
                return '<span class="badge" style="background:#6366f1;color:#fff;padding:4px 9px;border-radius:4px;font-weight:700;"><i class="fa fa-id-badge"></i> ' . ucfirst(str_replace('_', ' ', $role)) . '</span>';
            })
            ->addColumn('aksi', function ($user) {
                return '
                <div class="table-actions-group">
                    <button type="button" onclick="editForm(`'. route('user.show', $user->id) .'`, `'. route('user.update', $user->id) .'`)" class="btn-table-action btn-edit" title="Edit Staff"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('user.destroy', $user->id) .'`)" class="btn-table-action btn-delete" title="Delete Staff"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['role_badge', 'aksi'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|string',
        ]);

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

        return response()->json([
            'status' => 'success',
            'message' => 'Staff user created successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roleName = $user->roles->first()->name ?? ($user->level == 1 ? 'admin' : 'cashier');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $roleName,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->has('role') && !empty($request->role)) {
            $roleName = $request->role;
            $user->level = ($roleName === 'admin') ? 1 : 2;
            $user->syncRoles([$roleName]);
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Staff user updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot delete your own logged-in account.'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Staff user deleted successfully'
        ], 200);
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
