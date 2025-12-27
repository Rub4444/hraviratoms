{{-- invitation/blocks/program.blade.php --}}

@if(($features['program'] ?? false) && !empty($invitation->data['program']))
<div class="border-t border-slate-200/70 px-6 py-5">
    <h2 class="mb-3 text-xs font-semibold tracking-[0.25em] uppercase text-slate-500">
        Օրվա ծրագիրը
    </h2>

    <ul class="space-y-2 text-sm">
        @foreach($invitation->data['program'] as $item)
            <li class="flex gap-3">
                <span class="w-16 text-xs font-semibold invite-accent">
                    {{ $item['time'] ?? '' }}
                </span>
                <span class="text-slate-700">
                    {{ $item['label'] ?? '' }}
                </span>
            </li>
        @endforeach
    </ul>
</div>
@endif
