@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4">Ieraksti tabula</h1>

    <a href="/data/createIeraksts">
        <button class="btn btn-success btn-sm">Pievienot</button>
    </a>
    <br><br>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $kluda)
            {{ $kluda }} <br><br>
        @endforeach
    @endif

    @if($ieraksti->isEmpty())
        <p>Datu nav.</p>
    @else
        @php
            $headers = array_keys($ieraksti->first()->toArray());
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
                @foreach($ieraksti as $item)
                    <tr>
                        @foreach($headers as $field)
                            <td>{{ $item->$field }}</td>
                        @endforeach
                        <td>
                            <a href="/data/all/{{$item->ieraksts_id}}/deleteIeraksts">
                                <button class="btn btn-danger btn-sm">Dzēst</button>
                            </a>

                            <a href="/data/all/{{$item->ieraksts_id}}/showIerakstsDetails">
                                <button class="btn btn-success btn-sm">Informācija</button>
                            </a>

                            <a href="/data/editIeraksts/{{$item->ieraksts_id}}">
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
