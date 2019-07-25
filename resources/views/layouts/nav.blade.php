<div class="row">
    <div class="col-2 bg-secondary list-group pr-0 pl-3">
        <a class="text-dark list-group-item list-group-item-action bg-secondary border-0 disabled" href="#">Menu</a>
        <hr class="border border-dark m-0">
        @foreach ($roles as $role)
            <a class="text-dark list-group-item list-group-item-action bg-secondary border-0" href="{{"/" . $username . "/" . $role->url }}">{{$role->name}}</a>
        @endforeach
    </div>
<div class="col-10">

