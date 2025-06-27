@php
    $landingContent =\App\Models\LandingContent::where('type','counter')->where('locale',app()->getLocale())->get();
@endphp
<section class="section-style-2 grad-bg-6">
    <div class="container">
        <div class="row justify-content-center">

            @foreach($landingContent as $content)
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="single-stat" data-aos="fade-down" data-aos-duration="1000">
                        <img src="{{ asset($content->icon) }}" alt=""/>
                        <h3 class="title">
                            @php
                                $description = $content->description;
                                if ($description >= 1000000000) {
                                    $formattedDescription = round($description / 1000000000, 1) . 'B+';
                                } elseif ($description >= 1000000) {
                                    $formattedDescription = round($description / 1000000, 1) . 'M+';
                                } elseif ($description >= 1000) {
                                    $formattedDescription = round($description / 1000, 1) . 'K+';
                                } else {
                                    $formattedDescription = number_format($description);
                                }
                            @endphp
                            <span class="">{{ $formattedDescription }}</span>
                        </h3>
                        <h4>{{ $content->title }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
