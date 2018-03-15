@if ($errors->any())
    <ul class="errors">
        @foreach ($errors->all() as $error)
            <li class="text-danger">{{ $error }}</li>
        @endforeach
    </ul>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif
@if (session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
@endif
@if (session('danger'))
    <div class="alert alert-danger">
        {{ session('info') }}
    </div>
@endif