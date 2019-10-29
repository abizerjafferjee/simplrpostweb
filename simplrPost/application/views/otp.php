<body class="from-bg">
  <div id="loader-div">
    <div id="loader">

    </div>
  </div>
  <div id="otp">

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
    <div class="header pt-7 pt-lg-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-green">OTP Authentication</h1>
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
              <div class="text-center text-muted mb-4">
                Enter your OTP to reset password
              </div>
              <form role="form">
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni single-copy-04"></i></span>
                    </div>
                    <input class="form-control" placeholder="OTP" type="text" id="otpValue">
                  </div>
                  <span class="validate_error"></span>
                </div>
                <div class="col-12 text-right">
                  <a href="#" id="resendOtp"><small>Resend OTP</small></a>
                </div>

                <div class="text-center">
                  <a href="#" class="btn btn-rounded btn-primary my-4" id="otpSubmitButton">Submit</a>
                </div>
              </form>
            </div>
          </div>
          <div class="row mt-3">

          </div>
        </div>
      </div>
    </div>
  </div>
</body>