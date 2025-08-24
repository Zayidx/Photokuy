<header class="pb-header" id="pb-header">
    <div class="pb-left">
        <a href="{{ route('photobooth.start') }}" class="pb-brand" aria-label="Go to start">📸 Photobooth</a>
        <span class="pb-sub">Capture, compose, share — instantly.</span>
    </div>
    <nav class="pb-nav">
        <a href="{{ route('photobooth.start') }}" class="pb-link {{ request()->routeIs('photobooth.start') ? 'active' : '' }}">Start</a>
        <a href="{{ route('photobooth.select') }}" class="pb-link {{ request()->routeIs('photobooth.select') ? 'active' : '' }}">Layouts</a>
        <button id="help-open" class="pb-link" style="background:transparent;border:none;cursor:pointer;">Help</button>
        <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme"><span class="icon">🌙</span><span class="label">Dark</span></button>
    </nav>
</header>
