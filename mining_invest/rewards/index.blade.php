@extends('frontend::layouts.user')
@section('title')
    {{ __('Rewards') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-12 col-md-12 col-12">
            <!-- New My Reward Points Card -->
            <div class="site-card rewards-card" style="border-radius: 12px; overflow: hidden;">
                <div class="site-card-header">
                    <h4 class="title">{{ __('My Reward Points') }}</h4>
                </div>
                <div class="site-card-body text-center">
                    <h3 class="acc-balance">{{ auth()->user()->points }}</h3>
                    <a href="{{ route('user.rewards.redeem.now') }}" class="site-btn polis-btn mt-3">
                        <i data-lucide="gift"></i> {{ __('Redeem Now') }}
                    </a>
                    @if($myRanking)
                        <div class="last-login mt-3">{{ __("Every :points reward points are :amount", ['points' => $myRanking->point, 'amount' => $currencySymbol.$myRanking->amount]) }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4 offset-xl-4 col-lg-12 col-md-12 col-12">
            <img src="{{ asset('front/images/reward.png') }}" alt="" class="rounded">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="site-card" style="border-radius: 12px; overflow: hidden;">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Reward Point Earnings:') }}</h4>
                </div>
                <div class="site-card-body p-0 table-responsive" style="border-radius: 12px;">
                    <table class="display data-table site-custom-table site-custom-table-sm" style="width: 100%; border-collapse: collapse; border-radius: 12px; overflow: hidden;">
                        <thead>
                        <tr style="background-color: transparent; color: white;">
                            <th style="padding: 12px; text-align: left; width: 33%">{{ __('Ranking List') }}</th>
                            <th style="padding: 12px; text-align: left; width: 33%">{{ __('Amount Of Transactions') }}</th>
                            <th style="padding: 12px; text-align: left; width: 33%">{{ __('Reward') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($earnings as $earning)
                            <tr style="background-color: transparent; color: white;">
                                <td style="padding: 12px; text-align: left;">{{ $earning->ranking->ranking }}</td>
                                <td style="padding: 12px; text-align: left;">{{ $earning->amount_of_transactions.' '.$currency }}</td>
                                <td style="padding: 12px; text-align: left;">{{ $earning->point }} {{ __('Points') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 12px; color: white;">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="site-card" style="border-radius: 12px; overflow: hidden;">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Reward Points Redeem:') }}</h4>
                </div>
                <div class="site-card-body p-0 table-responsive" style="border-radius: 12px;">
                    <table class="display data-table site-custom-table site-custom-table-sm" style="width: 100%; border-collapse: collapse; border-radius: 12px; overflow: hidden;">
                        <thead>
                        <tr style="background-color: transparent; color: white;">
                            <th style="padding: 12px; text-align: left; width: 33%">{{ __('Ranking List') }}</th>
                            <th style="padding: 12px; text-align: left; width: 33%">{{ __('Per Points') }}</th>
                            <th style="padding: 12px; text-align: left; width: 33%">{{ __('Redeem Amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($redeems as $redeem)
                            <tr style="background-color: transparent; color: white;">
                                <td style="padding: 12px; text-align: left;">{{ $redeem->ranking->ranking }}</td>
                                <td style="padding: 12px; text-align: left;">{{ $redeem->point }} {{ __('Points') }}</td>
                                <td style="padding: 12px; text-align: left;">{{ $redeem->amount.' '.$currency }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 12px; color: white;">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mt-4">
            <div class="site-card" style="border-radius: 12px; overflow: hidden;">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Redeem Summary:') }}</h4>
                </div>
                <div class="site-card-body p-0 table-responsive" style="border-radius: 12px;">
                    <table class="display data-table site-custom-table site-custom-table-sm" style="width: 100%; border-collapse: collapse; border-radius: 12px; overflow: hidden;">
                        <thead>
                        <tr style="background-color: transparent; color: white;">
                            <th style="padding: 12px; text-align: left; width: 25%">{{ __('Description') }}</th>
                            <th style="padding: 12px; text-align: left; width: 25%">{{ __('Amount') }}</th>
                            <th style="padding: 12px; text-align: left; width: 25%">{{ __('Redeemed Date') }}</th>
                            <th style="padding: 12px; text-align: left; width: 25%">{{ __('Transaction Type') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($transactions as $transaction)
                            <tr style="background-color: transparent; color: white;">
                                <td style="padding: 12px; text-align: left;">{{ $transaction->description }}</td>
                                <td style="padding: 12px; text-align: left;">
                                    <span @class([
                                    "fw-bold",
                                    "green-color" => isPlusTransaction($transaction->type),
                                    "red-color" => !isPlusTransaction($transaction->type)
                                    ])>
                                    {{ isPlusTransaction($transaction->type) ? '+' : '-' }}{{ $transaction->amount.' '.$currency }}
                                    </span>
                                </td>
                                <td style="padding: 12px; text-align: left;">{{ $transaction->created_at }}</td>
                                <td style="padding: 12px; text-align: left;">
                                    <span class="site-badge badge-primary">{{ ucfirst(str_replace('_',' ',$transaction->type->value)) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 12px; color: white;">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
