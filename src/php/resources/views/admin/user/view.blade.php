<div class="row">

    <!-- Basic Data -->
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Basic Data</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>User Name</label>
                    <strong class="form-control">{{$user->name}}</strong>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <label class="form-control">{{$user->email}}</label>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Account Status</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Status</label>
                    <label class="form-control">
                        @if($user['status'] == 'approved')
                            <span class="badge badge-pill badge-success text-uppercase">Approved</span>
                        @elseif($user['status'] == 'pending')
                            <span class="badge badge-pill badge-info text-uppercase">Pending</span>
                        @elseif($user['status'] == 'disapproved')
                            <span class="badge badge-pill badge-warning text-uppercase">Disapproved</span>
                        @elseif($user['status'] == 'suspended')
                            <span class="badge badge-pill badge-danger text-uppercase">Suspended</span>
                        @endif
                    </label>
                </div>
                <div class="form-group">
                    <label>Verified At</label>
                    <strong class="form-control">
                        @if(!empty($user->email_verified_at))
                            <i class="fa fa-check"></i>
                        @else
                            <i class="fa fa-close"></i>
                        @endif
                    </strong>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Log</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Created At</label>
                    <strong class="form-control">@dateTime($user->created_at)</strong>
                </div>
                <div class="form-group">
                    <label>Updated At</label>
                    <strong class="form-control">@dateTime($user->updated_at)</strong>
                </div>
            </div>
        </div>

    </div>


</div>
