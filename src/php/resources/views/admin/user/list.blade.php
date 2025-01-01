<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-striped dataTable" role="grid">
            <thead>
            <tr role="row">
                <!--<th tabindex="0" class="text-center"><input type="checkbox" id="selectAll"></th>-->
                <th tabindex="0">Name</th>
                <th tabindex="0">Email</th>
                <th tabindex="0" class="text-center">Activation</th>
                <th tabindex="0" class="text-center">Approval</th>
                <th tabindex="0" class="text-center">Created At</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($data))
                @foreach($data as $user)
                    <tr>
                    <!--
                        <td class="text-center">
                            <input type="checkbox" name="user_ids[]" value="{{-- $user['id'] --}}">
                        </td>
                        -->
                        <td>
                            <a href="javascript:loadUser('{{$user['uid']}}');">{{ ucfirst($user['name']) }}</a>
                        </td>
                        <td>{{ $user['email'] }}</td>
                        <td class="text-center">
                            @if(!empty($user['email_verified_at']))
                                <i class="fa fa-check"></i>
                            @else
                                <i class="fa fa-close"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($user['status'] == 'approved')
                                <span class="badge badge-pill badge-success text-uppercase">Approved</span>
                            @elseif($user['status'] == 'pending')
                                <span class="badge badge-pill badge-info text-uppercase">Pending</span>
                            @elseif($user['status'] == 'disapproved')
                                <span class="badge badge-pill badge-warning text-uppercase">Disapproved</span>
                            @elseif($user['status'] == 'suspended')
                                <span class="badge badge-pill badge-danger text-uppercase">Suspended</span>
                            @endif
                        </td>
                        <td class="text-center">@dateTime($user['created_at'])</td>
                        <td class="text-center">
                            <a href="javascript:;" onclick="loadUser('{{$user['uid']}}')">
                                <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                            </a>
                            @if($user['status'] == 'approved')
                                <a href="{{ secure_url('user/suspend', ['user_uid' => $user['uid']]) }}" class="btn-link">
                                    <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></button>
                                </a>
                            @elseif($user['status'] == 'suspended')
                                <a href="{{ secure_url('user/approve', ['user_uid' => $user['uid']]) }}" class="btn-link">
                                    <button type="button" class="btn btn-sm btn-success"><i class="fas fa-thumbs-up"></i></button>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">No records found.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{ $from }}
            to {{ $to }} of
            {{ $total }} entries
        </div>
    </div>
    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers float-right">
            @if (!empty($data))
                {!! $paginator->render() !!}
            @endif
        </div>
    </div>
</div>
