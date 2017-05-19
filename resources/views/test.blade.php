<!DOCTYPE html>
<html>
  <head>
    <title>Tyler Cowen's Ethnic Food Guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="/css/test.css">

  </head>

  <body>


  <div class="container-fluid">

    <div class="row">
      <header class="col-md-12">
        <p>Tyler Cownen's Ethnic Dining Guide</p>
        <p class="small">An Asian Market</p>
      </header>
    </div>

    <div class="row content">

      <aside class="col-md-2">
        <span class="fa fa-arrow-circle-o-right slide-out-arrow"></span>
        <h3>Cusines</h3>
        @foreach ($cuisines as $key=>$value)
            <p>{{ $value }}</p>
        @endforeach
        <h3>Location</h3>
      </aside>
      <main class="col-md-10">
        <h1>Restaurants</h1>
        @foreach ($restaurants as $key=>$restaurant)
            <p>{{ $restaurant->name }}</p>
            <img src="https://maps.googleapis.com/maps/api/streetview?size=600x300&location={{ $restaurant->latitude }},{{ $restaurant->longitude }}&key=AIzaSyD3O_iIzWezFJlYFBYkM5x4SfwP1Gt5HbQ">

            https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=APIKEY&location=38.9022568,-77.002219&keyword=Indigo&radius=400

            https://maps.googleapis.com/maps/api/place/photo?key=APIKEY&photoreference=CmRYAAAAu_mn764-d5k9KYp_efZH5ixGZF2hAfaeA0bE_fG_IigMnpIi3DjNh3ohxid4pPINoWlUzlrZN_ry7f7X5z7BRY057tl3EUR3FMWEG3bYWrWwAElFTleJFmZ1Gy0x_0JEEhBNnY74wHXjXJBJp8PfuB8HGhSQzMxVaw1g_Ucqqb-QqQIZVAzfYQ&maxheight=1600
            <hr>



        @endforeach

      </main>

    </div>

  </div>
  </body>


<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<script src="/js/test-scripts.js"></script>
</html>