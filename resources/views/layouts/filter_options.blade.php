<div class="section-title-5 mb-30">
    <h2>{{ __('messages.filter') }}</h2>
</div>
<div class="left-title mb-20">
    <h4>{{ __('messages.label_category') }}</h4>
</div>
<div class="left-menu mb-30">
    <ul>
        @foreach ($categories as $item)
            <li>
                <a href="{{ route('front.shopping.shopping_category', $item->slug) }}">{{ $item->name }}<span>({{ $item->products->count() }})</span></a>
            </li>
        @endforeach
    </ul>
</div>


<div class="left-title mb-20">
    <h4>{{ __('messages.label_price') }}</h4>
</div>
<div class="left-menu mb-30">
    <ul>
        <li><a href="{{ route('front.shopping.price_range', [10000, 50000]) }}">Rp 10.000 - Rp 50.000</a></li>
        <li><a href="{{ route('front.shopping.price_range', [50000, 100000]) }}">Rp 50.000 - Rp 100.000</a></li>
        <li><a href="{{ route('front.shopping.price_range', [100000, 500000]) }}">Rp 100.000 - Rp 500.000</a></li>
        <li><a href="{{ route('front.shopping.price_range', [500000, 9999999]) }}">{{ __('messages.label_more_than') }} Rp 500.000</a></li>
    </ul>
</div>

