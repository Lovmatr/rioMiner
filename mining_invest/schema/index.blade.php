@extends('frontend::layouts.user')
@section('title')
    {{ __('All Schema') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('All The Schemas') }}</h3>
                </div>
                <div class="site-card-body">
                    <div class="row">
                        @foreach($schemas as $schema)

                            <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="single-investment-plan">
                                    <img
                                        class="investment-plan-icon"
                                        src="{{ asset($schema->icon) }}"
                                        alt=""
                                    />
                                    @if($schema->badge)
                                        <div class="feature-plan">{{$schema->badge}}</div>
                                    @endif

                                    <h3>{{$schema->name}}</h3>
                                    @if($schema->interest_type == 'fixed')
                                        <p>{{ $schema->schedule->name . ' ' . $schema->fixed_roi .' '. ($schema->roi_interest_type == 'percentage' ? '%' : '$') }}</p>

                                    @else
                                        <p>{{ $schema->schedule->name . ' ' . $schema->min_roi . '-' . $schema->max_roi .' '. ($schema->roi_interest_type == 'percentage' ? '%' : '$') }}</p>
                                    @endif
                                    <ul>
                                        <li>{{ __('Investment') }} <span class="special">
                                            {{ $schema->type == 'range' ? $currencySymbol . $schema->min_amount . '-' . $currencySymbol . $schema->max_amount : $currencySymbol . $schema->fixed_amount }}
                                        </span></li>
                                        <li>{{ __('Capital Back') }}
                                            <span>{{ $schema->capital_back ? 'Yes' : 'No' }}</span></li>
                                        <li>{{ __('Return Type') }} <span>{{ ucwords($schema->return_type) }}</span>
                                        </li>
                                        <li>{{ __('Number of Period') }}
                                            <span>{{ ($schema->return_type == 'period' ? $schema->number_of_period : 'Unlimited').($schema->number_of_period == 1 ? ' Time' : ' Times' )  }}</span>
                                        </li>
                                        <li>{{ __('Profit Withdraw') }} <span>{{ __('Anytime') }}</span></li>
                                        <li>{{ __('Cancel') }} <span> @if($schema->schema_cancel)
                                                    {{ __('Within').' '. $schema->expiry_minute .' '. 'Minute' }}
                                                @else
                                                    {{ __('No') }}
                                                @endif</span></li>
                                    </ul>
                                    <div class="holidays"><span class="star">*</span>@if( null != $schema->off_days)
                                            {{ implode(', ', json_decode($schema->off_days,true))  .' '.__('are')}}
                                        @else
                                            {{ __('No Profit') }}
                                        @endif {{ __('Holidays') }}</div>
                                    <a href="{{route('user.schema.preview',$schema->id)}}"
                                       class="site-btn grad-btn w-100 centered"><i
                                            class="anticon anticon-check"></i>{{ __('Invest Now') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
