@extends('layouts.front')
@section('content')
    <div class="section-element-area ptb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header text-center mb-20">
                        <img src="{{ asset('/img/3.jpg') }}" alt="not-found-img" />
                        <p>Wups, halaman yang anda tuju tidak ditemukan.</p>
                    </div>
                    <div class="entry-content text-center mb-30">
                        <p>Maaf, halaman yang anda tuju tidak ditemukan, mohon periksa lagi URL nya, pastikan benar.</p>
                        <a href="{{ route('welcome') }}">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
