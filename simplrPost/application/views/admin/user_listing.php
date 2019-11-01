<link href="https://urcommunitycares.com/assets/web_files/vendor/plugin_css/datepicker.css" rel="stylesheet">
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
<!--delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to delete this user?</h3>
          <!-- <div class="custom-control custom-checkbox mb-3">
          <input class="custom-control-input" id="customCheck1" type="checkbox">
          <label class="custom-control-label" for="customCheck1">This category is selected by so many users. If you delete it, it will put impact on system. Please cross check before proceeding. </label>
    </div> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary deleteModalButton" id="" onclick="deleteUser(this.id)">Delete</button>
        </div>
      </div>
    </div>
  </div>

<div class="container-fluid mt--7">
  <!-- Table -->
  <div class="row">
    <div class="col">
      <div class="card shadow">
        <div class="card-header border-0">
          <h3 class="mb-0">User Listing</h3>
          <div class="row">

            <div class="form-group col-lg-6 col-md-6 col-sm-12">
              <h4 class="pt-3">Filter</h4>
              <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
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

            <div class="form-group col-lg-6 col-md-6 col-sm-12">
              <h4 class="pt-3">Sort By</h4>
              <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                  <label class="px-2 py-2">Create Date</label>
                  <div class="input-group input-group-alternative border">
                    <select class="form-control" id="sortingFilter">
                      <option value="">Select Sort Order</option>
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
                  <input class="form-control" placeholder="Search" type="text" id="userSearchInput">
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="table-responsive" id="usersListing">
        <table class="table align-items-center table-flush" id="usersList">
          <thead class="thead-light">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Image</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Detail</th>
              <th scope="col">Delete User</th>
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
  <!-- </div> -->
  <!-- Dark table -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://urcommunitycares.com/assets/web_files/vendor/jquery/datepicker.min.js"></script>
  <script type="text/javascript" src="https://urcommunitycares.com/assets/web_files/vendor/jquery/moment.js"></script>
  <script type="text/javascript">

    $('#datepicker').datepicker({
      autoHide: true,
      endDate: "today"
    });

    $('#datepicker').on('pick.datepicker', function(e) {
      $('#datepicker').val(moment(e.date).format('YYYY-MM-DD'));
      loadPagination(0);
    });

    $('#sortingFilter').change(function() {
      loadPagination(0);
    })

    $('#userSearchInput').keyup(function() {
      loadPagination(0);
    })

    $(document).ajaxStart(function() {
      $('#loader').addClass('loader');
      $('#mainBody').css('z-index', '-1');
      $('#loader-div').css('z-index', '10');
    });

    $(document).ajaxComplete(function() {
      $('#loader').removeClass('loader');
      $('#mainBody').css('z-index', '1');
      $('#loader-div').css('z-index', '-1');
    });

    // Detect pagination click
    $('#pagination').on('click', 'a', function(e) {
      e.preventDefault();
      $pageNo = $(this).attr('data-ci-pagination-page');
      loadPagination($pageNo);
    });

    loadPagination(0);
    
    function loadPagination(pagno) {
      $.ajax({
        url: '<?=SITE_URL?>Admin/Admin/loadUserListing/' + pagno,
        type: 'post',
        dataType: 'json',
        data: {
          'date' : $('#datepicker').val(),
          'sorting' : $('#sortingFilter').val(),
          'search': $('#userSearchInput').val()
        },
        success: function(data) {
          if(data.result == ''){
            $('#usersList tbody').empty();
            $('#usersList tbody').append("<tr><td colspan=6 class='text-center text-danger h2'><img src='<?= BASE_URL?>assets/img/no_data.png'></td></tr>");
          } else {
            if(pagno != 0){
              $('html, body').animate(
                {
                  scrollTop: $('#usersListing').offset().top,
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
    function createCurrentPageLink() {
      $activePage = $('#activePage').text();
      $('#currentPageLink').attr('href', '<?=SITE_URL?>Admin/Admin/loadUserListing/' + $activePage);
      $('#currentPageLink').attr('data-ci-pagination-page', $activePage);
    }
    // Create table list
    function createTable(result, sno) {
      serialNumber = Number(sno);
      $('#usersList tbody').empty();
      for (index in result) {
        $userId = result[index].userId;
        $profilePicURL = result[index].profilePicURL;
        $name = result[index].name;
        $emailId = result[index].emailId;
        serialNumber += 1;

        var tr = "<tr>";
        tr += "<td>" + serialNumber + "</td>";
        tr += "<td scope='row'><div class='media align-items-center'><a class='avatar rounded-circle mr-3'><img style='object-fit:cover' alt='Image placeholder' src='<?=BASE_URL?>uploads/"+ $profilePicURL + "' onerror='this.onerror=null;this.src=\"<?=BASE_URL?>assets/img/user_default.png\"' height='100%' width='100'></a></div></td>";

        tr += "<td>" + $name + "</td>";
        tr += "<td>" + $emailId + "</td>";
        tr += "<td><a href='<?=BASE_URL?>user-detail/" + encodeURIComponent(window.btoa($userId)) + "'class='btn btn-icon btn-2 btn-primary' title='Detail'><span><i class='fas fa-eye'></i></span></a></td>";
        tr += "<td><button class='btn btn-icon btn-2 btn-danger' type='button' id='" + $userId + "' onclick='setDeleteModalUserId(this.id)'><span><i class='fas fa-trash'></i></span></button></td>";
        tr += "</tr>";
        $('#usersList tbody').append(tr);
      }
    }

    function setDeleteModalUserId(id) {
      createCurrentPageLink();
      $('.deleteModalButton').attr('id', id);
      $('#deleteModal').modal({backdrop: 'static', keyboard: false});
    }

    function deleteUser(id) {
      $.ajax({
        type: "post",
        url: '<?=SITE_URL?>Admin/Admin/deleteUser',
        data: {
          'userId': id
        },
        success: function(data) {
          $('#currentPageLink').trigger('click');
          $('.modalCloseButton').trigger('click');
        }
      });
    }
  </script>