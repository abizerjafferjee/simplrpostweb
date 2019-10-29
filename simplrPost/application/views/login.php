<body class="form-bg ">
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
    <div class="header pt-6">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-green">Welcome!</h1>

              <!-- <p class="text-lead text-light">Use these awesome forms to login or create new account in your project for free.</p> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container pb-2">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">

            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                Sign in with credentials
              </div>
              <form role="form">
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control pl-2" placeholder="Email" type="email" id="userEmail" name="userEmail">
                  </div>
                  <span class="validate_error" id="validateEmailError"></span>
                </div>

                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control pl-2" placeholder="Password" type="password" id="userPassword" name="userPassword">
                  </div>
                  <span class="validate_error" id="validatePasswordError"></span>
                </div>

                <div class="custom-control">
                  <a href="<?= SITE_URL ?>forgot-password" id='forgotPasswordLink'><small>Forgot password?</small></a>
                </div>

                <div class="text-center">
                  <button type="button" class="btn btn-primary my-4 btn-rounded" id='signIn'>Sign in</button>
                </div>
              </form>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>