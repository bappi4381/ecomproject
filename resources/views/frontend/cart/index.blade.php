@extends('frontend.layout')

@section('title', 'Shopping Cart')

@section('content')
<!-- Page Header -->
<div class="bg-slate-900 py-16 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary rounded-full blur-[150px]"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tighter uppercase mb-4">Your Shopping Cart</h1>
        <div class="flex items-center justify-center gap-2 text-slate-400 font-bold text-xs uppercase tracking-widest">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <i class="bi bi-chevron-right text-[10px]"></i>
            <span class="text-white">Cart</span>
        </div>
    </div>
</div>

<section class="py-24 bg-slate-50 min-h-[600px]">
    <div class="max-w-7xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3 animate-fade-in-down">
                <i class="bi bi-check-circle-fill text-xl"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Cart Items List -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100">
                                        <th class="py-6 px-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Product</th>
                                        <th class="py-6 px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Price</th>
                                        <th class="py-6 px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Quantity</th>
                                        <th class="py-6 px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Subtotal</th>
                                        <th class="py-6 px-8 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @php $total = 0; @endphp
                                    @foreach($cart as $id => $item)
                                        @php 
                                            $subtotal = $item['price'] * $item['quantity']; 
                                            $total += $subtotal; 
                                        @endphp
                                        <tr class="group hover:bg-slate-50/50 transition-colors">
                                            <!-- Product Info -->
                                            <td class="py-6 px-8">
                                                <div class="flex items-center gap-6">
                                                    <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center p-3 border border-slate-100 group-hover:scale-105 transition-transform">
                                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                                             alt="{{ $item['name'] }}" 
                                                             class="max-w-full max-h-full object-contain">
                                                    </div>
                                                    <div class="flex flex-col gap-1">
                                                        <span class="text-[10px] font-black uppercase text-primary tracking-widest">Electronics</span>
                                                        <h4 class="text-sm font-black text-slate-900 group-hover:text-primary transition-colors leading-tight">{{ $item['name'] }}</h4>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Price -->
                                            <td class="py-6 px-4 text-center">
                                                <span class="text-sm font-black text-slate-900">tk. {{ number_format($item['price'], 2) }}</span>
                                            </td>

                                            <!-- Quantity -->
                                            <td class="py-6 px-4">
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center gap-2">
                                                    @csrf
                                                    <div class="flex items-center bg-slate-100 rounded-xl px-2 py-1">
                                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                                               class="w-12 bg-transparent border-none text-center text-sm font-black focus:ring-0 outline-none"
                                                               onchange="this.form.submit()">
                                                    </div>
                                                    <button type="submit" class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Subtotal -->
                                            <td class="py-6 px-4 text-center">
                                                <span class="text-sm font-black text-primary tracking-tighter">tk. {{ number_format($subtotal, 2) }}</span>
                                            </td>

                                            <!-- Action -->
                                            <td class="py-6 px-8 text-right">
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm active:scale-95">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-between items-center">
                        <a href="{{ route('books.index') }}" class="flex items-center gap-3 text-slate-500 hover:text-primary font-black uppercase text-[10px] tracking-widest transition-colors">
                            <i class="bi bi-arrow-left text-lg"></i>
                            Back to Store
                        </a>
                        <div class="flex items-center gap-3">
                            <input type="text" placeholder="Coupon Code (Coming Soon)" disabled class="bg-white border border-slate-200 rounded-xl px-6 py-3 text-xs font-bold outline-none cursor-not-allowed opacity-50">
                            <button type="button" disabled class="bg-slate-300 text-white px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest cursor-not-allowed opacity-50">Apply</button>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-slate-900 text-white rounded-[40px] p-10 sticky top-24 shadow-2xl shadow-slate-900/40 relative overflow-hidden">
                        <!-- Decorative background element -->
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/20 rounded-full blur-[80px]"></div>
                        
                        <h3 class="text-2xl font-black tracking-tighter uppercase mb-8 relative z-10">Order Summary</h3>
                        
                        <div class="space-y-6 mb-10 relative z-10">
                            <div class="flex justify-between items-center text-slate-400">
                                <span class="text-xs font-black uppercase tracking-widest">Subtotal</span>
                                <span class="font-bold">tk. {{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-400">
                                <span class="text-xs font-black uppercase tracking-widest">Shipping</span>
                                <span class="font-bold text-green-400">FREE</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-400">
                                <span class="text-xs font-black uppercase tracking-widest">Tax (Estimated)</span>
                                <span class="font-bold">tk. 0.00</span>
                            </div>
                            <div class="h-px bg-white/10 my-6"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-black uppercase tracking-widest text-primary">Total Amount</span>
                                <span class="text-2xl font-black tracking-tighter">tk. {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <div class="space-y-4 relative z-10">
                            <a href="{{ route('checkout.index') }}" class="block w-full py-5 bg-primary hover:bg-primary-dark text-white text-center font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-primary/20 transition-all active:scale-95 no-underline">
                                Proceed to Checkout
                            </a>
                            <div class="flex items-center justify-center gap-4 opacity-50 mt-6 grayscale brightness-200">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4" alt="Visa">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6" alt="Mastercard">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4" alt="PayPal">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="max-w-2xl mx-auto py-32 text-center bg-white rounded-[48px] shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 group">
                    <i class="bi bi-cart-x text-6xl text-slate-200 group-hover:text-primary transition-colors"></i>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tighter uppercase">Your cart is empty</h3>
                <p class="text-slate-500 font-bold mb-10 px-12">Looks like you haven't added any premium gadgets to your cart yet. Explore our latest tech collections!</p>
                <a href="{{ route('books.index') }}" class="inline-block px-12 py-5 bg-primary text-white font-black uppercase text-xs tracking-widest rounded-2xl shadow-xl shadow-primary/30 transition-all hover:-translate-y-1 active:scale-95 no-underline">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
