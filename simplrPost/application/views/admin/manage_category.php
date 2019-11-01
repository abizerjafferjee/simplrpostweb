<!-- <script>
    document.cookie = "currentActivePage="+0;
</script> -->
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

<div class="container-fluid mt--7">
    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Manage Categories</h3>

                    <div class="float-right">
                        <button class="btn btn-icon btn-3 btn-primary" type="button" data-toggle="modal" data-target="#add_modal" data-backdrop="static" data-keyboard="false">
                            <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                            <span class="btn-inner--text">Add Category</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive" id="manageCategories">
                <table class="table align-items-center table-flush" id="categoriesList">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
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
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to delete this category ?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary deleteModalButton" id="" onclick="deleteCategory(this.id)">Delete</button>
                </div>
            </div>
        </div>
    </div>



    <!--edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="edit_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title pb-4 text-center" id="exampleModalLabel">Edit Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="email" class="form-control form-control-alternative" id="categoryName" placeholder="Category Name">
                    </div>
                    <span class="validate_error"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary editModalButton" onclick="editCategory(this.id)">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--add Modal -->
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Add Category</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="email" class="form-control form-control-alternative" id="newCategoryName" placeholder="Category Name">
                    </div>
                    <span class="validate_error" id="answerValidationError"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addNewCategory" onclick="addNewCategory()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://urcommunitycares.com/assets/web_files/vendor/jquery/datepicker.min.js"></script>
    <script type="text/javascript" src="https://urcommunitycares.com/assets/web_files/vendor/jquery/moment.js"></script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            autoHide: true,
            format: 'yyyy-mm-dd'
        });



        $('.datepicker').on('pick.datepicker', function(e) {
            var dt = moment(e.date);

            dob = dt.format('YYYY-MM-DD');

            $(this).val(dt.format('MMM DD, YYYY'));

        });
    </script>
    <script type='text/javascript'>
        $('input').focus(function(){
            $('.validate_error').html('');
            $('.validate_error').css('z-index', -1);
        })
        $(document).ready(function() {

            $(document).ajaxStart(function() {
                $('#loader').addClass('loader');
                $('#loader-div').css('z-index', '10');
            });

            $(document).ajaxComplete(function() {
                $('#loader').removeClass('loader');
                $('#loader-div').css('z-index', '-1');
            });

            // Detect pagination click
            $('#pagination').on('click', 'a', function(e) {
                e.preventDefault();
                $pageNo = $(this).attr('data-ci-pagination-page');
                loadPagination($pageNo);
            });

            loadPagination(0);
            // Load pagination
            function loadPagination(pagno) {
                $.ajax({
                    url: '<?=SITE_URL?>Admin/Admin/loadCategories/' + pagno,
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if(data.result == ''){
                            $('#categoriesList tbody').empty();
                            $('#categoriesList tbody').append("<tr><td colspan=6 class='text-center text-danger h2'><img src='<?= BASE_URL?>assets/img/no_data.png'></td></tr>");
                        } else {
                            if(pagno != 0){
                                $('html, body').animate(
                                    {
                                        scrollTop: $('#manageCategories').offset().top,
                                    },
                                    500,
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
                $('#categoriesList tbody').empty();

                result.forEach(function(result, index) {
                    $categoryId = result.categoryId;
                    $categoryName = result.categoryName;
                    serialNumber += 1;

                    var tr = "<tr>";
                    tr += "<td>" + serialNumber + "</td>";
                    tr += "<td>" + $categoryName + "</td>";
                    tr += "<td><button class='btn btn-icon btn-2 btn-primary' type='button' id='" + $categoryId + "' onclick='setEditModalValues(this.id)'><span><i class='fas fa-edit'></i></span></button></td>";
                    tr += "<td><button class='btn btn-icon btn-2 btn-danger' type='button' id='" + $categoryId + "' onclick='setDeleteModalCategoryId(this.id)'><span><i class='fas fa-trash'></i></span></button></td>";
                    tr += "</tr>";
                    $('#categoriesList tbody').append(tr);
                })
            }
        });

        function createCurrentPageLink() {
            $activePage = $('#activePage').text();
            $('#currentPageLink').attr('href', '<?=SITE_URL?>Admin/Admin/loadCategories/' + $activePage);
            $('#currentPageLink').attr('data-ci-pagination-page', $activePage);
        }

        function setEditModalValues(id) {
            $('.validate_error').html('');
            $('.validate_error').css('z-index', -1);
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/getCategoryValues',
                data: {
                    'categoryId': id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    $('.editModalButton').attr('id', id);
                    $('#categoryName').val(data.categoryName);
                    $('#editModal').modal({backdrop: 'static', keyboard: false});
                }
            });
        }

        function editCategory(id) {
            $categoryName = $('#categoryName').val();
            if ($categoryName.trim().length == 0) {
                $('.validate_error').html('Please fill this field');
                $('.validate_error').css('z-index', 10);
            } else {
                $.ajax({
                    type: "post",
                    url: '<?=SITE_URL?>Admin/Admin/editCategory',
                    data: {
                        'categoryId': id,
                        'categoryName': $categoryName
                    },
                    success: function(data) {
                        $result = JSON.parse(data);
                        $page = Math.ceil($result.position / 50);
                        $('#currentPageLink').attr('href', '<?=SITE_URL?>Admin/Admin/loadCategories/' + $page);
                        $('#currentPageLink').attr('data-ci-pagination-page', $page);
                        $('#currentPageLink').trigger('click');
                        $('.modalCloseButton').trigger('click');
                    }
                });
            }
        }

        function addNewCategory() {
            createCurrentPageLink();
            $categoryName = $('#newCategoryName').val();
            if ($categoryName.trim().length == 0) {
                $('.validate_error').html('Please fill this field');
                $('.validate_error').css('z-index', 10);
            } else {
                $.ajax({
                    type: "post",
                    url: '<?=SITE_URL?>Admin/Admin/addNewCategory',
                    data: {
                        'categoryName': $categoryName
                    },
                    success: function(data) {
                        $('#currentPageLink').trigger('click');
                        $('.modalCloseButton').trigger('click');
                        $('.validate_error').html('Please fill this field');
                        $('.validate_error').css('z-index', 10);
                    }
                });
            }
        }

        function setDeleteModalCategoryId(id) {
            createCurrentPageLink();
            $('.deleteModalButton').attr('id', id);
            $('#deleteModal').modal({backdrop: 'static', keyboard: false});
        }

        function deleteCategory(id) {
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/deleteCategtory',
                data: {
                    'categoryId': id
                },
                success: function(data) {
                    $('#currentPageLink').trigger('click');
                    $('.modalCloseButton').trigger('click');
                }
            });
        }
    </script>