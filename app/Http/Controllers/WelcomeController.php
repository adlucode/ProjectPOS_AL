<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $product = Product::count();
        $customer = Customer::count();
        $user = User::count();
        $dataorder = Order::count();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_transaksi = Order::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('total');

            $pendapatan = $total_transaksi;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        return view('dashboard',[
            "customer"=> $customer,
            "product"=> $product,
            "user"=> $user,
            "dataOrder"=> Order::paginate(5),
            "title"=>"Dashboard"
        ]);
        
    }

    
}
