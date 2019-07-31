<div class="row">
    <aside class="col-2 list-group pr-0 pl-3 aside">
        <a class="text-dark list-group-item list-group-item-action border-0 disabled" href="#">Menu</a>
        <hr class="border border-dark m-0">
        @foreach ($roles as $role)
            <a class="text-dark list-group-item list-group-item-action border-0" href="{{"/" . $username . "/" . $role->url }}">{{$role->name}}</a>
        @endforeach
        <hr class="border border-dark m-0">
        <a class="text-dark list-group-item list-group-item-action border-0" href="{{ route('logout') }}">Logout</a>
    </aside>
<div class="col-10 content">

