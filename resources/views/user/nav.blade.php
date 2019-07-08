<div class="row">
    <div class="col-2">
        <ul>
            @foreach ($roles as $role)
                <li>{{ $role->name }}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-10">

