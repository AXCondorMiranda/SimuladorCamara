@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="portada">
    <div class="port_1">
        <div class="port_1_container">
            <h1>Te damos la bienvenida</h1><br>
            <h2></h2>
        </div>
    </div>
    <div class="port_2">
        <h3>¡Mucha suerte!</h3>
    </div>
    <div class="port_3">
    <button id="iniciarExamenBtn">Iniciar mi examen</button>

</div>
</div>
<script>
    document.getElementById("iniciarExamenBtn").addEventListener("click", function(event) {
        if (!confirm("¿Estás han seguro de que deseas iniciar el examen?")) {
            event.preventDefault();
        } else {
            window.location.href = "{{ route('user.iniciar.examen') }}";
        }
    });
</script>
<script>
    
    function escribirTextoEnBucle(texto, elemento) {
        let i = 0;

        function escribir() {
            if (i < texto.length) {
                elemento.innerHTML += texto.charAt(i);
                i++;
                setTimeout(escribir, 100);
            } else {
                setTimeout(borrar, 1000);
            }
        }

        function borrar() {
            if (i > 0) {
                elemento.innerHTML = elemento.innerHTML.substring(0, i - 1);
                i--;
                setTimeout(borrar, 80);
            } else {
                setTimeout(escribir, 500);
            }
        }

        escribir();
    }

    document.addEventListener("DOMContentLoaded", function() {
        let elemento = document.querySelector(".port_1_container h2");
        let texto = "Prepárate para el ascenso PNP";
        escribirTextoEnBucle(texto, elemento);
    });
</script>

@endsection
