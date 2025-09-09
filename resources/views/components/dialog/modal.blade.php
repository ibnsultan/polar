@props([ 'title' => null, 'id' => 'modal' ])

<div id="{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="{{$id}}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            @if($title)
                <div class="modal-header">
                    <h5 class="font-semibold" id="{{$id}}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-pc-modal-dismiss="{{$id}}" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="modal-body">{{ $slot }}</div>
        </div>
    </div>
</div>
