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
    <div class="header pt-7 pt-lg-8">
      <div class="container">
        <div class="header-body text-center mb-7">
          <h1 class="text-green">Reset Password</h1>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form">
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="New Password" type="password" id="resetPassword">
                  </div>
                  <span class="validate_error" id="validateErrorResetPassword"></span>
                </div>

                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Confirm Password" type="password" id="confirmResetPassword">
                  </div>
                  <span class="validate_error" id="validateErrorConfirmResetPassword"></span>
                </div>

                <div class="text-center">
                  <button type="button" class="btn btn-primary my-4 btn-rounded" id="resetPasswordButton">Submit</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>