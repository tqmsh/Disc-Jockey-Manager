<div class="d-flex mt-3 flex-wrap" style="justify-content: center">
    @foreach ($items as $item)
    <div class="card m-4" style="width: 20rem;">
      <img src="{{$item->image}}" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">{{$item->name}}</h5>
        <p class="card-text">{{$item->description}}</p>
        <button href="#" class="btn btn-primary btn-rounded">Details</button>
      </div>
    </div>
    @endforeach
</div>