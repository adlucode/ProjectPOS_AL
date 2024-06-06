<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class Orders extends Component
{
    public $total;
    public $order_id;
    public $product_id;
    public $qty=1;
    public $uang;
    public $kembali;
    
    public function render()
    {
        $order=Order::select('*')->where('user_id','=',Auth::user()->id)->orderBy('id','desc')->first();

        $this->total=$order->total;
        $this->kembali=$this->uang-$this->total;
        return view('livewire.orders')
        ->with("data",$order)
        ->with("dataProduk",Product::where('stock','>','0')->get())
        ->with("dataOrderDetail",Order_detail::where('order_id','=',$order->id)->get());
    }

    public function store()
    {
        $this->validate([

            'product_id'=>'required'
        ]);
        $order=Order::select('*')->where('user_id','=',Auth::user()->id)->orderBy('id','desc')->first();
        $this->order_id=$order->id;
        $produk=Product::where('id','=',$this->product_id)->get();
        $harga=$produk[0]->price;
        Order_detail::create([
            'order_id'=>$this->order_id,
            'product_id'=>$this->product_id,
            'qty'=>$this->qty,
            'price'=>$harga
        ]);


        $total=$order->total;
        $total=$total+($harga*$this->qty);
        Order::where('id','=',$this->order_id)->update([
            'total'=>$total
        ]);
        $this->product_id=NULL;
        $this->qty=1;
    }

    public function delete($order_detail_id)
    {
        $order_detail=Order_detail::find($order_detail_id);
    $order_detail->delete();

    //update total
    $order_detail=Order_detail::select('*')->where('order_id','=',$this->order_id)->get();
    $total=0;
    foreach($order_detail as $od){
        $total+=$od->qty*$od->price;
    }

    try{
        Order::where('id','=',$this->order_id)->update([
            'total'=>$total
        ]);
    }catch(Exception $e){
        dd($e);
    }
    }
    

    public function receipt($id)
    {
        //update stok
        $order_detail = Order_detail::select('*')->where('order_id','=',$id)->get();
    //dd($order_detail);
    foreach ($order_detail as $od) {
        $stocklama = Product::select('stock')->where('id','=',$od->product_id)->sum('stock');
        $stock = $stocklama - $od->qty;
        try {
            Product::where('id','=',$od->product_id)->update([
                'stock' => $stock
            ]);
        } catch (Exception $e) {
            dd($e);
        }
    }
    return Redirect::route('cetakReceipt')->with(['id' => $id]);
    }
}
