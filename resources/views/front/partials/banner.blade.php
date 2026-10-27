@if(isset($banners) && $banners->count() > 0)
    <div class="container pt-3 pb-2">
        <div class="swiper homeBannerSwiper rounded-4 overflow-hidden shadow-sm">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide position-relative">
                        @php
                            $imgSrc = str_starts_with($banner->image, 'assets/') ? asset($banner->image) : asset('storage/' . $banner->image);
                        @endphp
                        <div class="banner-slide-wrapper position-relative" style="height: 340px;">
                            <img src="{{ $imgSrc }}" class="w-100 h-100 object-fit-cover" alt="Banner">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 d-flex align-items-center">
                                <div class="container text-white px-4 px-md-5">
                                    <h2 class="fw-bold mb-2 text-shadow">{{ $banner->{'title_' . app()->getLocale()} ?? $banner->title_tk }}</h2>
                                    @if($banner->link)
                                        <a href="{{ $banner->link }}" target="_blank" class="btn btn-primary btn-sm px-4 fw-semibold mt-2 shadow-sm">
                                            {{ __('Details') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next text-white"></div>
            <div class="swiper-button-prev text-white"></div>
        </div>
    </div>
@endif