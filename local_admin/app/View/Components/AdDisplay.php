<?php

namespace App\View\Components;

use App\Models\Campaign;
use Illuminate\View\Component;

class AdDisplay extends Component
{
    public $url;
    public $id;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->url = Campaign::find($id)->image;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return <<<'blade'
<div>
<script>
var triggered = false;
function sendInternalRequestWithIdParam(id) {
    if (!triggered){
          // create a new XMLHttpRequest object
          var xhr = new XMLHttpRequest();

          // prepare the request URL with ID parameter
          var url = '/your-page-url?id=' + encodeURIComponent(id);  <!-- TODO Change link -->

          // open a new request with a GET method
          xhr.open('GET', url, true);

          // set a callback function for when the request is complete
          xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
              // request is complete and successful, do something with the response
              console.log(xhr.responseText);
            }
          };

          // send the request
          xhr.send();
          triggered = true;
    }

}
function checkIfImageIsVisible(image, callback) {
    if (image != null){
      var rect = image.getBoundingClientRect();
      var viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight);
      if (rect.bottom >= 0 && rect.top < viewHeight) {
        callback(image);
    }
  }
}

function handleVisibleImage(image) {
    if(!triggered){
      console.log('Image is now visible:', image.id);
      sendInternalRequestWithIdParam({{ $id }});
  }
}
window.addEventListener('scroll', function() {
  checkIfImageIsVisible(image, handleVisibleImage);
});
window.addEventListener("load", function(){
    image = document.getElementById({{ $id }});
    console.log(image == null);
    checkIfImageIsVisible(image, handleVisibleImage);
})
</script>
<a href=""> <!-- TODO Add link -->
<img id={{$id}} src="{{$url}}" alt="AnImage" width="600" height="600">
</a>
</div>
blade;
    }
}
