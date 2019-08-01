<div class="row">
    <aside class="col-2 list-group pr-0 aside">
        <a class="text-dark list-group-item list-group-item-action border-0 disabled" href="#"><i class="fa fa-bars" aria-hidden="true"></i> Menu</a>
        <hr class="border border-dark m-0">
        @foreach ($roles as $role)
            <a class="text-dark list-group-item list-group-item-action border-0" href="{{"/" . $username . "/" . $role->url }}"><i class="{{$role->icon}}" aria-hidden="true"></i> {{$role->name}}</a>
        @endforeach
        <hr class="border border-dark m-0">
        <a class="text-dark list-group-item list-group-item-action border-0" href="{{ route('logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
    </aside>
<div class="col-10 content">

