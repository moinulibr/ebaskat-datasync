<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\EBasketMailer;
use App\Models\OrderTrack;
use App\Models\VendorOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Datatables;
use PDF;

class OrderSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    
    public function search()
    {
        return view('admin.order.search-order');
    }
}
