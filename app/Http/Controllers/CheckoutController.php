<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CheckoutMail;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store()
    {
        $carts = Cart::where('user_id', Auth::user()->id);
        $cartUser = $carts->get();

        $transaction = Transaction::create([
            'user_id' => Auth::user()->id
        ]);

        foreach ($cartUser as $cart) {
            $transaction->detail()->create([
                'product_id' => $cart->product_id,
                'qty' => $cart->qty
            ]);
        }


        // Restfill API Mailgun
        Mail::to($carts->first()->user->email)->send(new CheckoutMail($cartUser));

        // Setelah user mencheckout product di cart
        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect('/')->with('success', 'Anda telah berhasil checkout, check email segera!');
    }
}
