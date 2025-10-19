<?php

namespace App\Exports;

use App\Models\Sph;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Preorder;
use App\Models\Kegiatan_visit;
use Illuminate\View\View;
use App\Models\Presentasi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class TabulasiExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
   

    public function view(): View
    {
        $customer = Customer::all();

        $progress = array();
        foreach ($customer as $x) {
            $progress[$x->id] = 
               $this->check_null(Call::where('customer_id', $x->id)->get()->count()) + $this->check_null(Kegiatan_visit::where('customer_id', $x->id)->get()->count())+ $this->check_null(Sph::where('customer_id', $x->id)->get()->count())+ $this->check_null(Preorder::where('customer_id', $x->id)->get()->count())+$this->check_null(Presentasi::where('customer_id', $x->id)->get()->count());
            
        }

        $by_user = Auth::user()->id;

        return view('pdf.excel', [
            'customer' => customer::all(),
            'customer_by_sales' => customer::where('user_id', $by_user)->get(),
            'progress'=> $progress
        ]);
    }

    public function check_null($data)
    {

        if (empty($data)) {
            return 0;
        } else {
            return 1;
        }
    }
}
