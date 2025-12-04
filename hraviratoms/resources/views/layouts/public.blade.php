{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="hy" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'LoveLeaf • Օնլայն հարսանեկան հրավիրատոմսեր')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Dancing+Script:wght@400;600&family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap"
        rel="stylesheet">

    {{-- Tailwind через CDN с конфигом LoveLeaf --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              leaf: {
                DEFAULT: '#5CA371',
                soft: '#A7D3B5',
                deep: '#447D56',
                bg: '#FAF8F4',
              },
              gold: {
                light: '#ECDDBB',
                DEFAULT: '#D9C79E',
                dark: '#A9966C',
              },
              love: {
                blush: '#F6ECE8',
              },
            },
            fontFamily: {
              logo: ['"Playfair Display"', 'serif'],
              display: ['"Cormorant Garamond"', 'serif'],
              body: ['Inter', 'system-ui', 'sans-serif'],
              script: ['"Dancing Script"', 'cursive'],
            },
          }
        }
      }
    </script>

    @stack('head')
</head>
<body class="h-full bg-leaf-bg text-slate-900 antialiased font-body" style="margin:0;">
    {{-- Прелоадер, чтобы не было “голого” HTML до загрузки стилей --}}
    <div
        id="preloader"
        style="
          position:fixed;
          inset:0;
          display:flex;
          align-items:center;
          justify-content:center;
          background:#FAF8F4;
          z-index:9999;
          font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
          font-size:13px;
          color:#64748b;
        "
    >
        Loading LoveLeaf...
    </div>

    {{-- Оболочка страницы, которая появится после полной загрузки --}}
    <div id="page-wrapper" style="opacity:0; transition: opacity .25s ease;">
        <div class="min-h-full flex flex-col">

            {{-- HEADER --}}
            @include('partials.header')

            {{-- КОНТЕНТ СТРАНИЦЫ --}}
            <main class="w-full flex-1 px-3 pb-10 pt-6 sm:px-6 sm:pt-10 md:mx-auto md:max-w-6xl">
                @yield('content')
            </main>

            {{-- FOOTER --}}
            @include('partials.footer')
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            var preloader = document.getElementById('preloader');
            var wrapper = document.getElementById('page-wrapper');

            if (preloader) preloader.style.display = 'none';
            if (wrapper) wrapper.style.opacity = '1';
        });
    </script>

    @stack('scripts')
</body>
</html>
