{{-- resources/views/partials/header.blade.php --}}
<header class="border-b border-slate-200/70 bg-white/80 backdrop-blur">
    <div class="flex items-center justify-between px-3 py-2 sm:px-6 sm:py-3 md:mx-auto md:max-w-6xl">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-leaf text-white text-[11px] sm:text-xs font-logo">
                LL
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px] sm:text-sm font-logo tracking-wide">
                    LoveLeaf
                </span>
                <span class="text-[10px] sm:text-[11px] text-slate-500">
                    Online Wedding Invites
                </span>
            </div>
        </div>

        {{-- Desktop-меню --}}
        <nav class="hidden gap-5 text-[11px] text-slate-600 sm:flex">
            <a href="#how" class="hover:text-leaf-deep">Ինչպես է աշխատում</a>
            <a href="#templates" class="hover:text-leaf-deep">Դիզայններ</a>
            <a href="#pricing" class="hover:text-leaf-deep">Գնացուցակ</a>
            <a href="#faq" class="hover:text-leaf-deep">FAQ</a>
            <a href="#pricing" class="hover:text-leaf-deep">Կապ</a>
        </nav>

        {{-- Кнопка входа / кабинета --}}
        <div class="flex items-center">
            @guest
                <a
                    href="/login"
                    class="inline-flex items-center justify-center rounded-full bg-leaf px-3 py-1.5 text-[11px] sm:px-4 sm:text-xs font-medium text-white shadow-sm shadow-leaf/40 hover:bg-leaf-deep"
                >
                    Մուտք LoveLeaf
                </a>
            @endguest

            @auth
                @if(auth()->user()->is_superadmin)
                    <a href="/admin" class="btn-admin text-[11px] sm:text-xs">
                        Մուտք LoveLeaf Admin (Superadmin)
                    </a>
                @else
                    <a href="/admin/invitations" class="btn-admin text-[11px] sm:text-xs">
                        Մուտք LoveLeaf Invitation
                    </a>
                @endif
            @endauth
        </div>
    </div>
</header>
