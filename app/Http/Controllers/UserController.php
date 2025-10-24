<?php

namespace App\Http\Controllers;

use App\Models\Sph;
use App\Models\Call;
use App\Models\User;
use App\Models\Customer;
use App\Models\Preorder;
use App\Models\Presentasi;
use Illuminate\Http\Request;
use App\Models\Kegiatan_other;
use App\Models\Kegiatan_visit;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\SphProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Category\Principal;

class UserController extends Controller
{
    public function register(){
        $roles  = Role::all();
        return view('setting.user.create',compact('roles'));
    }

    public function register_action(Request $request){

        $request->validate([
            'role' => 'required',
            'username' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'password_confirm' => 'required|same:password',



        ]);

        $user = new User;
        $user->username = $request->username;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->address = "Depok";
        $user->city = "Jakarta";
        $user->country  =  "indonesia";
        $user->assignRole($request->role);

        $user->save();

        return redirect()->route('setting.user')->with('success', 'Registration success. Please login!');

    }

    public function editUser($id)
    {
        $user = User::find($id);
        $role = Role::all();

        return view('setting.user.edit',compact('user','role'));

    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([

        ]);

        $user = User::find($id);
        $user->username = $request->username;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->address = $request->address;
        $user->city = $request->city;
        $user->country  = "Indonesia";
        $user->role = $request->role;
        $user->about = $request->about;
        $user->syncRoles($request->role);

        $user->save();

        return redirect()->route('setting.user')->with('success', 'User Berhasil Di Update !!');
    }

    public function deleteUser($id)
    {
        $user = user::find($id);
        $user->delete();
        return redirect()->route('setting.user')->with('success', 'Data Berhasil Di Hapus !!');
    }

    public function login(){
        $data['title'] = 'Login';
        return view('auth.login');
    }

    public function login_action(Request $request){
        $request->validate([
            'userEmail' => 'required',
            'userPassword' => 'required'

        ]);

        if (Auth::attempt(['email' => $request->userEmail, 'password' => $request->userPassword])) {
            $request->session()->regenerate();
            if(Auth::user()->role == 'sales'){
                return redirect()->route('user.profile',Auth::user()->id)->with('success', 'Login Berhasil !!');
            }
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }

    public function profile(Request $request, $id)
    {
        $user =  User::find($id);

        // Validate optional date inputs (Y-m-d) like dashboard
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
        ]);

        if (!$validator->fails() && $request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
            } catch (\Exception $e) {
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
            }
        } else {
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
        }

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        $dateKey = $start->format('Ymd') . '_' . $end->format('Ymd');

        // Brand Chart By Sales (refactored to use SphProduct + principal names)
        $brand_rows = SphProduct::query()
            ->whereNotNull('brand_id')
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('sph', function ($q) use ($id) {
                $q->where('user_id', $id);
            })
            ->selectRaw('brand_id as brand, COUNT(*) as value')
            ->groupBy('brand_id')
            ->orderBy('brand_id', 'asc')
            ->get();

        $brandNames = Principal::query()
            ->whereIn('id', $brand_rows->pluck('brand')->all())
            ->pluck('name', 'id');

        $brand_series = [];
        foreach ($brand_rows as $row) {
            $name = $brandNames[$row->brand] ?? (string) $row->brand;
            $brand_series[] = ['x' => $name, 'y' => (int) $row->value];
        }

        // Produk Chart By Sales - refactored to use SphProduct
        $rows = SphProduct::query()
            ->whereNotNull('product_id')
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('sph', function ($q) use ($id) {
                $q->where('user_id', $id);
            })
            ->selectRaw('product_id, COUNT(*) as value')
            ->groupBy('product_id')
            ->get();

        $productNames = Product::query()
            ->whereIn('id', $rows->pluck('product_id')->all())
            ->pluck('nama_produk', 'id');

        $data_produk = [];
        $product_series = [];
        foreach ($rows as $row) {
            $name = $productNames[$row->product_id] ?? (string) $row->product_id;
            $data_produk[$name] = (int) $row->value;
            $product_series[] = ['x' => $name, 'y' => (int) $row->value];
        }

        // KPI Weekly/Monthly and totals (unchanged semantics)
        $data_customer = Customer::where('user_id', $id)->count();
        $data_call  = Call::where('user_id', $id)->count();
        $data_visit = Kegiatan_visit::where('user_id', $id)->count();
        $data_other = Kegiatan_other::where('user_id', $id)->count();
        $data_presentasi = Presentasi::where('user_id', $id)->count();
        $data_sph =  Sph::where('user_id', $id)->count();
        $data_po = Preorder::where('user_id', $id)->count();
        // Customer By Sales
        $customer_by_sales = Customer::where('user_id', $id)->orderBy('id', 'asc')->take(5)->get();

        // Sales target filtered by date range
        $sales_target = $user->sph()->whereBetween('created_at', [$start, $end])->sum('nilai_pagu');

        $filter_start = $start;
        $filter_end = $end;

        return view('setting.user.profile', compact('user','customer_by_sales', 'data_customer','data_call','data_visit', 'data_other', 'data_presentasi','data_sph','data_po','brand_series','product_series','data_produk','filter_start','filter_end','sales_target'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
