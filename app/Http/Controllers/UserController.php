<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Models\ErrorLog;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['products'])->orderBy('id', 'DESC')->select('users.*');

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->editColumn('avatar', function($data) {
                        return '<img src="'. MyHelper::get_uploaded_file_url($data->avatar) .'" alt="picture" width="50" height="50" class="rounded-circle">';
                    })
                    ->addColumn('products_count', function($data) {
                        return $data->products()->count();
                    })
                    ->rawColumns(['avatar'])
                    ->make(true);
        }
        $data = [
            'title' => 'List Akun',
            'datatable' => true
        ];

        return view('dashboard.user.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Akun Admin',
        ];

        return view('dashboard.user.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'name' => ['required', 'max:255'],
            'username' => ['required', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
            'phone' => ['nullable', 'digits_between:10,15'],
        ]);

        $User = new User();
        $User->name = $request->name;
        $User->username = $request->username;
        $User->email = $request->email;
        $User->phone = $request->phone;
        $User->role = 'ADMIN';
        $User->password = Hash::make($request->password);
        if ($request->hasFile('avatar')) {
            $filename = Str::random(32) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $file_path = $request->file('avatar')->storeAs('public/uploads', $filename);
        }
        $User->avatar = isset($file_path) ? $file_path : '';
        $User->save();

        return redirect()->route('dashboard.master.user.index')->with('success', 'Data berhasil disimpan');
    }

    public function destroy($id)
    {
        $user = User::with(['products'])->findOrFail($id);
        try {
            $user = $user;
            if (Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            if (Storage::exists($user->shop_logo)) {
                Storage::delete($user->shop_logo);
            }

            // Jika user seller, clear produknya
            if ($user->role == 'SELLER' && $user->products->count() > 0) {
                foreach($user->products as $userProduct) {
                    if (Storage::exists($userProduct->thumbnail)) {
                        Storage::delete($userProduct->thumbnail);
                    }

                    // Clear product galleries first
                    if ($userProduct->product_galleries->count() > 0) {
                        foreach($userProduct->product_galleries as $gal) {
                            if (Storage::exists($gal->thumbnail)) {
                                Storage::delete($gal->thumbnail);
                            }

                            $gal->delete();
                        }
                    }

                    // finally, delete the product itself
                    $userProduct->delete();
                }
            }

            $user->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus data.');
        } catch (Exception $e) {
            ErrorLog::create([
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'stack_trace' => json_encode($e->getTrace()),
                'url' => url()->full(),
                'user_id' => Auth::user()->id,
            ]);

            return redirect()->back()->with([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $data = [
            'title' => 'Edit Akun',
            'user' => User::findOrFail($id)
        ];

        return view('dashboard.user.show', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'username' => ['required', 'max:255', 'unique:users,username,' . $id],
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'phone' => ['nullable', 'digits_between:10,15'],
        ]);

        $User = User::findOrFail($id);
        $User->name = $request->name;
        $User->username = $request->username;
        $User->email = $request->email;
        $User->phone = $request->phone;
        if ($request->password) {
            $User->password = Hash::make($request->password);
        }
        if ($request->hasFile('avatar')) {
            if ($User->avatar != '') {
                Storage::delete($User->avatar);
            }
            $filename = Str::random(32) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $file_path = $request->file('avatar')->storeAs('public/uploads', $filename);
        }
        $User->avatar = isset($file_path) ? $file_path : $User->avatar;
        $User->save();

        return redirect()->back()->with('success', 'Profil berhasil diupdate');
    }

    public function update_toko(Request $request)
    {
        $request->validate([
            'shop_logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'shop_name' => ['required', 'max:255'],
            'shop_desc' => ['nullable'],
        ]);

        $User = User::findOrFail(Auth::user()->id);
        $User->shop_name = $request->shop_name;
        $User->shop_desc = $request->shop_desc;
        if ($request->hasFile('shop_logo')) {
            if ($User->shop_logo != '') {
                Storage::delete($User->shop_logo);
            }
            $filename = Str::random(32) . '.' . $request->file('shop_logo')->getClientOriginalExtension();
            $file_path = $request->file('shop_logo')->storeAs('public/uploads', $filename);
        }
        $User->shop_logo = isset($file_path) ? $file_path : $User->shop_logo;
        $User->save();

        return redirect()->back()->with('success', 'Toko berhasil diupdate');
    }

    public function profile()
    {
        $data = [
            'title' => 'Profile Saya',
            'user' => Auth::user()
        ];

        return view('dashboard.user.profile', $data);
    }

    public function update_profile(Request $request)
    {
        $id = Auth::user()->id;
        $request->validate([
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'username' => ['required', 'max:255', 'unique:users,username,' . $id],
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email',  'unique:users,email,' . $id],
            'phone' => ['nullable', 'digits_between:10,15'],
        ]);

        $User = User::findOrFail($id);
        $User->name = $request->name;
        $User->username = $request->username;
        $User->email = $request->email;
        $User->phone = $request->phone;
        if ($request->hasFile('avatar')) {
            if ($User->avatar != '') {
                Storage::delete($User->avatar);
            }
            $filename = Str::random(32) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $file_path = $request->file('avatar')->storeAs('public/uploads', $filename);
        }
        $User->avatar = isset($file_path) ? $file_path : $User->avatar;
        $User->save();

        return redirect()->back()->with('success', 'Profil berhasil diupdate');
    }

}
