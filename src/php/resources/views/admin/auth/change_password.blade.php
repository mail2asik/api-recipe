@extends('admin.admin_template')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $page_title }}</h6>
                </div>
                <div class="card-body">
                    @include('admin.section.flash_message')
                    <form autocomplete="off" class="form-validation" id="change_password_form" role="form" method="post" action="{{ secure_url('/doChangePassword') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <label for="pwd">Current Password</label>
                            <input type="password" placeholder="Current Password" name="password" class="form-control" value="" />
                        </div>
                        <div class="form-group">
                            <label for="pwd">New Password</label>
                            <input type="password" placeholder="New Password" name="new_password" id="new_password" class="form-control" value="" />
                        </div>
                        <div class="form-group">
                            <label for="pwd">Confirm Password</label>
                            <input type="password" placeholder="Confirm Password" name="new_password_confirmation" class="form-control" value="" />
                        </div>
                        <div class="text-center  m-t-20">
                            <button type="submit" id="submitBtn" class="btn btn-embossed btn-primary" data-style="expand-left">Submit</button>
                            <a href=" {{ secure_url('/') }}">
                                <button type="button" class="cancel btn btn-embossed btn-default m-b-10 m-r-0">Cancel</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- jQuery validation v1.14.0 -->
    <script src="{{ secure_asset("admin/vendor/jquery-validation/jquery.validate.min.js") }}"></script>
    <!-- jQuery validation v1.14.0 additional methods -->
    <script src="{{ secure_asset("admin/vendor/jquery-validation/additional-methods.min.js") }}"></script>
    <script>
        $(document).ready(function(){

            var form = $("#change_password_form");
            $('#submitBtn').click(function(e) {
                form.validate({
                    errorClass: 'validationError',
                    rules: {
                        password: {
                            required: true,
                            minlength: 6
                        },
                        new_password: {
                            required: true,
                            minlength: 6
                        },
                        new_password_confirmation: {
                            required: true,
                            minlength: 6,
                            equalTo: "#new_password"
                        }
                    },
                    messages: {
                        password: {
                            required: "Please enter password",
                            minlength: jQuery.validator.format("Please enter correct password")
                        },
                        new_password: {
                            required: "Please enter new password",
                            minlength: jQuery.validator.format("Enter at least {0} characters")
                        },
                        new_password_confirmation: {
                            required: "Please enter re-password",
                            minlength: jQuery.validator.format("Enter at least {0} characters"),
                            equalTo: "Enter the same password as above"
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element);
                    }
                });
                e.preventDefault();
                if (form.valid()) {
                    $(this).addClass('ladda-button');
                    var l = Ladda.create(this);
                    l.start();

                    $(this).attr('disabled','disabled');
                    form.submit();
                }
            });
        });
    </script>
@endsection
