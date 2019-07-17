<div class="row">
    <div class="col-2 bg-primary">
        <ul>
            @foreach ($roles as $role)
                <li><a class="text-dark" href="{{"/" . $username . "/" . $role->url }}">{{$role->name}}</a></li>
            @endforeach
        </ul>
    </div>
<div class="col-10">

