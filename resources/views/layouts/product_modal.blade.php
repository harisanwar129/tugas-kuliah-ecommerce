<!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">x</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <div class="modal-tab">
                                <div class="product-details-large tab-content" id="productDetailImages">
                                    {{-- dynamic content --}}
                                </div>
                                <div class="product-details-small quickview-active owl-carousel" id="productDetailCarouselImages">
                                    {{-- dynamic_content --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <div class="modal-pro-content">
                                <h3 id="productName">productName</h3>
                                <div class="price" id="productPrice">
                                    <span>productPrice</span>
                                    <span class="old-price">productPrice</span>
                                </div>
                                <p id="productDescription">productDescription</p>
                                <form action="" id="product_form" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="formProductId" id="formProductId" value="">
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="99" />
                                    <button id="add_to_cart">{{ __('messages.add_to_cart') }}</button>
                                </form>
                                <p class="pt-2">Berat: <span id="productWeight"></span> Gram</p>
                                <span id="stock_status"><i class="fa fa-check"></i> {{ __('messages.stock') }}: 10</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->
