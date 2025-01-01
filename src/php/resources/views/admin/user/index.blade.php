@extends('admin.admin_template')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Users</h1>
    @include('admin.section.flash_message')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!--<form autocomplete="off" class="form-validation" id="delete_bulk_users_form" role="form" method="post"
                      action="{{ secure_url('/users/doBulkDeleteUsers') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>-->
                    <div class="row form-inline mb-2">
                        <div class="col-sm-9">
                            Show
                            <select id="rowsPerPage" class="form-control input-sm">
                                <option value="5" {{ ($limit == '5') ? 'selected' : '' }}>5</option>
                                <option value="10" {{ ($limit == '10') ? 'selected' : '' }}>10</option>
                                <option value="25" {{ ($limit == '25') ? 'selected' : '' }}>25</option>
                                <option value="50" {{ ($limit == '50') ? 'selected' : '' }}>50</option>
                                <option value="100" {{ ($limit == '100') ? 'selected' : '' }}>100</option>
                            </select>
                            entries
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" id="search_by_keywords" class="form-control"
                                       placeholder="Search by Email"
                                       value="{{ $search_by_keywords }}">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" id="search_btn" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="ajaxResponse" class="overflowAutoSmallScreen"></div>
                    <!--
                    <div class="row">
                        <div class="m-t-20">
                            <button type="submit" id="submitBtn" class="btn btn-embossed btn-primary" data-style="expand-left">Delete</button>
                        </div>
                    </div>
                    -->
                <!--</form>-->
            </div>
        </div>
    </div>

    <!-- View user -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="viewUserContainer">
                    <h4 class="text-center">Loading...</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
      var mainPanelDivEl = $(".table-responsive:first");

      // Get url
      var getUsersPageURL = function (page, limit, search_by_keywords) {
        return '/user?page=' + page + '&limit=' + limit + '&search_by_keywords=' + encodeURIComponent(search_by_keywords);
      };

      var url = getUsersPageURL('{{ $page }}', '{{ $limit }}', '{{ $search_by_keywords }}');

      $(document).ready(function () {

        // Pagination
        $(document).on('click', '.pagination a', function (e) {
          url = $(this).attr('href');
          addUsersPushState(url);
          getUsersPage(url);
          e.preventDefault();
        });

        // Rows per page
        $(document).on('change', '#rowsPerPage', function (e) {
          var updateUrl = getUsersPageURL(1, $('#rowsPerPage').val(), $('#search_by_keywords').val());
          addUsersPushState(updateUrl);
          getUsersPage(updateUrl);
          e.preventDefault();
        });

        // Search by keyword
        $(document).on('click', '#search_btn', function (e) {
          var updateUrl = getUsersPageURL(1, $('#rowsPerPage').val(), $('#search_by_keywords').val());
          addUsersPushState(updateUrl);
          getUsersPage(updateUrl);
          e.preventDefault();
        });

        // Back button action
        $(window).on('popstate', function () {
          if (history.state !== null) {
            if (typeof (history.state.usersPageUrl) == 'string') {
              getUsersPage(history.state.usersPageUrl);
            }
          } else {
            getUsersPage(url);
          }
        });

        // Select multiple records
        $(document).on('click', '#selectAll', function (e) {
          $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });

      });

      // Ajax request
      var getUsersPage = function (url) {
        blockUI(mainPanelDivEl);
        $.get(url, function (data, status, xhr) {
          unblockUI(mainPanelDivEl);

          if (xhr.getResponseHeader('Content-Type') === 'application/json' && data.error) {
            window.location = 'not-found';
          }

          if (status == 'success') {
            $('#ajaxResponse').html(data);
          }
        });
      };

      // Add push state
      var addUsersPushState = function (url) {
        window.history.pushState({usersPageUrl: url}, 'Users', url);
      };

      setTimeout(function () {
        getUsersPage(url);
        window.history.replaceState({usersPageUrl: url}, '', window.location.href);
      }, 100);

      function loadUser(user_uid) {
        $('#viewUserContainer').html('<h4 class="text-center">Loading...</h4>');

        $('#viewUserModal').modal({
          backdrop: 'static'
        });

        $.ajax({
          type: "GET",
          url: '/user/view/' + user_uid,
          success: function(data)
          {
            $('#viewUserContainer').html(data);
          }
        });
      }
    </script>
@endsection
