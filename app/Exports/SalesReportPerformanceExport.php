<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesReportPerformanceExport implements WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function headings(): array

    {
        $user = new User;
        $total = ($user->new_customer_monthly($this->id)/8)*100 + ($user->call_monthly($this->id)/60)*100 + ($user->visit_monthly($this->id)/16)*100 +($user->presentasi_monthly($this->id)/2)*100 + ($user->sph_monthly($this->id)/8)*100 + ($user->preorder_monthly($this->id)/1)*100 ;
        $total = round($total/6,2);
        return [
            ['Nama Sales', $user::find($this->id)->username],
            [' '],
            ['No','Performance' ,'Weekly','Monthly', 'Progress'],
            ['1','New Customer',$user->new_customer_weekly($this->id), $user->new_customer_monthly($this->id),($user->new_customer_monthly($this->id)/8)*100  .  '%'],
            ['2','Promotion',$user->call_weekly($this->id), $user->call_monthly($this->id), ($user->call_monthly($this->id)/60)*100  . '%'],
            ['3','Visit',$user->visit_weekly($this->id), $user->visit_monthly($this->id),($user->visit_monthly($this->id)/16)*100  . '%'],
            ['4','Presentasi',$user->presentasi_weekly($this->id), $user->presentasi_monthly($this->id),($user->presentasi_monthly($this->id)/2)*100  . '%'],
            ['5','Quotation',$user->sph_weekly($this->id), $user->sph_monthly($this->id),($user->sph_monthly($this->id)/8)*100  . '%'],
            ['6','Purchase Order',$user->preorder_weekly($this->id), $user->preorder_monthly($this->id),($user->preorder_monthly($this->id)/1)*100  . '%'],
            ['','','','',$total  . '%']
        ];
    }
}
