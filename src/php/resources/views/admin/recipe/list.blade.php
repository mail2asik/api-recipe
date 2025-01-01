<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-striped dataTable" role="grid">
            <thead>
            <tr role="row">
                <!--<th tabindex="0" class="text-center"><input type="checkbox" id="selectAll"></th>-->
                <th tabindex="0">Title</th>
                <th tabindex="0">Posted By</th>
                <th tabindex="0" class="text-center">Status</th>
                <th tabindex="0" class="text-center">Updated At</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($data))
                @foreach($data as $recipe)
                    <tr>
                        <td>
                            <a href="javascript:loadRecipe('{{$recipe['uid']}}');">{{ ucfirst($recipe['title']) }}</a>
                        </td>
                        <td>{{ $recipe['email'] }}</td>
                        <td class="text-center">
                            @if($recipe['status'] == 'approved')
                                <span class="badge badge-pill badge-success text-uppercase">Approved</span>
                            @elseif($recipe['status'] == 'pending')
                                <span class="badge badge-pill badge-warning text-uppercase">Pending</span>
                            @elseif($recipe['status'] == 'rejected')
                                <span class="badge badge-pill badge-danger text-uppercase">Rejected</span>
                            @endif
                        </td>
                        <td class="text-center">@dateTime($recipe['updated_at'])</td>
                        <td class="text-center">
                            <a href="javascript:;" onclick="loadRecipe('{{$recipe['uid']}}')">
                                <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button>
                            </a>
                            @if($recipe['status'] == 'approved')
                                <a href="{{ secure_url('recipe/reject', ['recipe_uid' => $recipe['uid']]) }}" class="btn-link">
                                    <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></button>
                                </a>
                            @elseif($recipe['status'] == 'suspended' || $recipe['status'] == 'pending')
                                <a href="{{ secure_url('recipe/approve', ['recipe_uid' => $recipe['uid']]) }}" class="btn-link">
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
