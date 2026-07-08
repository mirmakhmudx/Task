@extends('layouts.app')

@section('content')
    <style>
        .page-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #2d2d4e 100%);
            color: #fff;
            border-radius: 18px;
            padding: 2.5rem 2.25rem;
        }
        .page-hero h1 { font-weight: 600; letter-spacing: -0.5px; margin: 0; }
        .page-hero p { color: #c7c9e0; margin: 0.65rem 0 0; font-size: 1.02rem; }
        .page-crumb { font-size: 0.82rem; }
        .page-crumb a { color: #6b7280; text-decoration: none; }
        .page-crumb a:hover { color: #1a1a2e; }
        .page-crumb .sep { color: #c7c9d6; margin: 0 .35rem; }

        .prose { color: #2b2b3d; font-size: 1.02rem; line-height: 1.75; }
        .prose > *:first-child { margin-top: 0; }
        .prose h2 { font-weight: 600; font-size: 1.4rem; margin: 1.8rem 0 .8rem; letter-spacing: -0.3px; }
        .prose h3 { font-weight: 600; font-size: 1.15rem; margin: 1.4rem 0 .6rem; }
        .prose p { margin: 0 0 1.1rem; }
        .prose a { color: #4338ca; text-decoration: none; border-bottom: 1px solid #c7d2fe; }
        .prose a:hover { border-bottom-color: #4338ca; }
        .prose ul, .prose ol { margin: 0 0 1.1rem; padding-left: 1.3rem; }
        .prose li { margin-bottom: .4rem; }
        .prose img { max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 14px rgba(0,0,0,.08); margin: 1rem 0; }
        .prose blockquote {
            border-left: 3px solid #1a1a2e; background: #f8f9fb;
            margin: 1.2rem 0; padding: .8rem 1.1rem; border-radius: 0 10px 10px 0; color: #4a4a6a;
        }
        .prose code { background: #f0f1f5; padding: .15rem .4rem; border-radius: 6px; font-size: .9em; }
        .prose pre { background: #1a1a2e; color: #f8f9fb; padding: 1rem 1.1rem; border-radius: 12px; overflow:auto; }
        .prose hr { border: none; border-top: 1px solid #e8eaf0; margin: 1.6rem 0; }

        .child-card { text-decoration: none; color: #1a1a2e; display:block; height:100%; transition: transform .12s, box-shadow .12s; }
        .child-card:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(0,0,0,.07); }
        .child-card .arrow { color:#9ca3af; }

        .side-link {
            display:flex; align-items:center; justify-content:space-between;
            padding:.6rem .9rem; border-radius:10px; text-decoration:none;
            color:#4a4a6a; font-weight:500; font-size:.92rem;
        }
        .side-link:hover { background:#f0f1f5; color:#1a1a2e; }
        .side-link.active { background:#1a1a2e; color:#fff; }
    </style>

    @php
        $rootPages   = \App\Entity\Page\Page::whereIsRoot()->defaultOrder()->get();
        $activeRoot  = explode('/', $page->getPath())[0];
        $children    = $page->children()->defaultOrder()->get();
    @endphp

    <nav class="page-crumb mb-3">
        <a href="{{ route('home') }}">Home</a>
        @foreach($page->getAncestors() as $ancestor)
            <span class="sep">/</span><a href="{{ url($ancestor->getPath()) }}">{{ $ancestor->menu_title }}</a>
        @endforeach
        <span class="sep">/</span><span class="text-muted">{{ $page->menu_title }}</span>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-hero mb-4">
                <h1>{{ $page->title }}</h1>
                @if(!empty($page->description))
                    <p>{{ $page->description }}</p>
                @endif
            </div>

            @if($children->count())
                <div class="row g-3 mb-4">
                    @foreach($children as $child)
                        <div class="col-sm-6">
                            <a href="{{ url($child->getPath()) }}" class="child-card card">
                                <div class="card-body d-flex justify-content-between align-items-center" style="padding:1.1rem 1.25rem;">
                                    <span class="fw-semibold">{{ $child->menu_title }}</span>
                                    <span class="arrow">&rarr;</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="prose">{!! $page->content !!}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3" style="position:sticky; top:1rem;">
                <div class="card-body" style="padding:1.25rem;">
                    <div class="text-muted text-uppercase small fw-semibold mb-2">Ma'lumot sahifalari</div>
                    @foreach($rootPages as $root)
                        <a href="{{ url($root->slug) }}"
                           class="side-link {{ $activeRoot === $root->slug ? 'active' : '' }}">
                            <span>{{ $root->menu_title }}</span>
                            @if($activeRoot === $root->slug)<span>&bull;</span>@endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center" style="padding:1.5rem;">
                    <div class="fw-semibold mb-1">Savolingiz bormi?</div>
                    <p class="text-muted small mb-3">Biz bilan bog'laning yoki murojaat qoldiring.</p>
                    <a href="{{ route('cabinet.tickets.create') }}" class="btn btn-primary btn-sm w-100">Murojaat yuborish</a>
                </div>
            </div>
        </div>
    </div>
@endsection
