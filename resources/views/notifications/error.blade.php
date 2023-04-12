@if(count($errors) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    </div>
@endif
