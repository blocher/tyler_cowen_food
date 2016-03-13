<div class="list-group">
  @foreach ($restaurants as $restaurant)

    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">
          {{ $restaurant->name }}
            <div class="cuisine-badges pull-right">
              @foreach ($restaurant->terms()->cuisines()->get() as $cuisine)
                  <span class="badge">{{ $cuisine->title }}</span>
              @endforeach
            </div>
        </h3>
      </div>
      <div class="panel-body">
        {{ $restaurant->excerpt }}
      </div>
      <div class="panel-footer">
          <a href="https://www.google.com/maps/place/{{ $restaurant->formatted_address }}" target="_blank"><i class="fa fa-map-marker"></i>&nbsp;{{ $restaurant->formatted_address }}</a><span class="pull-right"><a href="{{ $restaurant->permalink }}" target="_blank"><i class="fa fa-external-link"></i></a></span>
      </div>
    </div>
  @endforeach


</div>