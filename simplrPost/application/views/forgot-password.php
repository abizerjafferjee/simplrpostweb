<body class="form-bg">
  <div id="loader-div">
    <div id="loader">

    </div>
  </div>
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
      <div class="container px-4">
        <span class="navbar-brand">
          <a href="<?= BASE_URL ?>"><img src="<?= base_url('assets/img/brand/logo.png') ?>" /></a>
        </span>
      </div>
    </nav>
    <!-- Header -->
    <div class="header pt-5 pt-lg-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-green">Forgot Password</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">

            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-5">
                Please enter your email for OTP
              </div>
              <form method="post" action="#">
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" placeholder="Email" type="email" id="forgotPasswordEmail">
                  </div>
                  <span class="validate_error"></span>
                </div>
                <div class="custom-control text-right">
                  <a href="<?= base_url() ?>" class="h3 text-green"><small>Sign in?</small></a>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-rounded btn-primary my-4" id="forgotPassword">Send</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</body>