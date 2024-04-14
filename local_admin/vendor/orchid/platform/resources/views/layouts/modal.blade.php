@push('modals-container')
    <div class="modal fade center-scale"
         id="screen-modal-{{$key}}"
         role="dialog"
         aria-labelledby="screen-modal-{{$key}}"
         data-controller="modal"
         data-modal-slug="{{$templateSlug}}"
         data-modal-async-enable="{{$asyncEnable}}"
         data-modal-async-route="{{$asyncRoute}}"
         data-modal-open="{{$open}}"
        {{$staticBackdrop ? "data-bs-backdrop=static" : ''}}
    >
        <div class="modal-dialog modal-fullscreen-md-down {{$size}} {{$type}}" role="document" id="screen-modal-type-{{$key}}">
            <form class="modal-content"
                  id="screen-modal-form-{{$key}}"
                  method="post"
                  enctype="multipart/form-data"
                  data-controller="form"
                  data-action="form#submit"
                  data-form-button-animate="#submit-modal-{{$key}}"
                  data-form-button-text="{{ __('Loading...') }}"
            >
            <div class="modal-header">
                <div style="display: flex; gap:5px;">
                    <h4 class="modal-title text-black fw-light" data-modal-target="title">{{$title}}</h4>
                    @php
                        $video_tutorial_appropriate_title = str_replace(' ', '', strtolower($title));
                        $video_tutorial_appropriate_route = request()->route()->getName() . "_modal-{$video_tutorial_appropriate_title}";
                    @endphp
                    
                    @if(DB::table('video_tutorials')->where('portal', 0)->where('route_name', $video_tutorial_appropriate_route)->exists())
                        <a target="_blank" href="{{DB::table('video_tutorials')->where('portal', 0)->where('route_name', $video_tutorial_appropriate_route)->first()->url}}">
                            <sup style="position: relative; bottom:5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
                                </svg>
                            </sup>
                        </a>
                    @endif
                </div>
                <button type="button" class="btn-close" title="Close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
                <div class="modal-body layout-wrapper">
                    <div data-async>
                        @foreach($manyForms as $formKey => $modal)
                            @foreach($modal as $item)
                                {!! $item ?? '' !!}
                            @endforeach
                        @endforeach
                    </div>

                    @csrf
                </div>
                <div class="modal-footer">

                    @if(!$withoutCloseButton)
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">
                            {{ $close }}
                        </button>
                    @endif

                    @empty($commandBar)
                        @if(!$withoutApplyButton)
                            <button type="submit"
                                    id="submit-modal-{{$key}}"
                                    data-turbo="{{ var_export($turbo) }}"
                                    class="btn btn-default">
                                {{ $apply }}
                            </button>
                        @endif
                    @else
                        {!! $commandBar !!}
                    @endempty

                </div>
            </form>
        </div>
    </div>
@endpush
