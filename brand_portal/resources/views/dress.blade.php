<!-- resources/views/platform/dress.blade.php -->

<body>
<style>
    .selected {
        border: 2px solid #4287F5;
    }
</style>
<section class="bg-white px-5 py-5 rounded shadow-sm">
    <div class="container">
        <div class="row gx-3">
            <aside class="col-lg-6">
                <div class="rounded-4 mx-4 mb-4 d-flex justify-content-center">
                    @if(count($dress->images) > 0)
                        <a class="rounded-4" target="_blank" data-type="image"
                           style="display: flex; align-items: center; height: 50vh; overflow: hidden;"
                           href="{{ $dress->images[0] }}">
                            <img id="main-image" style="max-width: 100%; max-height: 50vh; margin: auto;"
                                 class="rounded-4 fit"
                                 src="{{ $dress->images[0] }}"
                                 onerror="this.onerror=null;this.src='/image/placeholder_no_image.png';"/>
                        </a>
                    @else
                        <img id="main-image" style="max-width: 100%; max-height: 50vh; margin: auto;"
                             class="rounded-4 fit"
                             src="/image/placeholder_no_image.png"/>
                    @endif
                </div>
                <hr/>
                <div class="d-flex justify-content-center mb-3" id="thumbnails">
                    @foreach($dress->images as $index => $image)
                        <a class="d-flex align-items-center justify-content-center mx-2 item-thumb" data-type="image">
                            <img style="max-width: 60px; max-height: 60px;" src="{{ $image }}"
                                 onerror="this.onerror=null;this.src='/image/placeholder_no_image.png';"
                                 class="rounded-2 {{ $index == 0 ? 'selected' : '' }}"/>
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

                        // Remove 'selected' class from all thumbnails
                        thumbnails.querySelectorAll('img').forEach(function (img) {
                            img.classList.remove('selected');
                        });

                        // Add 'selected' class to clicked thumbnail
                        clickedElement.classList.add('selected');
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

                    <hr/>

                    <pre class="bg-white"
                         style="font-size: 14px; white-space: pre-wrap; word-wrap: break-word; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';">{{$dress->description}}</pre>

                </div>
            </main>
        </div>
    </div>
</section>
</body>
