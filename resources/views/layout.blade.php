<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evidence nářadí</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            font-size: 15px;
            color: #1e293b;
            background: #f1f5f9;
        }

        a { color: #2563eb; text-decoration: none; }

        /* horni lista */
        .header { background: #fff; border-bottom: 1px solid #e2e8f0; }
        .header-inner { max-width: 1100px; margin: 0 auto; padding: 16px 20px; display: flex; align-items: center; gap: 10px; }
        .logo { width: 34px; height: 34px; border-radius: 8px; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .header a.title { color: #0f172a; font-weight: 700; font-size: 18px; }

        .container { max-width: 1100px; margin: 0 auto; padding: 30px 20px; }

        .page-head { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; margin-bottom: 20px; }
        .page-head h1 { margin: 0; font-size: 26px; }
        .muted { color: #64748b; font-size: 14px; margin: 4px 0 0; }

        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
        .card h2 { margin: 0 0 15px; font-size: 18px; }

        /* tlacitka */
        .btn { display: inline-block; padding: 9px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #1e293b; font: inherit; font-weight: 500; font-size: 14px; cursor: pointer; }
        .btn:hover { background: #f8fafc; }
        .btn-primary { background: #2563eb; border-color: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger { background: #fff; border-color: #fecaca; color: #dc2626; }
        .btn-danger:hover { background: #fef2f2; }
        .btn-small { padding: 5px 10px; font-size: 13px; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }

        /* filtr */
        .filter { display: inline-flex; background: #e2e8f0; padding: 4px; border-radius: 10px; margin-bottom: 18px; flex-wrap: wrap; }
        .filter a { padding: 7px 14px; border-radius: 7px; color: #475569; font-weight: 500; font-size: 14px; }
        .filter a.active-filter { background: #fff; color: #0f172a; box-shadow: 0 1px 2px rgba(0,0,0,.1); }

        /* tabulka */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: #64748b; font-weight: 600; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 13px 12px; border-bottom: 1px solid #f1f5f9; }
        tr:hover td { background: #f8fafc; }
        .right { text-align: right; white-space: nowrap; }
        .code { font-family: monospace; background: #f1f5f9; padding: 2px 7px; border-radius: 5px; font-size: 13px; }
        .empty { text-align: center; color: #94a3b8; padding: 30px; }

        /* barevne stitky stavu */
        .status { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .status::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .status-active { background: #dcfce7; color: #15803d; }
        .status-service { background: #fef3c7; color: #b45309; }
        .status-retired { background: #f1f5f9; color: #64748b; }

        /* formulare */
        label { display: block; font-weight: 500; font-size: 14px; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; margin-bottom: 14px; background: #fff; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px #dbeafe; }
        .error { color: #dc2626; font-size: 13px; margin: -10px 0 12px; }
        .form-card { max-width: 560px; }

        .success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }

        /* dlazdice na detailu */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 18px; }
        .stat-label { display: block; color: #64748b; font-size: 13px; margin-bottom: 6px; }
        .stat strong { font-size: 18px; }

        /* detail - historie vlevo, formular vpravo */
        .two-cols { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start; }
        .two-cols .card { margin-bottom: 0; }
        @media (max-width: 800px) { .two-cols { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-inner">
            <div class="logo">EN</div>
            <a class="title" href="{{ route('equipment.index') }}">Evidence nářadí</a>
        </div>
    </div>

    <div class="container">
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
