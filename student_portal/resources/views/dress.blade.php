<!-- resources/views/platform/dress.blade.php -->

<body>
@if($dress)
<section class="bg-white px-5 py-5 rounded shadow-sm">
    <div class="container">
        <div class="row gx-3">
            <aside class="col-lg-6">
                <div class="rounded-4 mb-3 d-flex justify-content-center">
                    @if(count($dress->images) > 0)
                    <a data-fslightbox="mygalley" class="border rounded-4" target="_blank" data-type="image"
                       href="{{ $dress->images[0] }}">
                        <img id="main-image" style="max-width: 100%; max-height: 50vh; margin: auto;"
                             class="border rounded-4 fit"
                             src="{{ $dress->images[0] }}"/>
                    </a>
                    @else
                    <img id="main-image" style="max-width: 100%; max-height: 50vh; margin: auto;"
                         class="border rounded-4 fit"
                         src="/image/placeholder_dress.webp"/>
                    @endif
                </div>
                <div class="d-flex justify-content-center mb-3" id="thumbnails">
                    @foreach($dress->images as $image)
                    <a data-fslightbox="mygalley" class="border mx-1 rounded-2" data-type="image"
                       class="item-thumb">
                        <img class="rounded-2" style="max-width: 60px; max-height: 60px;" src="{{ $image }}"/>
                    </a>
                    @endforeach
                </div>
            </aside>
            <script>
                document.body.addEventListener('click', function (e) {
                    var thumbnails = document.getElementById('thumbnails');
                    var clickedElement = e.target;
                    var isWithinThumbnails = thumbnails.contains(clickedElement);

                    if (isWithinThumbnails && clickedElement.tagName.toLowerCase() === 'img') {
                        document.getElementById('main-image').src = clickedElement.src;
                    }
                });
            </script>

            <main class="col-lg-6">
                <div class="ps-lg-3">
                    <h4 class="title text-dark">{{$dress->model_name}}</h4>

                    <div class="row mb-2">
                        <dt class="col-3">Brand</dt>
                        <dd class="col-9">{{$dress->vendor->company_name}}</dd>

                        <dt class="col-3">Model Number</dt>
                        <dd class="col-9">{{$dress->model_number}}</dd>

                        @if(!empty($dress->colours))
                        <dt class="col-3">Color(s)</dt>
                        <dd class="col-9">{{implode(', ', $dress->colours)}}</dd>
                        @endif

                        @if(!empty($dress->sizes))
                        <dt class="col-3">Size(s)</dt>
                        <dd class="col-9">{{implode(', ', $dress->sizes)}}</dd>
                        @endif
                    </div>

                </div>
            </main>
        </div>
    </div>
</section>
@else
<section class="bg-white px-1 py-4 rounded shadow-sm">
    <div class="container">
        <h1 class="display-6" style="font-size: 2rem">You haven't claimed a dress yet</h1>
    </div>
</section>
@endif
</body>
