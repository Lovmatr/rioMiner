@php use App\Enums\TxnStatus; @endphp
@extends('frontend::layouts.user')
@section('title')
{{ __('Schema Logs') }}
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12 desktop-screen-show">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('All Invested Schemas') }}</h3>
            </div>
            <div class="site-card-body">
                <div class="site-table">
                    <div class="table-filter">
                        <div class="filter">
                            <form action="{{ route('user.invest-logs') }}" method="get">
                                <div class="search">
                                    <input type="text" id="search" placeholder="Search" value="{{ request('query') }}"
                                        name="query" />
                                    <input type="date" name="date" value="{{ request()->get('date') }}" />
                                    <button type="submit" class="apply-btn"><i
                                            icon-name="search"></i>{{ __('Search') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @php
                    $logs = $data->when(request('query'),function($query){
                    $query->whereHas('schema',function($schemaQuery){
                    $schemaQuery->where('name','LIKE','%'.request('query').'%');
                    });
                    })->paginate(request()->integer('limit',15))->withQueryString();
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>{{ __('Schema') }}</th>
                                    <th>{{ __('ROI') }}</th>
                                    <th>{{ __('Period Remaining') }}</th>
                                    <th>{{ __('Profit') }}</th>
                                    <th>{{ __('Capital Back') }}</th>
                                    <th>{{ __('Timeline') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $invest)
                                <tr>
                                    <td>
                                        <img class="schema-icon" src="{{asset($invest->schema->icon)}} " alt="">
                                    </td>
                                    <td>
                                        <div class="content">
                                            <h6 class="title  gradient-text-1 fw-7">{{ $invest->schema->name }} >
                                                {{ $currencySymbol.$invest->invest_amount }}</h6>
                                            <p class="description">{{ $invest->created_at }}</p>
                                        </div>
                                    </td>
                                    <td><strong>{{ $invest->interest_type == 'percentage' ? $invest->interest.'%' : $currencySymbol.$invest->interest }}</strong>
                                    </td>
                                    <td><strong>{{ $invest->return_type != 'period' ? __('Unlimited') : $invest->number_of_period . ($invest->number_of_period < 2 ? ' Time' : ' Times') }}</strong>
                                    </td>

                                    @php
                                    $calculateInterest = ($invest->interest*$invest->invest_amount)/100;
                                    $interest = $invest->interest_type != 'percentage' ? $invest->interest :
                                    $calculateInterest;
                                    @endphp
                                    <td><strong
                                            class="">{{ $invest->already_return_profit .' x '.$interest .' = '. ($invest->already_return_profit*$interest).' '. $currency }}</strong>
                                    </td>
                                    <td>
                                        <div class="site-badge primary-bg">
                                            {{ $invest->capital_back ? 'Yes' : 'No' }}
                                        </div>
                                    </td>
                                    <td><strong>@if($invest->status->value == 'ongoing')
                                            <div class="timeline-grid">
                                                <span class="white-text">
                                                    <span id="days{{ $invest->id }}"></span>D : <span
                                                        id="hours{{ $invest->id }}"></span>H : <span
                                                        id="minutes{{ $invest->id }}"></span>M : <span
                                                        id="seconds{{ $invest->id }}"></span>S
                                                </span>
                                                <div class="single-progress">
                                                    <div class="progress">
                                                        <div class="progress-bar" id="time-progress{{ $invest->id }}"
                                                            role="progressbar" style="width: 100%;" aria-valuenow="47"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="white-text" id="percent-text{{ $invest->id }}">100%</span>
                                            </div>
                                            <script>
                                                (function ($) {
                                                    "use strict";
                                                    // Countdown
                                                    const second = 1000,
                                                        minute = second * 60,
                                                        hour = minute * 60,
                                                        day = hour * 24;
                                                    let timezone = @json(setting('site_timezone', 'global'));


                                                    let countDown = new Date('{{$invest->next_profit_time}}')
                                                        .getTime()
                                                    var start = new Date(
                                                            '{{ $invest->last_profit_time ?? $invest->created_at}}')
                                                        .getTime()
                                                    setInterval(function () {

                                                        let utc_datetime_str = new Date().toLocaleString(
                                                            "en-US", {
                                                                timeZone: timezone
                                                            });
                                                        let now = new Date(utc_datetime_str).getTime();

                                                        let distance = countDown - now;


                                                        var progress = (((now - start) / (countDown -
                                                            start)) * 100).toFixed(2);


                                                        $("#time-progress{{ $invest->id }}").css("width",
                                                            progress + '%');

                                                        $("#percentage{{ $invest->id }}").text(progress >=
                                                            100 ? 100 + '%' : progress + '%');
                                                        $('#percent-text{{ $invest->id }}').text(progress >=
                                                            100 ? 100 + '%' : progress + '%')

                                                        document.getElementById('days{{ $invest->id }}')
                                                            .innerText = Math.floor(distance < 0 ? 0 :
                                                                distance / (day)),
                                                            document.getElementById(
                                                                'hours{{ $invest->id }}').innerText = Math
                                                            .floor(distance < 0 ? 0 : (distance % (day)) / (
                                                                hour)),
                                                            document.getElementById(
                                                                'minutes{{ $invest->id }}').innerText = Math
                                                            .floor(distance < 0 ? 0 : (distance % (hour)) /
                                                                (minute)),
                                                            document.getElementById(
                                                                'seconds{{ $invest->id }}').innerText = Math
                                                            .floor(distance < 0 ? 0 : (distance % (
                                                                minute)) / second);

                                                    }, second)

                                                })(jQuery)

                                            </script>
                                            @elseif($invest->status->value == 'pending')
                                            <span class="site-badge warnning">{{ __('Pending') }}</span>
                                            @elseif($invest->status->value == 'completed')
                                            <div class="d-flex gap-10">
                                                <span class="site-badge success">{{ __('Success') }}</span>
                                            </div>
                                            @else
                                            <span class="site-badge primary-bg">{{ __('Cancelled') }}</span>
                                            @endif
                                        </strong>
                                    </td>
                                    <td>
                                    @if($invest->schema->schema_cancel &&
                                             ($invest->status->value !== 'canceled') &&
                                             now()->diffInMinutes($invest->created_at) <= $invest->schema->expiry_minute)
                                        <form action="{{ route('user.invest-cancel', $invest->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="site-badge primary-bg">{{ __('Cancel') }}</button>
                                        </form>
                                    @else
                                        --
                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if(count($logs) == 0)
                            <div class="alert alert-table mt-20 text-center" role="alert">
                                {{ __('No Data Found') }}
                            </div>
                            @endif
                        </table>
                        {{ $logs->links('frontend::include.__pagination') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-12 mobile-screen-show">
        <div class="all-feature-mobile mobile-transactions mb-3">
            <div class="title">{{ __('All Schemas') }}</div>
            <div class="mobile-transaction-filter">
                <div class="filter">
                    <form action="{{ route('user.invest-logs') }}" method="get">
                        <div class="search">

                            <input type="text" placeholder="Search" value="{{ request('query') }}" name="query" />
                            <input type="date" name="date" value="{{ request()->get('date') }}" />
                            <button type="submit" class="apply-btn"><i icon-name="search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="contents">
                @foreach($logs as $invest)
                <div class="single-transaction">
                    <div class="transaction-left">
                        <div class="transaction-des">
                            <h4 class="title gradient-text-1 fw-7">{{ $invest->schema->name }} >>
                                {{ $currencySymbol.$invest->invest_amount }}</h4>
                            <p class="description">{{ $invest->created_at }}</p>
                        </div>
                    </div>
                    <div class="transaction-right">
                        <div class="transaction-amount ">
                            {{ $invest->interest_type == 'percentage' ? $invest->interest.'%' : $currencySymbol.$invest->interest }}
                        </div>
                        <div class="transaction-fee sub">
                            @php
                            $calculateInterest = ($invest->interest*$invest->invest_amount)/100;
                            $interest = $invest->interest_type != 'percentage' ? $invest->interest : $calculateInterest;
                            @endphp
                        </div>
                        <div class="transaction-gateway">
                            {{ $invest->already_return_profit .' x '.$interest .' = '. ($invest->already_return_profit*$interest).' '. $currency }}
                        </div>
                        <div class="site-table-col">
                            <span
                                class="white-text">{{ $invest->return_type != 'period' ? __('Unlimited') : $invest->number_of_period . ($invest->number_of_period < 2 ? ' Time' : ' Times') }}</span>
                        </div>
                        <div class="site-table-col">
                            <span class="success-text">{{ $invest->capital_back ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="site-table-col">
                            @if($invest->status->value == 'ongoing')
                            <div class="timeline-grid">
                                <span class="white-text">
                                    <span id="mobile-days{{ $invest->id }}"></span>D : <span
                                        id="mobile-hours{{ $invest->id }}"></span>H : <span
                                        id="mobile-minutes{{ $invest->id }}"></span>M : <span
                                        id="mobile-seconds{{ $invest->id }}"></span>S
                                </span>
                                <div class="single-progress">
                                    <div class="progress">
                                        <div class="progress-bar" id="mobile-time-progress{{ $invest->id }}" role="progressbar"
                                            style="width: 100%;" aria-valuenow="47" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                                <span class="white-text" id="mobile-percent-text{{ $invest->id }}">100%</span>
                            </div>
                            <script>
                                (function ($) {
                                    "use strict";
                                    // Countdown
                                    const second = 1000,
                                        minute = second * 60,
                                        hour = minute * 60,
                                        day = hour * 24;
                                    let timezone = @json(setting('site_timezone', 'global'));


                                    let countDown = new Date('{{$invest->next_profit_time}}').getTime()
                                    var start = new Date('{{ $invest->last_profit_time ?? $invest->created_at}}')
                                        .getTime()
                                    setInterval(function () {

                                        let utc_datetime_str = new Date().toLocaleString("en-US", {
                                            timeZone: timezone
                                        });
                                        let now = new Date(utc_datetime_str).getTime();

                                        let distance = countDown - now;


                                        var progress = (((now - start) / (countDown - start)) * 100)
                                            .toFixed(2);


                                        $("#mobile-time-progress{{ $invest->id }}").css("width", progress + '%');

                                        $("#mobile-percentage{{ $invest->id }}").text(progress >= 100 ? 100 + '%' :
                                            progress + '%');
                                        $('#mobile-percent-text{{ $invest->id }}').text(progress >= 100 ? 100 +
                                            '%' : progress + '%')

                                        document.getElementById('mobile-days{{ $invest->id }}').innerText = Math
                                            .floor(distance < 0 ? 0 : distance / (day)),
                                            document.getElementById('mobile-hours{{ $invest->id }}').innerText =
                                            Math.floor(distance < 0 ? 0 : (distance % (day)) / (hour)),
                                            document.getElementById('mobile-minutes{{ $invest->id }}').innerText =
                                            Math.floor(distance < 0 ? 0 : (distance % (hour)) / (minute)),
                                            document.getElementById('mobile-seconds{{ $invest->id }}').innerText =
                                            Math.floor(distance < 0 ? 0 : (distance % (minute)) / second);

                                    }, second)

                                })(jQuery)

                            </script>
                            @elseif($invest->status->value == 'pending')
                            <span class="site-badge warnning">{{ __('Pending') }}</span>
                            @elseif($invest->status->value == 'completed')
                            <span class="site-badge success">{{ __('Success') }}</span>
                            @else
                            <span class="site-badge primary-bg">{{ __('Cancelled') }}</span>
                            @endif
                        </div>
                        <div>
                            @if($invest->schema->schema_cancel &&
                                             ($invest->status->value !== 'canceled') &&
                                             now()->diffInMinutes($invest->created_at) <= $invest->schema->expiry_minute)
                                <form action="{{ route('user.invest-cancel', $invest->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="site-badge primary-bg">{{ __('Cancel') }}</button>
                                </form>
                            @else
                                --
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
