<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evidence nářadí</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f2f2f2; }
        .header { background: #333; padding: 10px 20px; }
        .header a { color: white; text-decoration: none; font-size: 20px; }
        .container { max-width: 900px; margin: 20px auto; background: white; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border-bottom: 1px solid #ddd; padding: 6px; text-align: left; }
        input, select, textarea { width: 100%; padding: 5px; margin-bottom: 10px; box-sizing: border-box; }
        .error { color: red; }
        .success { background: #d4edda; padding: 10px; margin-bottom: 15px; }
        .filter a { margin-right: 10px; }
        .filter a.active-filter { font-weight: bold; }

        /* barvy stavu */
        .status { padding: 2px 8px; border-radius: 4px; color: white; }
        .status-active { background: green; }
        .status-service { background: orange; }
        .status-retired { background: gray; }
    </style>
</head>
<body>
    <div class="header">
        <a href="{{ route('equipment.index') }}">Evidence nářadí</a>
    </div>

    <div class="container">
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
