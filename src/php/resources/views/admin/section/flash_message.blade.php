<!-- Form validation error message -->
@if(count($errors->all()))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Validation Failed</strong>
        <ul>
            @foreach ($errors->all() as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session()->has('flash_message'))
    <div class="alert alert-{{ (session('flash_message')['status'] == 'fail') ? 'danger' : 'success' }} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{ session('flash_message')['message'] }}</strong>
        @if(isset(session('flash_message')['error_fields']) && !empty(session('flash_message')['error_fields']))
            <ul>
                @if(is_array(session('flash_message')['error_fields']))
                    @foreach(session('flash_message')['error_fields'] as $key=>$val)
                        @if(is_array(session('flash_message')['error_fields'][$key]))
                            <li>{{ session('flash_message')['error_fields'][$key][0] }}</li>
                        @else
                            <li>{{ session('flash_message')['error_fields'][$key] }}</li>
                        @endif
                    @endforeach
                @else
                    <li>{{ session('flash_message')['error_fields'] }}</li>
                @endif
            </ul>
        @endif
    </div>
@endif
