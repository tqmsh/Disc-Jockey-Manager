<body>
    @if (now() < $election->end_date)
        <h5  class="layout v-md-center">
            This election ends on {{$election->end_date}}. Remember to vote!
        </h5>
    @else
        {{-- TODO CHANGE THE BUTTON, ITS UGLY RN --}}

        <header class="layout">
            <div class="row">
                <div class="col-sm">
                    <h5>
                        This election has ended.
                    </h5>
                </div>
                <div class="col-sm">
                    <a class="btn btn-primary rounded text-center icon-link" href="{{ route('platform.election.winners') }}">
                        View Winners
                    </a>

                </div>
            </div>
        </div>
    @endif
</body>