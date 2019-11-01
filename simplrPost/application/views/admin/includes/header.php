<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-147249123-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-147249123-1');
  </script>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $title ?></title>
  <!-- Favicon -->
  <link href="<?= base_url('assets/img/brand/favicon.png') ?>" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->
  <link href="<?= base_url('assets/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
  <link href = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel = "stylesheet">
  <link href="<?= base_url('assets/js/plugins/%40fortawesome/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="<?= base_url('assets/css/argon-dashboard.min9f1e.css?v=1.1.0') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" />
  <style>
    .page-item a {
      position: relative;
      display: block;
      padding: .5rem .75rem;
      margin-left: -1px;
      line-height: 1.25;
      color: #8898aa;
      background-color: #fff;
      border: 1px solid #dee2e6
    }

    .page-item a:hover {
      z-index: 2;
      color: #8898aa;
      text-decoration: none;
      background-color: #dee2e6;
      border-color: #dee2e6
    }

    .page-item a:focus {
      z-index: 2;
      outline: 0;
      box-shadow: none
    }

    .page-item:first-child a {
      margin-left: 0;
      border-top-left-radius: .375rem;
      border-bottom-left-radius: .375rem
    }

    .page-item:last-child a {
      border-top-right-radius: .375rem;
      border-bottom-right-radius: .375rem
    }

    .page-item.active a {
      z-index: 1;
      color: #fff;
      background-color: #1bac71;
      border-color: #1bac71
    }

    .page-item.disabled a {
      color: #8898aa;
      pointer-events: none;
      cursor: auto;
      background-color: #fff;
      border-color: #dee2e6
    }

    .pagination-lg a {
      padding: .75rem 1.5rem;
      font-size: 1.25rem;
      line-height: 1.5
    }

    .pagination-lg .page-item:first-child a {
      border-top-left-radius: .4375rem;
      border-bottom-left-radius: .4375rem
    }

    .pagination-lg .page-item:last-child a {
      border-top-right-radius: .4375rem;
      border-bottom-right-radius: .4375rem
    }

    .pagination-sm a {
      padding: .25rem .5rem;
      font-size: .875rem;
      line-height: 1.5
    }

    .pagination-sm .page-item:first-child a {
      border-top-left-radius: .25rem;
      border-bottom-left-radius: .25rem
    }

    .pagination-sm .page-item:last-child a {
      border-top-right-radius: .25rem;
      border-bottom-right-radius: .25rem
    }

    .page-item.active a{
      box-shadow: 0 7px 14px rgba(50, 50, 93, .1), 0 3px 6px rgba(0, 0, 0, .08)
    }

    .page-item a {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      margin: 0 3px;
      border-radius: 50% !important;
      width: 36px;
      height: 36px;
      font-size: .875rem
    }

    .pagination-lg .page-item a {
      width: 46px;
      height: 46px;
      line-height: 46px
    }

    .pagination-sm .page-item a{
      width: 30px;
      height: 30px;
      line-height: 30px
    }
  </style>
</head>

<body class="">
  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="<?= BASE_URL ?>">
        <img src="<?= base_url('assets/img/brand/logo.png" class="navbar-brand-img') ?>" alt="...">
      </a>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        
        <!-- <li class="nav-item dropdown">
          <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="<?= BASE_URL . 'uploads/' . $data->profilePicURL ?>" height="100%" width="100%" onerror='this.onerror=null;this.src="<?= BASE_URL?>uploads/admin/admin_placeholder.jpg"'>
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="<?= BASE_URL ?>admin-profile" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>
            <a href="<?= BASE_URL ?>change-password" class="dropdown-item">
              <i class="ni ni-lock-circle-open"></i>
              <span>Change Password</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= BASE_URL ?>logout" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="<?=BASE_URL?>admin-dashboard">
                <img src="<?= base_url('assets/img/brand/logo.png" class="navbar-brand-img') ?>">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <!-- <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form> -->
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>admin-dashboard">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/Dashboard.png') ?>"> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="<?= BASE_URL ?>users-list">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/userList.png') ?>"> User Listing
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="<?= BASE_URL ?>businesses-list">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/Business-List.png') ?>"> Business Listing
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#categories" aria-expanded="true" aria-controls="ui-advanced">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/manageCategories.png') ?>"> Categories
            </a>
            <div class="collapse" id="categories">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="<?= BASE_URL ?>manage-categories">Manage Categories</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= BASE_URL ?>manage-primary-categories">Manage Primary Categories</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-advanced" aria-expanded="true" aria-controls="ui-advanced">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/Notification.png') ?>"> Notification
            </a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="<?= BASE_URL ?>notifications-list">
                    Notifications
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= BASE_URL ?>send-notification">
                    Send Notification
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-faq" aria-expanded="true" aria-controls="ui-faq">
            <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/manageFAQ.png') ?>"> FAQs
            </a>
            <div class="collapse" id="ui-faq">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>manage-FAQ">
                    Manage FAQ
                  </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>manage-issues">
                    Manage Issues
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>feedbacks-list">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/Feedback.png') ?>"> Feedback
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="true" aria-controls="ui-advanced">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/Report.png') ?>"> Manage Reports
            </a>
            <div class="collapse" id="reports">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="<?= BASE_URL ?>reports-list"> Reports </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= BASE_URL ?>reported-businesses-list">Reported Businesses</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= BASE_URL ?>manage-pages">
              <img class="sidebar-icon" src="<?= base_url('assets/img/icons/sidebar/managePages.png') ?>"> Manage Pages
            </a>
          </li>
        </ul>
        <!-- Divider -->
        <hr class="my-3">
        </ul>
      </div>
    </div>
  </nav>
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <!-- <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="../index.html">Tables</a> -->
        <!-- Form -->
        <!-- <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input class="form-control" placeholder="Search" type="text">
            </div>
          </div>
        </form> -->
        <!-- User -->
        <ul class="navbar-nav align-items-center ml-auto d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="<?= BASE_URL . 'uploads/' . $data->profilePicURL ?>" height="100%" width="100%" width="100%" onerror='this.onerror=null;this.src="<?= BASE_URL?>uploads/admin/admin_placeholder.jpg"'>
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?= $data->name ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
              </div>
              <a href="<?= BASE_URL ?>admin-profile" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="<?= BASE_URL ?>change-password" class="dropdown-item">
                <i class="ni ni-lock-circle-open"></i>
                <span>Change Password</span>
              </a>

              <div class="dropdown-divider"></div>
              <a href="<?= BASE_URL ?>logout" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->