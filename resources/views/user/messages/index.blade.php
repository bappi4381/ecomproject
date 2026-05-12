@extends('user.layout')

@section('title', 'Messages')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Messages</h2>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Connect with our support and management team.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden bg-white min-h-[500px] flex flex-col items-center justify-center p-12 text-center relative">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>

        <div class="relative z-10 space-y-8">
            <div class="w-32 h-32 bg-slate-50 text-slate-200 rounded-[50px] flex items-center justify-center text-6xl mx-auto shadow-inner relative">
                <i class="bi bi-chat-dots"></i>
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-primary rounded-2xl flex items-center justify-center text-white text-lg shadow-lg animate-bounce">
                    <i class="bi bi-plus-lg"></i>
                </div>
            </div>
            
            <div class="space-y-3">
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">No Conversations Yet</h3>
                <p class="text-sm text-slate-400 font-medium max-w-sm mx-auto">This feature is currently under development. Soon you'll be able to chat directly with our experts.</p>
            </div>

            <div class="pt-8">
                <button class="px-10 py-4 bg-primary text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                    Notify Me When Ready
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
