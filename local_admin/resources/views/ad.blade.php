<a href="{{ $forward_url }}" target="_blank">
<div class="card">
    <div id = {{  $id  }} class="card__body">
            <img src="{{  $image_url  }}" alt="AnImage" width="600" height="600">
        <strong>{{  $title  }}</strong>
    </div>
    <span>
        {{  $title  }}
        <br>
        From: {{  $company  }}
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
                    .then((response) => {
                    console.log(response);
                }, (error) => {
                    console.log(error);
                });
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
        window.addEventListener("load", function () {
            image = document.getElementById({{  $id  }})
            console.log(image == null);
            checkIfImageIsVisible(image, handleVisibleImage);
        });
        window.addEventListener('scroll', function () {
            checkIfImageIsVisible(image, handleVisibleImage);
        });
        document.getElementById({{  $id  }}).addEventListener("click", function () {
            var url = 'https://api.promplanner.app/api/campaign_click/' + encodeURIComponent(id);
            console.log("Clicked ", id);
            axios.put(url)
                .then((response) => {
                    console.log(response);
                }, (error) => {
                    console.log(error);
                });
        })
    </script>
