@extends('admin.layout')

@section('content')
    <div class="top">
        <div>
            <h1>Support SMS</h1>
            <p>Customer texts are forwarded to AMS as support tickets.</p>
        </div>
    </div>

    <form class="card" method="POST" action="{{ route('admin.support-sms.store') }}" style="margin-bottom: 16px;">
        @csrf
        <div class="grid-2">
            <div class="field">
                <label for="user_id">User</label>
                <select id="user_id" name="user_id" required>
                    <option value="">Select a user</option>
                    @foreach ($users as $user)
                        <option
                            value="{{ $user->id }}"
                            data-phone="{{ $user->phone }}"
                            @selected((string) old('user_id') === (string) $user->id)
                        >{{ $user->name }}{{ $user->phone ? ' — '.$user->phone : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="form_type">Type</label>
                <select id="form_type" name="form_type" required>
                    @foreach ($formTypes as $value => $label)
                        <option value="{{ $value }}" @selected(old('form_type', 'sms') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="from">From</label>
                <input id="from" name="from" value="{{ old('from') }}" placeholder="+8801700000000">
            </div>
            <div class="field">
                <label for="to">To</label>
                <input id="to" name="to" value="{{ old('to', $supportPhone) }}">
            </div>
            <div class="field">
                <label for="body">Message</label>
                <input id="body" name="body" value="{{ old('body') }}" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Send SMS</button>
    </form>
    <script>
        document.getElementById('user_id')?.addEventListener('change', function () {
            const phone = this.selectedOptions[0]?.dataset.phone || '';
            const from = document.getElementById('from');
            if (from && phone) {
                from.value = phone;
            }
        });
    </script>

    @if ($threads->isEmpty())
        <div class="card">
            <p class="empty">No support SMS yet.</p>
        </div>
    @else
        <div class="sms-threads">
            @foreach ($threads as $thread)
                @php
                    $first = $thread->first();
                    $latest = $thread->last();
                    $title = $thread->firstWhere('customer_name')?->customer_name
                        ?: ($first->direction === 'reply' ? 'Agent' : $first->from_number);
                @endphp
                <article class="card sms-thread">
                    <div class="sms-thread-head">
                        <div>
                            <strong>{{ $title }}</strong>
                            <span class="hint">{{ $first->from_number }}</span>
                        </div>
                        <div class="sms-thread-meta">
                            @if ($latest->ams_ticket_number)
                                <span>{{ $latest->ams_ticket_number }}</span>
                            @endif
                            <span class="badge badge-{{ $latest->status }}">{{ $latest->status }}</span>
                        </div>
                    </div>
                    <div class="sms-chat">
                        @foreach ($thread as $message)
                            <div class="sms-bubble {{ $message->direction === 'reply' ? 'sms-bubble-agent' : 'sms-bubble-customer' }}">
                                <div class="sms-bubble-meta">
                                    {{ $message->direction === 'reply' ? 'Agent' : ($message->customer_name ?: 'Customer') }}
                                    <time>{{ $message->created_at?->format('M j, H:i') }}</time>
                                </div>
                                <p>{{ $message->body }}</p>
                            </div>
                        @endforeach
                    </div>
                    <form class="sms-reply" method="POST" action="{{ route('admin.support-sms.reply') }}">
                        @csrf
                        <input type="hidden" name="thread_id" value="{{ $first->id }}">
                        <input name="body" placeholder="Write a reply" required>
                        <button class="btn btn-primary" type="submit">Reply</button>
                    </form>
                </article>
            @endforeach
        </div>
    @endif
@endsection
