@extends('adminlte::page')

@section('title', 'Jornadas')

@section('content')


    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="jornada-header">
        <h1> 🗓️ Jornadas registradas</h1>
    </div><br>

    <div id="lista-jornadas">
        @foreach ($jornadas as $jornada)
            <div class="card mb-2">
                <div class="card-header">
                    <strong>Jornada {{ $jornada->numero }}</strong> – {{ $jornada->fecha }} – 💰 {{ $jornada->premio }}
                </div>
                <div class="card-body">
                <a href="{{ route('jornada.show.numero', $jornada->numero) }}" class="btn btn-info">🔍 Ver / Editar</a>


                   <form id="form-cierre-{{ $jornada->numero}}" action="{{ route('jornadas.cerrar', $jornada->numero) }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>

                    <button onclick="confirmarBorrado({{ $jornada->numero }})" class="btn btn-danger btn-sm">🗑️
                        Borrar</button>

                    <form id="form-borrado-{{ $jornada->numero }}" action="{{ route('jornada.destroy', $jornada->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        @endforeach
    </div>


    <script>
        

        function confirmarBorrado(id) {
            if (confirm('¿Seguro que quieres borrar esta jornada? Esta acción no se puede deshacer.')) {
                document.getElementById('form-borrado-' + id).submit();
            }
        }
    </script>

    

@stop
