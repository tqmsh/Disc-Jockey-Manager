<?php

namespace App\View\Components;

use App\Models\Campaign;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdDisplay extends Component
{
    public $url;
    public $id;
    public $forward_url;
    public $campaign;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->campaign = Campaign::find($id);
        $this->url = ($this->campaign != null && $this->campaign->image != null) ? $this->campaign->image : 'https://via.placeholder.com/600x600';
        $this->forward_url = ($this->campaign != null && $this->campaign->website != null) ? $this->campaign->website : '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return <<<'blade'
<div>
<script>
var triggered = false;
function sendInternalRequestWithIdParam(id) {
    if (!triggered){
          // prepare the request URL with ID parameter
          var url = '/campaign_view/' + encodeURIComponent(id);

          $.post(url, "", (data, status) => { console.log(data);})
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
      sendInternalRequestWithIdParam({{ $id }})
  }
}
window.addEventListener('scroll', function() {
  checkIfImageIsVisible(image, handleVisibleImage);
});
window.addEventListener("load", function(){
    image = document.getElementById({{ $id }})
    console.log(image == null);
    checkIfImageIsVisible(image, handleVisibleImage);
})
document.getElementById({{  $id  }}).addEventListener("click", function(){
    var url = '/campaign_click/' + encodeURIComponent(id);

    $.post(url, "", (data, status) => { console.log(data);})
})
</script>
<a href=" {{ $forward_url }}">
<img id={{$id}} src="{{$url}}" alt="AnImage" width="600" height="600">
</a>
</div>
blade;
    }
}
