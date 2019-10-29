<link href="<?= base_url('assets/css/plugins/slick.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/plugins/slick-theme.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/plugins/datepicker.css') ?>" rel="stylesheet">
<style>
    #loader-div {
      width: 100%;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: -1;
    }

    .loader {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 1;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
    a.disabled {
        pointer-events: none;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="modal-title pb-4 text-center font-weight-bold" style="color:#1bac71" id="exampleModalLabel">Notification sent successfully</h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<div id="loader-div">
  <div id="loader">

  </div>
</div>
<!-- Header -->
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
  <div class="container-fluid">
    <div class="header-body">
      <!-- Card stats -->
      <div class="row">

      </div>
    </div>
  </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--7 wrap">
    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Notification List</h3>
                    <div class="row">

                        <div class="form-group col-lg-6 col-md-6 col-sm-12 focused">
                            <h4 class="pt-3">Filter</h4>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 focused">
                                    <label class="px-2 py-2">Select Date</label>
                                    <div class="input-group input-group-alternative border">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input class="form-control" id="datepicker" placeholder="Select date" type="text" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-sm-12 focused">
                            <h4 class="pt-3">Sort By</h4>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 focused">
                                    <label class="px-2 py-2">Create Date</label>
                                    <div class="input-group input-group-alternative border">
                                        <select class="form-control" id="sortingFilter">
                                            <option value="0">Select Order</option>
                                            <option value="oldToNew">Old To New</option>
                                            <option value="newToOld">New To Old</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group mb-0">
                                <div class="input-group input-group-alternative border">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Search" type="text" id="search">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table-responsive card shadow" id="notificationsListing">
                <table class="table align-items-center table-flush" id="notificationList">
                    <thead class="thead-light">
                        <tr on>
                            <th scope="col">#</th>
                            <th scope="col">Notifications</th>
                            <th scope="col">Audience</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-4">
            <nav aria-label="...">
              <ul class="pagination justify-content-end mb-0" id="pagination">

              </ul>
            </nav>
          </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://urcommunitycares.com/assets/web_files/vendor/jquery/datepicker.min.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/plugins/moment.js') ?>"></script>
    <script>
        $('#datepicker').datepicker({
            autoHide: true,
            endDate: "today"
        });
        $( "#datepicker" ).datepicker({  maxDate: 0 });

        $('#datepicker').on('pick.datepicker', function(e) {
            $('#datepicker').val(moment(e.date).format('YYYY-MM-DD'));
            loadPagination(0);
        });

        $('#sortingFilter').change(function() {
            loadPagination(0);
        })

        $('#search').keyup(function() {
            loadPagination(0);
        })

        $(document).ajaxStart(function() {
            $('#loader').addClass('loader');
            $('#loader-div').css('z-index', '10');
        });

        $(document).ajaxComplete(function() {
            $('#loader').removeClass('loader');
            $('#loader-div').css('z-index', '-1');
        });

        $('#pagination').on('click', 'a', function(e) {
            e.preventDefault();
            $pageNo = $(this).attr('data-ci-pagination-page');
            loadPagination($pageNo);
        });
        
        function createCurrentPageLink() {
            $activePage = $('#activePage').text();
            $('#currentPageLink').attr('href', '<?=SITE_URL?>Admin/Admin/loadNotificationListing/' + $activePage);
            $('#currentPageLink').attr('data-ci-pagination-page', $activePage);
        }

        loadPagination(0);

        function loadPagination(pagno) {
            $.ajax({
                url: '<?=SITE_URL?>Admin/Admin/loadNotificationListing/' + pagno,
                type: 'post',
                dataType: 'json',
                data: {
                    'date' : $('#datepicker').val(),
                    'sorting' : $('#sortingFilter').val(),
                    'search' : $('#search').val()
                },
                success: function(data) {
                    if(data.result == ''){
                        $('#notificationList tbody').empty();
                        $('#notificationList tbody').append("<tr><td colspan=6 class='text-center text-danger h2'>No data Found</td></tr>");
                    } else {
                        if(pagno != 0){
                            $('html, body').animate(
                            {
                                scrollTop: $('#notificationsListing').offset().top,
                            },
                            200,
                            'linear'
                            )
                        }
                        $('#pagination').html("<li class='page-item'><a id='currentPageLink' style='display:none'>currentPageLink</a></li>" + data.pagination);
                        createTable(data.result, data.row);
                    }
                }
            });
        }

        // Create table list
        function createTable(result, sno) {
            serialNumber = Number(sno);
            $('#notificationList tbody').empty();
            for (index in result) {
                $notificationId = result[index].notificationId;
                $information = result[index].information;
                $notificationAudience = result[index].notificationAudience;
                serialNumber += 1;

                var tr = "<tr>";
                tr += "<td>" + serialNumber + "</td>";
                tr += "<td class='white-space-unset'>" + $information + "</td>";
                tr += "<td>" + $notificationAudience + "</td>";
                tr += "<td><button class='btn btn-icon btn-2 btn-primary' type='button' title='Detail' id='"+ $notificationId +"' onclick='repeatNotification(this.id)'>Repeat Notification</button></td>";
                tr += "</tr>";
                $('#notificationList tbody').append(tr);
            }
        }
        function repeatNotification(id){
            createCurrentPageLink();
            $.ajax({
                url: '<?=SITE_URL?>Admin/Admin/repeatNotification',
                type: 'post',
                data: {
                    'notificationId' : id
                },
                success: function(data) {
                    $('#currentPageLink').trigger('click');
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                }
            });
        }

    </script>