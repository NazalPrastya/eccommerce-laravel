@extends('template.user')
@include('sweetalert::alert')

@section('title')
    Cart
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('css/cart.css')}}"> 
@endsection

@section('content')
<div class="container">
    <!-- success message & Error message -->
    @if (Session::has('success'))
    <div class="alert alert-success" role="alert">
       {{ Session::get('success') }}
     </div>
    @endif

    @if (Session::has('error'))
    <div class="alert alert-danger" role="alert">
       {{ Session::get('error') }}
     </div>
    @endif

        @php
            $total = 0;    
        @endphp
    {{-- @if ($carts->count() == 0)
    <p style="text-align:center;">Your Cart is Empty</p>
    @else --}}
    
<div class="">
    <h3 class="">{{ $carts->count() }} Item in your cart</h3>
    <a href="/shop" class="btn btn-primary mb-3">Daftar Barang yang lain</a>
</div>

@foreach ($carts as $cart)

<div class="cart">
        <div class="row">
            <div class="col-lg-3">
            <img class="img-cart" src="{{asset('storage/images/product.jpg')}}" alt="">
            </div>
            <div class="col-lg-9">
                <div class="top">
                    <p class="item-name">{{ $cart->product->name }}</p>
                    <div class="top-right">
                        <p class="">Rp.{{ number_format($cart->product->price) }}</p>
                        <select name="qty" class="quantity" data-item="{{ $cart->id }}">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{$i}}" {{ $cart->qty == $i ? 'selected' : '' }} >{{ $i }}</option>
                        @endfor
                        </select>
                        <!-- Subtotal -->
                        <p class="total-item">IDR.{{ number_format($cart->product->price * $cart->qty) }}</p>
                    </div>
                </div>
                <hr class="mt-2 mb-2">
                <div class="bottom">
                   <div class="row">
                        <p class="col-lg-6 item-desc">
                            {{ $cart->product->desc }}
                        </p>
                        <div class="offset-lg-4">

                        </div>
                        <div class="col-lg-2">
                            <!-- delete cart --> 
                                <form action="{{ route('delete',$cart->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('yakin ingin hapus {{ $cart->product->name }} ?')">Remove</button>
                                </form>
                               
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
    @php
    $total += ($cart->product->price * $cart->qty);
    @endphp
@endforeach


   

    <div class="totalz">
    <h4 class="total-price">Total Price: IDR. {{ number_format($total) }}</h4>
</div>
</div>

<form action="/checkout" method="POST" style="margin-left: 700px;">
@csrf
<button type="submit" class="btn btn-primary" onclick="return confirm('yakin ini aja yang di checkout?')">Checkout</button>
</form>
    {{-- @endif --}}
@endsection

@section('script')
<script type="text/javascript">
    (function(){
    const classname = document.querySelectorAll('.quantity');

    Array.from(classname).forEach(function(element){
     element.addEventListener('change', function(){
        const id = element.getAttribute('data-item');
        axios.patch(`/cart/${id}`, {
            quantity: this.value,
            id: id
          })
          .then(function (response) {
            //console.log(response);
            window.location.href = '/cart'
          })
          .catch(function (error) {
            console.log(error);
          });
   })
 })
    })();
</script>
<script type="text/javascript" src="{{asset('js/script.js')}}"></script>
@endsection