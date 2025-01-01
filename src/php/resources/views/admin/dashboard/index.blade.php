@extends('admin.admin_template')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $page_title }}</h1>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Users (<label class="text-success">Approved</label> / <label class="text-warning">Pending</label> / <label class="text-danger">Suspended</label>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <label class="text-success">{{ $users['approved'] }}</label> /
                                <label class="text-warning">{{ $users['pending'] }}</label> /
                                <label class="text-danger">{{ $users['suspended'] }}</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Recipes (<label class="text-warning">Pending Approval</label>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <label class="text-warning">{{ $recipes['pending'] }}</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="text-warning fas fa-file-invoice fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Recipes (<label class="text-success">Approved</label>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <label class="text-success">{{ $recipes['approved'] }}</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="text-success fas fa-file-invoice fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Recipes (<label class="text-danger">Rejected</label>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <label class="text-danger">{{ $recipes['rejected'] }}</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="text-danger fas fa-file-invoice fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Users</h6>
                </div>
                <div class="card-body">
                    <canvas id="users_statistics" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ secure_asset("admin/vendor/chart.js/Chart.bundle.js") }}"></script>

    <script>
      var options = {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        },
        title: {
          display: false,
              text: 'Statistics'
        },
        legend: false,
            responsive: true
      };

      // Users
      new Chart(document.getElementById("users_statistics"), {
        type: 'line',
        data: {
          labels: <?php echo json_encode($users['users_statistics']['labels']) ?>,
          datasets: [{
            data: <?php echo json_encode($users['users_statistics']['data']) ?>,
            label: "Users",
            fill: true,
            borderColor: '#4E73DF'
          }
          ]
        },
        options: options
      });
    </script>
@endsection
