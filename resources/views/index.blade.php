
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

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/css/bootstrap3/bootstrap-switch.min.css" crossorigin="anonymous">

     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


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

      <div class="row">
        <div class="col-md-9">
        <div class="list-group">
          @foreach ($restaurants as $restaurant)

<!--              <a href="{{ $restaurant->permalink }}" class="list-group-item">
              <h4 class="list-group-item-heading"></h4>
              <p class="list-group-item-text"></p>
            </a> -->

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


        </div>
        <div class="col-md-3">
          <h3>Sidebar</h3>
          <form>
            @foreach ($cuisines as $cuisine)
              <div class="checkbox" style="width:100%">
                <input data-size="mini" checked=true type="checkbox" name="cuisine" data-label-text="{{ $cuisine->title }}" value="{{ $cuisine->id }}">
              </div>
            @endforeach
          </form>

        </div>
      </div>



    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js" crossorigin="anonymous"></script>

    <script>
        $("[name='cuisine']").bootstrapSwitch();
    </script>

  </body>
</html>
