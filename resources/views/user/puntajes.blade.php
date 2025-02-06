@extends('layouts.app')

@section('content')

    <div class="portada">
        <div class="port_1">
            <div class="port_1_container">
                <h1>Puntajes</h1><br>
                <h2>Revisa tus respuestas</h2>
            </div>
            <div class="container_table">
                <div class="table-wrapper">
                    <table>
                        <thead>
                        <tr>
                            <th style="width: 50%">Examen</th>
                            <th>Total preguntas</th>
                            <th>Total correctas</th>
                            <th>Total incorrectas</th>
                            <th>Puntaje</th>
                            <th>Fecha de examen</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($result as $res)
                            <tr>
                                <td style="width: 50%">{{ $res->test->name }}</td>
                                <td>{{ $res->total_marked }}</td>
                                <td>{{ $res->total_correct }}</td>
                                <td>{{ $res->total_incorrect }}</td>
                                <td>{{ $res->total_correct*1}}</td>
                                <td>{{ $res->duration}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
