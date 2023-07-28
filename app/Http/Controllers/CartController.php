<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;
use RealRashid\SweetAlert\Facades\Alert;


class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        Alert::success('Warning Title', 'Warning Message');
        return view('cart.index', compact('carts'));
    }

    public function store(Request $request)
    {
        $duplicate = Cart::where('product_id', $request->product_id)->first();
        if ($duplicate) {
            return redirect('cart')->with('error', 'Barang sudah ada di keranjang');
        }
        Cart::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'qty' => 1
        ]);

        return redirect('cart')->with('success', 'barang berhasil ditambakan ke keranjang');
    }

    public function update(Request $request, $id)
    {
        Cart::where('id', $id)->update([
            'qty' => $request->quantity,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        DB::table('carts')->where('id', $id)->delete();
        Alert::warning('Warning Title', 'Warning Message');
        return redirect('cart')->with('error', 'barang berhasil dihapus dari keranjang');
    }
}
