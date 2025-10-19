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

    public function profile($id)
    {
        $user =  User::find($id);

        // Brand Chart By Sales

        $data_brand = DB::table('sphs')->select('brand', DB::raw("count(brand) as value"))
            ->where('user_id', $id)
            ->groupBy('brand')
            ->orderBy('sphs.brand', 'asc')
            ->get();


        // Produk Chart By Sales
        $produkArray = [];

        // Loop through each Sph object and extract the product data
        $products = Sph::where('user_id', $id)->get();
        $products->each(function ($product) use (&$produkArray) {
            $data = json_decode($product->produk, true);
            if (is_array($data['produk'])) { // add a check for array type
                $produkArray = array_merge($produkArray, $data['produk']);
            }
        });

        // Count the number of occurrences of each product
        $produkCounts = array_count_values($produkArray);

        // Create a new array with unique product names as keys and their counts as values
        $data_produk = [];
        foreach ($produkCounts as $key => $value) {
            $data_produk[$key] = $value;
        }


        // KPI Weekly By Sales
        // KPI Monthly By Sales
        // Total Data By Sales
        $data_customer = Customer::where('user_id', $id)->get()->count();
        $data_call  = Call::where('user_id', $id)->get()->count();
        $data_visit = Kegiatan_visit::where('user_id', $id)->get()->count();
        $data_other = Kegiatan_other::where('user_id', $id)->get()->count();
        $data_presentasi = Presentasi::where('user_id', $id)->get()->count();
        $data_sph =  Sph::where('user_id', $id)->get()->count();
        $data_po = Preorder::where('user_id', $id)->get()->count();
        // Customer By Sales
        $customer_by_sales = Customer::where('user_id', $id)->get()->sortBy('asc')->take(5);
        return view('setting.user.profile', compact('user','customer_by_sales', 'data_customer','data_call','data_visit', 'data_other', 'data_presentasi','data_sph','data_po','data_brand','data_produk'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
