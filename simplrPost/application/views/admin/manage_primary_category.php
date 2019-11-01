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
    .image-upload>input {
        display: none;
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
                    <h3 class="mb-0">Manage Primary Categories</h3>

                    <div class="float-right">
                        <button class="btn btn-icon btn-3 btn-primary" type="button" onclick="$('#add_modal').modal({backdrop: 'static', keyboard: false})">
                            <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                            <span class="btn-inner--text">Add Primary Category</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive" id="categoriesListing">
                <table class="table align-items-center table-flush" id="categoriesList">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Icon</th>
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
    <!-- </div> -->
    <!-- Dark table -->


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
                    <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to delete this primary category?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary deleteModalButton" id="" onclick="deletePrimaryCategory(this.id)">Delete</button>
                </div>
            </div>
        </div>
    </div>



    <!--edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="edit_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title pb-4 text-center" id="exampleModalLabel">Edit Primary Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <select class="form-control" id="editCategory">
                                    <option value="" id="selectedCategory">Select Category</option>
                                </select>
                            </div>
                        </div>
                        <span class="validate_error" id="editCategoryNameValidationError"></span>
                        <div class="image-upload mb-2">
                            <label for="editFile">
                                <img src="<?= BASE_URL . 'assets/img/icons/upload.png' ?>" width="100px" height="100px" id="previewEditImage">
                            </label>
                            <input type="file" id="editFile" onchange="readURL(this)" data-id="previewEditImage">
                        </div>
                        <span class="validate_error" id="editCategoryIconValidationError"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary editModalButton" onclick="editPrimaryCategory(this.id)" id="1">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--add Modal -->
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Add Primary Category</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <select class="form-control" id="selectCategory">
                                        <option value="">Select Category</option>
                                    </select>
                                </div>
                            </div>
                            <span class="validate_error" id="addCategoryNameValidationError"></span>
                            <div class="image-upload mb-2">
                                <label for="selectIconImage">
                                    <img src="<?= BASE_URL . 'assets/img/icons/upload.png' ?>" width="100px" height="100px" id="previewSelectImage">
                                </label>
                                <input type="file" id="selectIconImage" onchange="readURL(this)" data-id="previewSelectImage">
                            </div>
                            <span class="validate_error" id="addCategoryIconValidationError"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="addNewCategory">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
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
        $option = '';
        $(document).ready(function() {
            $('input').focus(function(){
                $('#editCategoryNameValidationError').css('z-index', -1);
                $('#addCategoryNameValidationError').css('z-index', -1);
            })
            $('select').focus(function(){
                $('#editCategoryIconValidationError').css('z-index', -1);
                $('#addCategoryIconValidationError').css('z-index', -1);
            })
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
                    url: '<?=SITE_URL?>Admin/Admin/loadPrimaryCategories/' + pagno,
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if(data.result == ''){
                            $('#notificationList tbody').empty();
                            $('#notificationList tbody').append("<tr><td colspan=6 class='text-center text-danger h2'><img src='<?= BASE_URL?>assets/img/no_data.png'></td></tr>");
                        } else {
                            if(pagno != 0){
                                $('html, body').animate(
                                {
                                    scrollTop: $('#categoriesListing').offset().top,
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
                $('#categoriesList tbody').empty();

                result.forEach(function(result, index) {
                    serialNumber += 1;

                    var tr = "<tr>";
                    tr += "<td>" + serialNumber + "</td>";
                    tr += "<td><a class='avatar rounded-circle'><img src='<?=BASE_URL?>uploads/"+result.iconImageURL+"' onerror='this.src=\"<?=BASE_URL?>assets/img/user_default.png\"' height='100%' width='100%'></a></td>";
                    tr += "<td>" + result.categoryName + "</td>";
                    tr += "<td><button class='btn btn-icon btn-2 btn-primary' type='button' id='" + result.categoryId + "' onclick='setEditModalValues(this.id)' data-id='"+result.primaryCategoryId+"'><span><i class='fas fa-edit'></i></span></button></td>";
                    tr += "<td><button class='btn btn-icon btn-2 btn-danger' type='button' id='" + result.categoryId + "' onclick='setDeleteModalPrimaryCategoryId(this.id)'><span><i class='fas fa-trash'></i></span></button></td>";
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
            $primaryCategoryId = $('#'+id).attr('data-id');
            $('.validate_error').css('z-index', -1);
            createCurrentPageLink();
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/getPrimaryCategoryValues',
                data: {
                    'primaryCategoryId': id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    $('#previewEditImage').attr('src', '<?= BASE_URL ?>uploads/'+data.iconImageURL);
                    $('#selectedCategory').attr("value",data.categoryId);
                    $('#selectedCategory').text(data.categoryName);
                    $('#editCategory').val(data.categoryId);
                    $('.editModalButton').attr('id', id);
                    $('.editModalButton').attr('data-id', $primaryCategoryId);
                    $('#editModal').modal({backdrop: 'static', keyboard: false});
                }
            });
        }

        function editPrimaryCategory(id) {
            createCurrentPageLink();
            $primaryCategoryId = $('#'+id).attr('data-id');
            var formData = new FormData();
            var files = $('#editFile')[0].files[0];
            formData.append('primaryCategoryId', $primaryCategoryId);
            formData.append('categoryId', id);
            formData.append('file',files);
            formData.append('newCategoryId', $('#editCategory').val());
            if($('#editCategory').val() == ''){
                $('#editCategoryNameValidationError').html('Please select a category');
                $('#editCategoryNameValidationError').css('z-index', 10);
            } else if(files == ''){
                $('#editCategoryIconValidationError').html('Please select an icon');
                $('#editCategoryNameValidationError').css('z-index', 10);
            } else {
                $.ajax({
                    type: "post",
                    url: '<?=SITE_URL?>Admin/Admin/editPrimaryCategory',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        getCategoryName();
                        $('#currentPageLink').trigger('click');
                        $('.modalCloseButton').trigger('click');
                    }
                });
            }
        }
        $('#addNewCategory').on('click', function(e){
            e.preventDefault();
            createCurrentPageLink();
            var formData = new FormData();
            var files = $('#selectIconImage')[0].files[0];
            formData.append('file',files);
            formData.append('categoryId', $('#selectCategory').val());
            if($('#selectCategory').val().trim == ''){
                $('#addCategoryNameValidationError').html('Please select a category');
            } else if(files == ''){
                $('#addCategoryIconValidationError').html('Please select an icon');
            } else {
                $.ajax({
                    type: "post",
                    url: '<?=SITE_URL?>Admin/Admin/addNewPrimaryCategory',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if(data == 1){
                            getCategoryName();
                            $('#currentPageLink').trigger('click');
                            $('.modalCloseButton').trigger('click');
                        } else if(data > 1){
                            console.log('This category already existed');
                        } else {
                            console.log('Error occured');
                        }
                    }
                });
            }
        });

        function setDeleteModalPrimaryCategoryId(id) {
            createCurrentPageLink();
            $('.deleteModalButton').attr('id', id);
            $('#deleteModal').modal({backdrop: 'static', keyboard: false});
        }

        function deletePrimaryCategory(id) {
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/deletePrimaryCategory',
                data: {
                    'primaryCategoryId': id
                },
                success: function(data) {
                    getCategoryName();
                    $('#currentPageLink').trigger('click');
                    $('.modalCloseButton').trigger('click');
                }
            });
        }
        getCategoryName();
        function getCategoryName(){
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/getAllCategoryNames',
                success: function(data) {
                    data = JSON.parse(data);
                    for($i=0; $i<data.length; $i++){
                        $option += "<option value='"+data[$i].categoryId+"'>"+data[$i].categoryName+"</option>";
                    }
                    $('#editCategory').append($option);
                    $('#selectCategory').append($option);
                }
            });
        }
        function readURL(input) {
            $id = $(input).attr('data-id');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#'+$id).attr('src', e.target.result);
                    $('#'+$id).css('object-fit','cover');
                    $('#'+$id).css('margin-left','0');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>