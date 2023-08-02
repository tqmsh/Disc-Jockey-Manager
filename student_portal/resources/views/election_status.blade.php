<body>
    @if (now() < $election->end_date)
        <h5  class="layout v-md-center">
            This election ends on {{$election->end_date}}. Remember to vote!
        </h5>
    @else
        {{-- TODO ADD LINK TO WINNERS HERE --}}
        <h5 class="layout v-md-center">
            This election has ended. See winners here _____
        </h5>
    @endif
    
</body>