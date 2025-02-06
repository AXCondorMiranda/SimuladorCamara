@extends('layouts.app')

@section('content')
    <div class="portada">
        <div class="port_1">
            <div class="port_1_container">
                <h1>Temario</h1><br>
                <h2>Descarga el banco de preguntas</h2>
            </div>
        </div>

        <div class="temario">
            <div class="temario_oficiales">
                <div>
                    <h1>Oficiales</h1>
                </div>
                <div class="temario_descargas">
                    <div class="container_tem">
                        <h2>Oficiales Subalternos</h2>
                        <a target="_blank" href="{{ asset('images/OFICIALES_SUBALTERNO_ARMAS.pdf') }}">
                            <div class="boton_descarga"><img src="{{ asset('img/img_temario/icon_descargar.png') }}" alt="">
                            </div>
                        </a>
                    </div>
                    <div class="container_tem">
                        <h2>Oficiales Superiores</h2>
                        <a target="_blank" href="{{ asset('images/OFICIALES_SUPERIORES_ARMAS.pdf') }}">
                            <div class="boton_descarga"><img src="{{ asset('img/img_temario/icon_descargar.png') }}" alt="">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="temario_centro">
                <img src="{{ asset('img/img_temario/icon_c_tem.png') }}" alt="">
            </div>
            <div class="temario_suboficiales">
                <div>
                    <h1>Suboficiales</h1>
                </div>
                <div class="temario_descargas">
                    <div class="container_tem">
                        <h2>Suboficiales de armas</h2>
                        <a target="_blank" href="{{ asset('images/SUBOFICIALES_DE_ARMAS.pdf') }}">
                            <div class="boton_descarga"><img src="{{ asset('img/img_temario/icon_descargar.png') }}" alt="">
                            </div>
                        </a>
                    </div>
                    <div class="container_tem">
                        <h2>Suboficiales de servicios</h2>
                        <a target="_blank" href="{{ asset('images/SUBOFICIALES_DE_SERVICIO.pdf') }}">
                            <div class="boton_descarga"><img src="{{ asset('img/img_temario/icon_descargar.png') }}" alt="">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
