<!DOCTYPE html>
<html lang="en">

<head>
	<title>ONEMALL | @yield('title')</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="author" content="ONEMALL">
	<meta name="keywords" content="electronics, gadgets, smartphones, laptops">
	<meta name="description" content="Premium Electronics and Gadget Store">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans text-slate-900 flex flex-col min-h-screen">

	<div id="header-wrap">
		<!-- Top Bar -->
		<div class="bg-slate-950 py-2.5 text-[11px] text-white/70 border-b border-white/5">
			<div class="max-w-7xl mx-auto px-4">
				<div class="flex flex-col md:flex-row justify-between items-center gap-4">
					<div class="flex items-center gap-6">
						<div class="flex items-center gap-1.5 cursor-pointer hover:text-primary transition-colors">
							<img src="https://flagcdn.com/w20/gb.png" width="16" alt="English" class="rounded-sm">
							<span class="font-bold uppercase tracking-widest">English</span>
							<i class="bi bi-chevron-down text-[8px]"></i>
						</div>
						<div class="flex items-center gap-1.5 cursor-pointer hover:text-primary transition-colors border-l border-white/10 pl-6">
							<span class="font-bold uppercase tracking-widest">USD</span>
							<i class="bi bi-chevron-down text-[8px]"></i>
						</div>
					</div>
					<div class="flex items-center gap-8">
						@auth
							<a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 hover:text-primary transition-colors uppercase font-black tracking-widest">
								<i class="bi bi-person-check-fill text-sm text-primary"></i> 
								Hi, {{ explode(' ', Auth::user()->name)[0] }}
							</a>
							<a href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-2 hover:text-red-500 transition-colors uppercase font-black tracking-widest text-slate-400">
								<i class="bi bi-box-arrow-right text-sm"></i> Logout
							</a>
							<form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="hidden">
								@csrf
							</form>
						@else
							<a href="{{ route('user.auth.login') }}" class="flex items-center gap-2 hover:text-primary transition-colors uppercase font-black tracking-widest"><i class="bi bi-person text-sm"></i> Account</a>
							<a href="{{ route('user.auth.login') }}" class="flex items-center gap-2 hover:text-primary transition-colors uppercase font-black tracking-widest"><i class="bi bi-lock text-sm"></i> Login</a>
						@endauth
						<a href="{{ route('wishlist.index') }}" class="flex items-center gap-2 hover:text-primary transition-colors uppercase font-black tracking-widest"><i class="bi bi-heart text-sm"></i> Wishlist</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Main Header -->
		<header class="bg-slate-900 py-6 text-white">
			<div class="max-w-7xl mx-auto px-4">
				<div class="flex flex-col lg:flex-row items-center justify-between gap-8">
					<div class="w-full lg:w-1/4">
						<a href="{{ route('home') }}" class="flex items-center gap-4 group no-underline text-white">
							<div class="bg-primary text-white w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-xl shadow-primary/20 group-hover:scale-110 transition-transform">
								<i class="bi bi-lightning-charge-fill"></i>
							</div>
							<div class="flex flex-col">
								<h1 class="m-0 text-3xl font-black tracking-tighter leading-none uppercase italic">ONEMALL</h1>
								<span class="text-[9px] text-primary-light font-black tracking-[0.3em] uppercase mt-1">Digital Universe</span>
							</div>
						</a>
					</div>
					<div class="w-full lg:w-1/2">
						<form action="{{ route('books.index') }}" method="GET" class="flex bg-white rounded-2xl overflow-hidden shadow-2xl h-14 group">
							<select name="category" class="bg-slate-50 px-8 text-slate-900 font-black text-xs uppercase border-r border-slate-100 outline-none appearance-none cursor-pointer hover:bg-slate-100 transition-colors tracking-widest">
								<option value="">All Categories</option>
								<option>Smartphones</option>
								<option>Laptops</option>
								<option>Smart Watches</option>
								<option>Accessories</option>
							</select>
							<input type="text" name="search" class="flex-1 px-6 text-slate-900 text-sm font-bold outline-none placeholder-slate-400" placeholder="What are you looking for?">
							<button type="submit" class="bg-primary hover:bg-primary-dark text-white px-10 transition-all flex items-center gap-2 active:scale-95">
								<i class="bi bi-search text-lg"></i>
							</button>
						</form>
					</div>
					<div class="w-full lg:w-1/4 flex justify-end">
						@php
							$cart = session()->get('cart', []);
							$cartCount = collect($cart)->sum('quantity');
							$cartTotal = collect($cart)->sum(function ($item) {
								return $item['price'] * $item['quantity'];
							});
						@endphp
						<a href="{{ route('cart.index') }}" class="flex items-center gap-5 group no-underline text-white">
							<div class="relative bg-white/5 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl border border-white/10 shadow-lg group-hover:bg-primary group-hover:border-primary transition-all group-hover:rotate-6">
								<i class="bi bi-cart-dash"></i>
								<span class="absolute -top-2 -right-2 bg-primary text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center shadow-xl border-2 border-slate-900 group-hover:bg-white group-hover:text-primary transition-colors">{{ $cartCount }}</span>
							</div>
							<div class="flex flex-col">
								<span class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Your Cart</span>
								<span class="font-black text-lg tracking-tighter">{{ number_format($cartTotal, 2) }} Tk</span>
							</div>
						</a>
					</div>
				</div>
			</div>
		</header>

		<!-- Navigation Bar -->
		<nav class="bg-slate-800 text-white border-t border-white/5">
			<div class="max-w-7xl mx-auto px-4">
				<div class="flex items-center justify-between">
					<div class="bg-primary px-10 py-5 font-black text-xs uppercase tracking-[0.2em] flex items-center gap-4 cursor-pointer hover:bg-primary-dark transition-all">
						<i class="bi bi-grid-3x3-gap-fill text-lg"></i>
						Shop By Department
						<i class="bi bi-chevron-down text-[10px] ml-4 opacity-50"></i>
					</div>
					<ul class="hidden lg:flex list-none m-0 p-0 gap-10">
						<li><a href="{{ route('home') }}" class="text-white no-underline font-black text-xs uppercase tracking-widest py-5 block hover:text-primary transition-all relative group {{ request()->routeIs('home') ? 'text-primary' : '' }}">
                            Home
                            <span class="absolute bottom-0 left-0 w-0 h-1 bg-primary group-hover:w-full transition-all {{ request()->routeIs('home') ? 'w-full' : '' }}"></span>
                        </a></li>
						<li><a href="{{ route('books.index') }}" class="text-white no-underline font-black text-xs uppercase tracking-widest py-5 block hover:text-primary transition-all relative group {{ request()->routeIs('books.*') ? 'text-primary' : '' }}">
                            Store
                            <span class="absolute bottom-0 left-0 w-0 h-1 bg-primary group-hover:w-full transition-all {{ request()->routeIs('books.*') ? 'w-full' : '' }}"></span>
                        </a></li>
						<li><a href="#" class="text-white no-underline font-black text-xs uppercase tracking-widest py-5 block hover:text-primary transition-all relative group">
                            Flash Deals
                            <span class="absolute bottom-0 left-0 w-0 h-1 bg-primary group-hover:w-full transition-all"></span>
                        </a></li>
						<li><a href="{{ route('frontend.articles') }}" class="text-white no-underline font-black text-xs uppercase tracking-widest py-5 block hover:text-primary transition-all relative group">
                            Tech Blog
                            <span class="absolute bottom-0 left-0 w-0 h-1 bg-primary group-hover:w-full transition-all"></span>
                        </a></li>
						<li><a href="#" class="text-white no-underline font-black text-xs uppercase tracking-widest py-5 block hover:text-primary transition-all relative group">
                            Contact
                            <span class="absolute bottom-0 left-0 w-0 h-1 bg-primary group-hover:w-full transition-all"></span>
                        </a></li>
					</ul>
					<a href="#" class="flex items-center gap-3 text-primary-light font-black text-xs uppercase tracking-widest no-underline group relative">
						<span class="bg-red-500 text-white text-[8px] px-2 py-0.5 rounded-sm absolute -top-5 right-0 uppercase animate-bounce">Limited</span>
						Special Offers <i class="bi bi-gift-fill group-hover:rotate-12 transition-transform"></i>
					</a>
				</div>
			</div>
		</nav>
	</div>

    <main class="flex-grow">
        @yield('content')
    </main>

	<footer class="bg-slate-950 text-white pt-24 pb-12">
		<div class="max-w-7xl mx-auto px-4">
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
				<!-- Brand Info -->
				<div class="space-y-8">
					<a href="{{ route('home') }}" class="flex items-center gap-4 group no-underline text-white">
                        <div class="bg-primary text-white w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h1 class="m-0 text-2xl font-black tracking-tighter leading-none uppercase italic">ONEMALL</h1>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed font-medium">
                        Your ultimate destination for the latest technology and gadgets. We bring the digital future to your doorstep with authentic products and premium service.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all text-xl"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all text-xl"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all text-xl"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all text-xl"><i class="bi bi-youtube"></i></a>
                    </div>
				</div>

				<!-- Useful Links -->
				<div>
					<h5 class="text-sm font-black uppercase tracking-[0.2em] mb-8 text-white">Explore Store</h5>
					<ul class="space-y-4 p-0 m-0 list-none">
						<li><a href="{{ route('home') }}" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Home Page</a></li>
						<li><a href="{{ route('books.index') }}" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">All Products</a></li>
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Featured Tech</a></li>
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Latest Deals</a></li>
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Gift Cards</a></li>
					</ul>
				</div>

				<!-- Support Links -->
				<div>
					<h5 class="text-sm font-black uppercase tracking-[0.2em] mb-8 text-white">Customer Support</h5>
					<ul class="space-y-4 p-0 m-0 list-none">
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Track My Order</a></li>
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Returns & Refunds</a></li>
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Shipping Policy</a></li>
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Privacy Policy</a></li>
						<li><a href="#" class="text-slate-400 hover:text-primary no-underline text-sm font-bold transition-colors">Contact Us</a></li>
					</ul>
				</div>

				<!-- Contact Info -->
				<div>
					<h5 class="text-sm font-black uppercase tracking-[0.2em] mb-8 text-white">Get In Touch</h5>
					<div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <i class="bi bi-geo-alt text-primary text-xl"></i>
                            <p class="text-slate-400 text-sm font-bold leading-tight">123 Tech Avenue, Silicon Valley,<br>California, USA</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="bi bi-telephone text-primary text-xl"></i>
                            <p class="text-slate-400 text-sm font-bold">+1 (555) 123-4567</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="bi bi-envelope text-primary text-xl"></i>
                            <p class="text-slate-400 text-sm font-bold">support@onemall.tech</p>
                        </div>
                    </div>
				</div>
			</div>

			<!-- Footer Bottom -->
			<div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
				<p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">
                    © 2026 ONEMALL. ALL RIGHTS RESERVED. DESIGNED FOR THE FUTURE.
                </p>
                <div class="flex items-center gap-4 opacity-50">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6" alt="Mastercard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4" alt="PayPal">
                </div>
			</div>
		</div>
	</footer>

	<script src="{{ asset('frontend') }}/js/jquery-1.11.0.min.js"></script>
	<script src="{{ asset('frontend') }}/js/plugins.js"></script>
	<script src="{{ asset('frontend') }}/js/script.js"></script>

</body>
</html>