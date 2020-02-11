<!DOCTYPE html>
<html>
<head>
	<title>Kuisku | Login</title>
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/css/login.css">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
          	<center>
          	<img src="<?= base_url() ?>/assets/z-techno.png" width="150px">
          	</center>
            <h5 class="card-title text-center">KUIS KU LOGIN</h5>
            <form class="form-signin" method="post" action="<?= base_url() ?>/do-login">
              <input type="hidden" name="act" value="login">
              <div class="form-label-group">
                <input type="email" name="user_login" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputEmail">Email</label>
              </div>

              <div class="form-label-group">
                <input type="password" name="user_pass" id="inputPassword" class="form-control" placeholder="Password" required>
                <label for="inputPassword">Kata Sandi</label>
              </div>

              <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember password</label>
              </div>
              <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit"><i class="fa fa-sign-in"></i> Sign in</button>
              <hr class="my-4">
              <center>Powered By Z-Tehno</center>
              <!-- <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i class="fa fa-google mr-2"></i> Sign in with Google</button>
              <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fa fa-facebook-f mr-2"></i> Sign in with Facebook</button> -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>