<style>
  .zoom {
    transition: transform .2s; /* Animation */
    margin: 0 auto;
    cursor: pointer;
  }
  .zoom:hover {
    transform: scale(1.1); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  }
</style>
  
<div class="d-flex mt-3 flex-wrap" style="justify-content: center">
    @foreach ($items as $item)
    <div class="card m-4 overflow-hidden" style="width: 20rem;">
      <img onclick="window.location.href='{{route('platform.eventFoodSingle.list', ['event_id' => $item->event_id, 'food_id' => $item->id])}}'" src="{{$item->image}}" class="card-img-top zoom" alt="...">
      <div class="card-body">
        <h5 class="card-title">{{$item->name}}</h5>
        <p class="card-text">{{$item->description}}</p>
        <button onclick="window.location.href='{{route('platform.eventFoodSingle.list', ['event_id' => $item->event_id, 'food_id' => $item->id])}}'" class="btn btn-primary btn-rounded">Details</button>
      </div>
    </div>
    @endforeach
</div>

