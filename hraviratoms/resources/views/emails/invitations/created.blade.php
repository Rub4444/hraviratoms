@component('mail::message')
# LoveLeaf • Նոր հրավիրատոմս

@if($isPublicRequest)
Նոր հայտ է ընդունվել օնլայն հրավիրատոմսի ստեղծման համար։
@else
Նոր հրավիրատոմս է ստեղծվել ադմին-պանելից։
@endif

**Հարսնացու:** {{ $invitation->bride_name ?? '—' }}
**Փեսա:** {{ $invitation->groom_name ?? '—' }}

@if($invitation->date)
**Ամսաթիվ:** {{ $invitation->date }}
@endif

@if($invitation->time)
**Ժամ:** {{ $invitation->time }}
@endif

@if($invitation->venue_name)
**Վայր:** {{ $invitation->venue_name }}
@endif

---

@if(is_array($invitation->data))
@php($data = $invitation->data)
@endif

@if(!empty($data['client_name']) || !empty($data['client_email']) || !empty($data['client_phone']))
**Հաճախորդի տվյալներ**

@if(!empty($data['client_name']))
- Անուն՝ {{ $data['client_name'] }}
@endif
@if(!empty($data['client_email']))
- Email՝ {{ $data['client_email'] }}
@endif
@if(!empty($data['client_phone']))
- Հեռախոս՝ {{ $data['client_phone'] }}
@endif
@if(!empty($data['client_notes']))
- Նշումներ՝ {{ $data['client_notes'] }}
@endif

@endif

@component('mail::button', ['url' => url('/admin/#/invitations')])
Բացել հրավիրատոմսերի ցուցակը
@endcomponent

Շնորհակալություն,<br>
LoveLeaf
@endcomponent
