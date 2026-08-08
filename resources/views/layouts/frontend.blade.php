@vite('resources/css/app.css')
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '我的網站')</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-base-200">
    {{-- 共用導覽列 --}}
    <nav class="navbar bg-base-100 shadow-sm">
        <div class="navbar-start">
            <a href="/" class="btn btn-ghost text-xl">My App</a>
        </div>
    </nav>

    {{-- 子 view 內容填進來 --}}
    <main class="p-6">
        @yield('content')
    </main>
</body>
</html>
