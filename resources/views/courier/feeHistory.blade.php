@extends('layouts.courier')

@section('pageTitle')
    <?php setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252'); ?>
    <section class="page-title bg-dark">
        <div class="container">
            <div class="page-title-row">
                <div class="page-title-content">
                    <h1 class="text-light">Riwayat Uang Saku</h1>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <ul class="list-group">
        @foreach ($histories as $item)
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">{{ strftime('%A, %d %B %Y', strtotime($item->date)) }}</div>
                    {{ $item->description }}
                </div>
                @if ($item->type == 'add')
                    <span
                        class="badge bg-success rounded-pill">+&nbsp;Rp&nbsp;{{ number_format($item->nominal, 0, '.', ',') }}</span>
                @elseif ($item->type == 'withdraw')
                    <span
                        class="badge bg-danger rounded-pill">-&nbsp;Rp&nbsp;{{ number_format($item->nominal, 0, '.', ',') }}</span>
                @else
                    <span
                        class="badge bg-warning rounded-pill">-&nbsp;Rp&nbsp;{{ number_format($item->nominal, 0, '.', ',') }}</span>
                @endif

            </li>
        @endforeach
    </ul>
@endsection
