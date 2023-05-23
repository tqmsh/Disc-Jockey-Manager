{{--<a href="{{ $forward_url }}" target="_blank">--}}
<div class="card" id = {{  $id  }}>
    <div class="card__body">
            <img src="{{  $image_url  }}" alt="AnImage" width="600" height="600">
        <strong>{{  $title  }}</strong>
    </div>
    <span>
        {{  $title  }}
        <br>
        From: {{  $company  }}
        <br>
        Category: {{  $category  }}
    </span>
</div>
</a>

<script>
        var triggered = false;

        function sendInternalRequestWithIdParam(id) {
            if (!triggered) {
                // prepare the request URL with ID parameter
                var url = 'https://api.promplanner.app/api/campaign_view/' + encodeURIComponent(id);

                axios.put(url)
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
                sendInternalRequestWithIdParam({{  $id  }})
            }
        }
        window.addEventListener("load", function () {
            image = document.getElementById({{  $id  }})
            checkIfImageIsVisible(image, handleVisibleImage);
        });
        window.addEventListener('scroll', function () {
            checkIfImageIsVisible(image, handleVisibleImage);
        });
        document.getElementById({{  $id  }}).addEventListener("click", function () {
            var url = 'https://api.promplanner.app/api/campaign_click/' + encodeURIComponent({{  $id  }});
            axios.put(url)
        })
    </script>
