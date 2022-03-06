<h3>Вы изменили следующую информацию в записи вашего пользователя:</h3>
<ul>
    @if($userChanged)
        <li>Поля в анкете пользователя:</li>
        <ul>
            @foreach($titles as $title)
                <li>{!! $title !!}</li>
            @endforeach
        </ul>
    @endif

    @if($rolesChanged)
        <li>Привязку ролей к пользователю.</li>
    @endif

    @if($clientsChanged)
        <li>Доступность отдельных клиентов пользователю.</li>
    @endif
</ul>

<p style="margin-top: 40px;">
    С уважением,<br/>
    <a href="{{ env('BRAND_URL') }}" target="_blank">{{ env('BRAND_NAME') }}</a>
</p>
