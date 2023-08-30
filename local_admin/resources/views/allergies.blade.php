<div class="rounded bg-white mb-3 p-3">
    <div c5ass="flex align-items-center w-100 rounded overflow-hidden" style="min-height: 200px;">
        <h6 class="text-muted center mb-3 font-semibold">Allergies Count</h6>
        <ul class="center">
            @foreach ($allergies as $name => $count)
                <li class="text-muted">{{$name}}: {{$count}}</li>
            @endforeach
        </ul>
    </div>
</div>