@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4">Kategorijas tabula</h1>

    <a href="/data/createKategorija">
        <button class="btn btn-success btn-sm">Pievienot</button>
    </a>
    <br><br>

    @if($kategorijas->isEmpty())
        <p>Datu nav.</p>
    @else
        @php
            $headers = array_keys($kategorijas->first()->toArray());
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
                @foreach($kategorijas as $item)
                    <tr>
                        @foreach($headers as $field)
                            <td>{{ $item->$field }}</td>
                        @endforeach
                        <td>
                            <a href="/data/all/{{$item->kategorija_id}}/deleteKategorija">
                                <button class="btn btn-danger btn-sm">Dzēst</button>
                            </a>

                            <a href="/data/all/{{$item->kategorija_id}}/showKategorijaDetails">
                                <button class="btn btn-success btn-sm">Informācija</button>
                            </a>

                            <a href="/data/editKategorija/{{$item->kategorija_id}}">
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
