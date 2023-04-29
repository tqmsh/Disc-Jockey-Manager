<div>
    <script>
        var triggered = false;

        function sendInternalRequestWithIdParam(id) {
            if (!triggered) {
                // prepare the request URL with ID parameter
                var url = 'https://api.promplanner.app/campaign_view/' + encodeURIComponent(id);

                $.post(url, "", (data, status) => {
                    console.log(data);
                })
                triggered = true;
            }

        }

        function checkIfImageIsVisible(image, callback) {
            if (image != null) {
                var rect = image.getBoundingClientRect();
                var viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight);
                if (rect.bottom >= 0 && rect.top < viewHeight) {
                    callback(image);
                }
            }
        }

        function handleVisibleImage(image) {
            if (!triggered) {
                console.log('Image is now visible:', image.id);
                sendInternalRequestWithIdParam({{  $id  }})
            }
        }

        window.addEventListener('scroll', function () {
            checkIfImageIsVisible(image, handleVisibleImage);
        });
        window.addEventListener("load", function () {
            image = document.getElementById({{  $id  }})
            console.log(image == null);
            checkIfImageIsVisible(image, handleVisibleImage);
        })
        document.getElementById({{  $id  }}).addEventListener("click", function () {
            var url = 'https://api.promplanner.app/campaign_click/' + encodeURIComponent(id);

            $.post(url, "", (data, status) => {
                console.log(data);
            })
        })
    </script>
    <a href="{{ $forward_url }}">
        <img id={{  $id  }} src="{{  $image_url  }}" alt="AnImage" width="600" height="600">
    </a>
</div>
