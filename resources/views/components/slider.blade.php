
<div class="slider-area">
    <div class="slider-active owl-carousel owl-theme">
        @foreach ($slider as $item)
            <div class="single-slider pt-125 pb-130 bg-img"
                style="background-image:url({{ MyHelper::get_uploaded_file_url($item->thumbnail) }});">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="slider-content slider-animated-1 text-center">
                                <h2 style="font-weight: 700;">{{ $item->title }}</h2>
                                <h3>{{ $item->subtitle }}</h3>
                                @if ($item->button_text != '' || $item->button_text != null)
                                    <a href="{{ $item->link }}" target="_blank" style="font-weight: 700">{{ $item->button_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
