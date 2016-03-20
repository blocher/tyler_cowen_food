
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Tyler Cowen Revisited</title>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

     <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">

     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />


    <!-- Custom styles for this template -->
    <link href="css/theme.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body role="document">

<?php
    // <!-- Fixed navbar -->
    // <nav class="navbar navbar-inverse navbar-fixed-top">
    //   <div class="container">
    //     <div class="navbar-header">
    //       <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
    //         <span class="sr-only">Toggle navigation</span>
    //         <span class="icon-bar"></span>
    //         <span class="icon-bar"></span>
    //         <span class="icon-bar"></span>
    //       </button>
    //       <a class="navbar-brand" href="#">Bootstrap theme</a>
    //     </div>
    //     <div id="navbar" class="navbar-collapse collapse">
    //       <ul class="nav navbar-nav">
    //         <li class="active"><a href="#">Home</a></li>
    //         <li><a href="#about">About</a></li>
    //         <li><a href="#contact">Contact</a></li>
    //         <li class="dropdown">
    //           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
    //           <ul class="dropdown-menu">
    //             <li><a href="#">Action</a></li>
    //             <li><a href="#">Another action</a></li>
    //             <li><a href="#">Something else here</a></li>
    //             <li role="separator" class="divider"></li>
    //             <li class="dropdown-header">Nav header</li>
    //             <li><a href="#">Separated link</a></li>
    //             <li><a href="#">One more separated link</a></li>
    //           </ul>
    //         </li>
    //       </ul>
    //     </div><!--/.nav-collapse -->
    //   </div>
    // </nav>
?>

    <div class="container theme-showcase" role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Tyler Cowen Revisited</h1>
        <p>This is ripping of Tyler's excellent reviews, but making them marginally more searchable.</p>
      </div>



        <div class="col-md-9">
          <!-- <h3 class='current-cuisines-label'>All cuisines</h3> -->
          <div class = "restaurant-group">
          </div>
        </div>
        {{-- Sidebar --}}
        <div class="col-md-3">


          {!! Form::open() !!}
            <h3>Sorting</h3>
            <div class="sorting">
              <div class="form-group">
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-warning active">Alphabetically</button>
                  </div>
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">By Distance</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Enter your address or click to determine current location</h4>
                  </div>
                  <div class="modal-body">
                    ...
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
              </div>
            </div>

            <h3>Filter by Cuisine</h3>
            <div class="cuisine-filters">


                <div class="form-group">
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-success toggle-cuisines toggle-cuisines-on">Select All</button>
                  </div>
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-danger toggle-cuisines toggle-cuisines-off">Clear</button>
                  </div>
                </div>
              </div>

              <div class="form-group">

              {{ Form::select('cuisine-filter-select[]', $cuisines, array_keys($cuisines->toArray()), ['id'=>'cuisine-filter-select', 'class'=>'form-control', 'multiple'=>true]) }}
              </div>

{{--                @foreach ($cuisines as $cuisine)
                <div class="form-group">
                  <input checked data-toggle="toggle" data-on="<i class='fa fa-check-circle'></i> {{ $cuisine->title }}" data-off="<i class='fa fa-minus-circle'></i> {{ $cuisine->title }}" data-width="100%" data-size="small" type="checkbox" class="cuisine-filter-button" name="cuisine-filter[]" value="{{ $cuisine->id }}">
                </div>
              @endforeach --}}
            </div>
             {!! Form::close() !!}

        </div>
      </div>



    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
    <script>

        var updatable = true;

        $( document ).ready(function() {

          $('#cuisine-filter-select').select2({
            placeholder: 'Select cuisines'
          });

          $('.toggle-cuisines-on').click(function() {
            $('#cuisine-filter-select option').prop('selected', true).trigger('change.select2');
            updateRestaurants();
          });

          $('.toggle-cuisines-off').click(function() {
            $('#cuisine-filter-select option').prop('selected', false).trigger('change.select2');
            updateRestaurants();
          });


          $('#cuisine-filter-select').change(function() {
            updateRestaurants();
          });

          updateRestaurants();


        });

        function updateRestaurants() {

          $( '.restaurant-group' ).html('<i class="fa-li fa fa-spinner fa-spin"></i>');
          var selected = $('#cuisine-filter-select').val();
          $.get("/api/restaurants?cuisine_filter=" + JSON.stringify(selected), function(data, status){
              $( '.restaurant-group' ).html(data);
              // if (selected!==null) {
              //   $('.current-cuisines-label').html('Filtered cuisines'selected.join(', '));
              // } else {
              //   $('.current-cuisines-label').html('All cuisines');
              // }
          });
        }

    </script>

  </body>
</html>
