<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Venturecraft\Revisionable\Revision;

class EventLogController extends Controller
{
    public function index(){
        $customer_event = Revision::all();

        return $customer_event;
    }
}
