@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4">Lietotāji tabula</h1>

    <a href="/data/createLietotajs">
        <button class="btn btn-success btn-sm">Pievienot</button>
    </a>
    <br><br>

    @if($lietotaji->isEmpty())
        <p>Datu nav.</p>
    @else
        @php
            $headers = array_keys($lietotaji->first()->toArray());
        @endphp

        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    @foreach($headers as $header)
                        <th>{{ ucfirst($header) }}</th>
                    @endforeach
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lietotaji as $item)
                    <tr>
                        @foreach($headers as $field)
                            <td>{{ $item->$field }}</td>
                        @endforeach
                        <td>
                            <a href="/data/all/{{$item->lietotajs_id}}/deleteLietotajs">
                                <button class="btn btn-danger btn-sm">Dzēst</button>
                            </a>
                            <a href="/data/all/{{$item->lietotajs_id}}/showLietotajsDetails">
                                <button class="btn btn-success btn-sm">Informācija</button>
                            </a>
                            <a href="/data/editLietotajs/{{$item->lietotajs_id}}">
                                <button class="btn btn-warning btn-sm">Rediģēt</button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
