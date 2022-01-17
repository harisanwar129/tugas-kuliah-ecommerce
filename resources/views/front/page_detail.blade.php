@extends('layouts.front')

@section('content')
    <div class="blog-main-area mt-70 mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 order-lg-2 order-1">
                    <div class="blog-main-wrapper">
                        <div class="single-blog-content">
                            <div class="single-blog-title">
                                <h3>{{ $page->nama }}</h3>
                            </div>
                            <div class="blog-single-content">
                                <p>{!! $page->konten !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
