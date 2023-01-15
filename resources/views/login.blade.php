<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Sign In</div>
                </div>

                <div style="padding-top:30px" class="panel-body">

                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                    <form id="loginform" class="form-horizontal" role="form" action="{{route('authenticate')}}" method="post">
                        @csrf
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="email" class="form-control" name="email" value="" placeholder="email">
                        </div>
                        @error('username')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="" style="margin-bottom: 25px"></div>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                        </div>
                        @error('password')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="" style="margin-bottom: 25px"></div>
                        <div style="margin-top:10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <button type="submit" id="btn-login" href="#" class="btn btn-success">Login </button>
                            </div>
                        </div>
                    </form>



                </div>
            </div>
        </div>
    </div>
</body>

</html>