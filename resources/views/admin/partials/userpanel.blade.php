<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="{{ asset('assets/admin/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
             alt="User Image">
    </div>
    <div class="info">
        <a href="javascript:void(0);" class="d-block">{{ Illuminate\Support\Facades\Auth::user()->name }}</a>
        @php
            $roles = \Illuminate\Support\Facades\Auth::user()->getRoleNames()->join(",<br/>");
        @endphp
        <p class="role-name">{!! $roles !!}</p>
        <a href="{{ route('logout') }}" class="d-block">
            <i class="fas fa-sign-out-alt"></i>
            Выход
        </a>
    </div>
</div>
