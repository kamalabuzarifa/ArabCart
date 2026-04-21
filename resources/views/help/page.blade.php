@extends('layouts.main')

@section('title', $title . ' | ' . config('app.name', 'ArabCart'))
@section('nav-active', 'help')

@push('page-styles')
    <style>
        .help-glow {
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.35), transparent 38%), linear-gradient(135deg, rgba(0, 51, 69, 0.96), rgba(0, 119, 153, 0.92));
        }

        .help-highlight-card {
            position: relative;
            overflow: hidden;
            transition: transform 220ms ease, box-shadow 220ms ease, border-color 220ms ease;
            will-change: transform;
        }

        .help-highlight-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18), transparent 45%);
            opacity: 0;
            transition: opacity 220ms ease;
            pointer-events: none;
        }

        .help-highlight-card:hover,
        .help-highlight-card:focus-visible {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.14);
            border-color: rgba(0, 119, 153, 0.28);
        }

        .help-highlight-card:hover::after,
        .help-highlight-card:focus-visible::after {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <section class="page-container">
        <div class="relative overflow-hidden rounded-[2rem] help-glow text-white p-8 md:p-12 shadow-2xl">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-20 w-80 h-80 rounded-full bg-secondary/20 blur-3xl"></div>
            </div>
            <div class="relative z-10 max-w-3xl text-right">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-bold backdrop-blur-sm">
                    <span class="material-symbols-outlined text-base">{{ $icon }}</span>
                    <span>{{ $eyebrow }}</span>
                </span>
                <h1 class="mt-6 text-4xl md:text-6xl font-black leading-tight font-headline">{{ $title }}</h1>
                <p class="mt-5 text-base md:text-lg text-white/90 max-w-2xl">{{ $description }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
            @foreach ($highlights as $highlight)
                <div class="help-highlight-card rounded-2xl bg-white p-6 shadow-sm border border-outline-variant/20 text-right cursor-pointer">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-primary/10 text-primary mb-4">
                        <span class="material-symbols-outlined">{{ $highlight['icon'] }}</span>
                    </div>
                    <h2 class="text-lg font-bold text-primary mb-2">{{ $highlight['title'] }}</h2>
                    <p class="text-sm text-on-surface-variant leading-7">{{ $highlight['text'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <div class="rounded-2xl bg-surface-container-low p-6 md:p-8 shadow-sm border border-outline-variant/15 text-right">
                <h2 class="text-2xl font-black text-primary mb-6">{{ $sectionTitle }}</h2>
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <div class="rounded-2xl bg-white p-5 border border-outline-variant/15">
                            <h3 class="text-lg font-bold text-on-surface mb-2">{{ $item['title'] }}</h3>
                            <p class="text-sm leading-7 text-on-surface-variant">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl bg-primary text-white p-6 md:p-8 shadow-lg text-right flex flex-col justify-start items-center gap-6">
                <div class="grid grid-cols-1 gap-4 w-full text-center">
                    <h2 class="text-2xl font-black mb-4">{{ $ctaTitle }}</h2>
                    <p class="text-white/85 leading-7">{{ $ctaText }}</p>
                </div>
                <div class="grid grid-cols-1 gap-5 mt-1 w-full max-w-md">
                    @foreach ($contacts as $contact)
                        <a class="rounded-2xl bg-white/10 hover:bg-white/15 transition-colors px-4 py-3 border border-white/10 w-full" href="{{ $contact['href'] }}">
                            <div class="flex items-center gap-2.5">
                                <span class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-sm">{{ $contact['icon'] }}</span>
                                </span>
                                <div>
                                    <div class="text-sm text-white/75">{{ $contact['label'] }}</div>
                                    <div class="font-bold" dir="ltr">{{ $contact['value'] }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
