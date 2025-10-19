<?php

namespace App\Exports;

use App\Models\Sph;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Presentasi;
use App\Models\Preorder;
use App\Models\Kegiatan_visit;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportDataByModel implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start_date;
    protected $end_date;
    protected $model;

    public function __construct($start_date, $end_date, $model )
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->model = $model;
    }

    public function view(): View
    {

        if($this->model == 'customer'){
            return view('das-view.excel.customer', [
                'customer' => customer::whereBetween('created_at', [$this->start_date, $this->end_date])->get(),
            ]);
        }elseif($this->model == 'call'){
             return view('das-view.excel.call', [
                'call' => Call::whereBetween('created_at', [$this->start_date, $this->end_date])->get(),
                'title' => 'Call'
            ]);
        }elseif($this->model == 'visit'){
             return view('das-view.excel.visit', [
                'visit' => Kegiatan_visit::whereBetween('created_at', [$this->start_date, $this->end_date])->get(),
                'title' => 'Visit'
            ]);
        }elseif($this->model == 'quotation'){
             return view('das-view.excel.quotation', [
                'quotation' => sph::whereBetween('created_at', [$this->start_date, $this->end_date])->get(),
                'title' => 'Quotation'
            ]);
        }elseif($this->model == 'presentasi'){
            return view('das-view.excel.presentasi', [
                'presentasi' => Presentasi::whereBetween('created_at', [$this->start_date, $this->end_date])->get(),
                'title' => 'Presentasi'
            ]);
        }elseif($this->model == 'preorder'){
            return view('das-view.excel.preorder', [
                'preorder' => Preorder::whereBetween('created_at', [$this->start_date, $this->end_date])->get(),
                'title' => 'Preorder'
            ]);
        }else{
            return null;
        }
    }
}
