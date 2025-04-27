@props(['team'])

<form method="POST" action="{{ route('current-team.update') }}" x-data>
    @method('PUT')
    @csrf

    <input type="hidden" name="team_id" value="{{ $team->id }}">

    <a href="javascript:void(0)" class="dropdown-item" x-on:click.prevent="$root.submit();">
        <span>{{ $team->name }}</span>

        @if (Auth::user()->isCurrentTeam($team))
            <div dir="ltr"
                class="flex -space-x-2 overflow-hidden *:flex *:items-center *:justify-center *:rounded-full *:w-[30px] *:h-[30px] hover:*:z-10 *:border-2">
                <i class="fas text-success fa-check text-xs"></i>
            </div>
        @endif
    </a>
</form>
