@push('head')
    <meta name="robots" content="noindex" />
    <link
          href="{{ asset('/vendor/orchid/favicon.ico') }}"
          sizes="any"
          type="image/svg+xml"
          id="favicon"
          rel="icon"
    >

    <!-- For Safari on iOS -->
    <meta name="theme-color" content="#21252a">
@endpush

<div class="h2 fw-light d-flex align-items-center">
    <span class="thumb-sm">
        <img src="{{ URL::asset('/image/Prom_VP_Line.png') }}"  style="height: 29.59px; width:40px;"> </span>
    <p class="ms-3 my-0 d-none d-sm-block">
        Disc Jockey Manager
    </p>
</div>


@stack('scripts')


@push('scripts')

    <style>
        .shepherd-content {
            background: #FAF9F6;
        }
        .shepherd.tippy-popper[x-placement^="left"] .tippy-arrow{
            border-left-color: #FAF9F6;
        }

        .shepherd-button{
            color: #ffffff;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/shepherd.js@5.0.1/dist/js/shepherd.js"></script>
    <script type='text/javascript'>
        var tour = new Shepherd.Tour({
            defaultStepOptions: {
                classes: 'shadow-md bg-purple-dark', // a separate CSS file
                scrollTo: true
            }
        });
    </script>

    @php
        $tourElements = \App\Models\TourElement::where("portal", 0)->where('screen', str_replace('/{method?}', '', Illuminate\Support\Facades\Route::current()->uri()))->orderBy('order_element', 'asc')->get()->toArray();
        $arr_tourels = [];
        foreach ($tourElements as $tel){
            array_push($arr_tourels, $tel);
        }

        $i = -1;
    @endphp
    @foreach ($arr_tourels as $el)
        <script type='text/javascript'>
            var linkToAdd = window.location.href.split('admin/')[0]
            tour.addStep({
                id: '{{$el['order_element']}}',
                title: '{{$el['title']}}',
                text: '{{$el['description']}}',
                @if ($el['element'] == "a[href" or $el['element'] == "button[formaction")
                attachTo: { element: '{{$el['element'].'='}}'+'"'+linkToAdd + '{{$el['extra']}}'+'"'+']', on: 'bottom' },
                @else
                attachTo: { element: '{{$el['element']}}', on: 'bottom' },
                @endif
                buttons: [
                        @php
                            $i++;
                        @endphp
                        @if($i == 0)
                    { text: 'Next', action: tour.next },
                    { text: 'End', action: tour.cancel },
                        @elseif($i==count($arr_tourels)-1)
                    { text: 'Back', action: tour.back },
                    { text: 'End', action: tour.cancel },
                        @else
                    { text: 'Next', action: tour.next },
                    { text: 'Back', action: tour.back },
                    { text: 'End', action: tour.cancel },
                    @endif

                ]
            });

        </script>
    @endforeach

@endpush

