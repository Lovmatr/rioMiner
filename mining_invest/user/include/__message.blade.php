<div class="row desktop-screen-show">
    <div class="col">
        @foreach ($messages as $message)
            <div class="alert site-alert alert-dismissible fade show d-flex justify-content-between align-items-center"
                role="alert">
                <div class="content d-flex align-items-center">
                    <div class="icon me-2"><i class="anticon anticon-warning"></i></div>
                    <span>{{ $message->notice }}</span>
                </div>
                <div class="action d-flex">
                    <a href="{{ route('user.read-notification', $message->id) }}" class="site-btn-sm grad-btn me-2">
                        <i class="anticon anticon-info-circle"></i>{{ __('Mark as read') }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
