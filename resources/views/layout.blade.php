{{-- Hlavní rozložení stránky – ostatní šablony ho rozšiřují přes @extends('layout') --}}
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Evidence nářadí')</title>

    {{-- Jednoduché styly přímo v šabloně, bez nutnosti npm/Vite --}}
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; background: #f4f5f7; color: #222; }
        header { background: #1f2937; color: #fff; padding: 12px 24px; }
        header a { color: #fff; text-decoration: none; font-weight: bold; }
        main { max-width: 960px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .btn { display: inline-block; padding: 6px 12px; border-radius: 6px; border: 1px solid #d1d5db; background: #fff; color: #222; text-decoration: none; cursor: pointer; font-size: 14px; }
        .btn-primary { background: #2563eb; border-color: #2563eb; color: #fff; }
        .btn-danger { background: #dc2626; border-color: #dc2626; color: #fff; }
        .btn.selected { background: #1f2937; color: #fff; }
        form.inline { display: inline; }
        label { display: block; font-weight: 600; margin-top: 12px; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font: inherit; }
        .error { color: #dc2626; font-size: 13px; }
        .flash { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; }
        .right { text-align: right; }

        /* Barevné odlišení stavů – barva + text */
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 13px; font-weight: 600; }
        .badge-active  { background: #dcfce7; color: #166534; } /* zelená = aktivní */
        .badge-service { background: #fef3c7; color: #92400e; } /* oranžová = v servisu */
        .badge-retired { background: #e5e7eb; color: #374151; } /* šedá = vyřazeno */
    </style>
</head>
<body>
    <header>
        <a href="{{ route('equipment.index') }}">🔧 Evidence firemního nářadí</a>
    </header>

    <main>
        {{-- Hláška o úspěšné akci (uložená v session přes ->with('success', ...)) --}}
        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        {{-- Sem se vloží obsah konkrétní stránky --}}
        @yield('content')
    </main>
</body>
</html>
