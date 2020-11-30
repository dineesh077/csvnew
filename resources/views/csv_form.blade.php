
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
      .header_sec {
      padding: 30px;
      background-color: yellow;
      }
      .welcome_sec {
      text-align: right;
      }
      .welcome_sec a {
      margin-left: 10px;
      color: blue;
      }
      .content_sec {
      padding: 50px 0;
      }
      .elem:valid {
      color: green;
      }
      .elem:invalid {
      color: red;
      }
    </style>
  </head>
  <body>
    <div class="header_sec">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <h1>MY LOGO</h1>
          </div>
          <div class="col-md-9">
            <div class="welcome_sec">
              Welcome Admin
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content_sec">
      <div class="container">
        <div class="row">
          <div class="col-md-3">
            <div class="dash_board">
              <h5>Dashboard</h5>
            </div>
          </div>
          <div class="col-md-9">
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
            </div>
            @endif
            @if ($message = Session::get('message'))
            <div class="alert alert-success alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
            </div>
            @endif
            @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <form enctype="multipart/form-data"  method="POST"  action="/csv/save">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
              <input type="hidden" name="csv_id" value="0"/>
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">CSV file</label>
                <div class="col-sm-6">
                  <input class="elem form-control" required type="file" name="csv_name">
                </div>
              </div>
              <!--<button type="submit" class="btn btn-primary">Upload</button>-->
              <input type='submit' name='submit' value='Import' class="btn btn-primary">
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--congtent_sec-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>  
  </body>
</html>