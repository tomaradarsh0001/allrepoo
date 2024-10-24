@if(session('status'))
<div class="alert alert-success">
    <h3>{{session('status')}}</h3>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if(session('status') || $errors->any() )
<script>
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
                document.querySelectorAll('.alert, .error').forEach(element => {
                element.classList.add('d-none');
            })
        },5000);
    })
</script>
@endif