<div class="container col-10">
    @if ($errors->any())
    <br>
        <div class="alert alert-danger">
          <div class="panel-heading">Errores</div>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
