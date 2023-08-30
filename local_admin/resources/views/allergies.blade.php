<div class="rounded bg-white mb-3 p-3">
    <div c5ass="flex align-items-center w-100 rounded overflow-hidden" style="min-height: 200px;">
        <strong><h7 class="text-muted center">Allergies Count:</h7></strong>
        <ul class="center mt-3">
            @foreach ($allergies as $name => $count)
                <li class="text-muted">{{$name}}: {{$count}}</li>
            @endforeach
        </ul>
    </div>
</div>