<div class="row">

    <!-- Basic Data -->
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Basic Data</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Recipe Category</label>
                    <strong class="form-control">{{config("constants.recipe_categories")[$recipe->category]}}</strong>
                </div>
                <div class="form-group">
                    <label>Recipe Title</label>
                    <strong class="form-control autoHeight">{{$recipe->title}}</strong>
                </div>
                <div class="form-group">
                    <label>Recipe Photo</label>
                    <div>
                        <img src="{{$recipe->image_url['thumb']}}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Details</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Ingredients</label>
                    <strong class="form-control h-auto">{!! nl2br($recipe->ingredients) !!}</strong>
                </div>
                <div class="form-group">
                    <label>Short Description</label>
                    <label class="form-control h-auto">{!! nl2br($recipe->short_desc) !!}</label>
                </div>
                <div class="form-group">
                    <label>Long Description</label>
                    <label class="form-control h-auto">{!! nl2br($recipe->long_desc) !!}</label>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recipe Status</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Status</label>
                    <label class="form-control">
                        @if($recipe['status'] == 'approved')
                            <span class="badge badge-pill badge-success text-uppercase">Approved</span>
                        @elseif($recipe['status'] == 'pending')
                            <span class="badge badge-pill badge-info text-uppercase">Pending</span>
                        @elseif($recipe['status'] == 'rejected')
                            <span class="badge badge-pill badge-danger text-uppercase">Rejected</span>
                        @endif
                    </label>
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
                    <strong class="form-control">@dateTime($recipe->created_at)</strong>
                </div>
                <div class="form-group">
                    <label>Updated At</label>
                    <strong class="form-control">@dateTime($recipe->updated_at)</strong>
                </div>
            </div>
        </div>

    </div>


</div>
