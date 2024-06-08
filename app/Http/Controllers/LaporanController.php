<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::count();
        if ($request->filled('q')) {
            $query = $query->where('id', 'LIKE', '%' . $request->q . '%');
        }
        if ($request->filled('tanggal_mulai')) {
            $query = $query->where('created_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query = $query->where('created_at', '<=', $request->tanggal_selesai);
        }

    
        $order = Order::All();
        $totalpenjualan = Order::sum('total');
        $title = "Laporan Penjualan";
        
        if ($request->page == 'laporan') {
            return view('laporan.laporan', compact('order', 'totalpenjualan', 'title'));
        }

        return view('laporan.laporan', compact('order', 'totalpenjualan', 'title'));
    }
}

