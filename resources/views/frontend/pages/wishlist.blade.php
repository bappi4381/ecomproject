@extends('frontend.layout')

@section('title', 'My Wishlist')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8 animate-fade-in-up">
            <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-2">/</span>
                <span class="text-slate-900">Wishlist</span>
            </nav>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tighter uppercase italic">My Wishlist</h1>
                <span class="text-xs font-bold text-slate-500 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100">
                    {{ $wishlistItems->count() }} Items
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                <i class="bi bi-check-circle-fill text-xl"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if($wishlistItems->isEmpty())
            <div class="bg-white rounded-[32px] p-16 text-center shadow-sm border border-slate-100 animate-fade-in-up">
                <div class="w-32 h-32 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center text-6xl mx-auto mb-8 shadow-inner">
                    <i class="bi bi-heart"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase mb-4">Your wishlist is empty</h2>
                <p class="text-slate-500 font-medium mb-8 max-w-md mx-auto">Looks like you haven't added any products to your wishlist yet. Explore our premium collection and find something you love.</p>
                <a href="{{ route('books.index') }}" class="inline-block px-10 py-4 bg-slate-900 text-white font-black uppercase tracking-[0.2em] text-[10px] rounded-xl hover:bg-black hover:-translate-y-1 transition-all shadow-xl shadow-slate-900/20">
                    Browse Products
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in-up" style="animation-delay: 100ms;">
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->product;
                    @endphp
                    <div class="group bg-white rounded-3xl p-4 shadow-sm hover:shadow-2xl hover:shadow-primary/10 transition-all duration-300 border border-slate-100 relative flex flex-col h-full">
                        
                        <!-- Remove from Wishlist Button -->
                        <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="absolute top-6 right-6 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 bg-white/80 backdrop-blur-md text-rose-500 rounded-full flex items-center justify-center shadow-sm hover:bg-rose-500 hover:text-white transition-colors" title="Remove from wishlist">
                                <i class="bi bi-trash-fill text-xs"></i>
                            </button>
                        </form>

                        <!-- Product Image -->
                        <a href="{{ route('books.show', $product->slug ?? $product->id) }}" class="block relative aspect-square rounded-2xl overflow-hidden bg-slate-50 mb-6">
                            @if($product->thumbnail)
                                <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="bi bi-image text-4xl"></i>
                                </div>
                            @endif
                            
                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center">
                                    <span class="bg-rose-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg">Out of Stock</span>
                                </div>
                            @endif
                        </a>

                        <!-- Product Info -->
                        <div class="flex-grow flex flex-col">
                            <!-- Category Badge -->
                            @if($product->category)
                                <div class="mb-3">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-lg">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                            @endif

                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight leading-snug mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                <a href="{{ route('books.show', $product->slug ?? $product->id) }}">{{ $product->name }}</a>
                            </h3>

                            <div class="mt-auto pt-4 flex items-center justify-between">
                                <div class="flex flex-col">
                                    @if($product->discount_price)
                                        <span class="text-xs text-slate-400 font-bold line-through">tk. {{ number_format($product->price, 2) }}</span>
                                        <span class="text-lg font-black text-slate-900 tracking-tighter">tk. {{ number_format($product->discount_price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-black text-slate-900 tracking-tighter">tk. {{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-lg shadow-slate-900/20 hover:bg-primary hover:shadow-primary/30 hover:-translate-y-1 transition-all group/btn">
                                            <i class="bi bi-cart-plus group-hover/btn:animate-bounce"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
