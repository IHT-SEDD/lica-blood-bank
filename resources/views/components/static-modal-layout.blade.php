@props([
'id' => 'global_static_modal',
'size' => '',
'title' => 'Static Modal Title',
])

{{-- Layout Modal Global :begin --}}
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true" role="dialog" aria-labelledby="{{ $id }}_label"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div {{ $attributes->merge(['class' => "modal-dialog modal-dialog-centered $size"]) }}>
        <div class="modal-content">
            {{-- Modal Header :begin --}}
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $id }}_label">{{ $title }}</h4>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            {{-- Modal Header :end --}}

            {{-- Modal Content :begin --}}
            <div class="modal-body">
                {{ $slot }}
            </div>
            {{-- Modal Content :end --}}
        </div>
    </div>
</div>
{{-- Layout Modal Global :end --}}