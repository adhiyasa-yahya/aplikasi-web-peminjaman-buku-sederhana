<div class="modal fade" id="{{ @$id }}" tabindex="-1" role="dialog" aria-labelledby="{{ @$id }}" aria-hidden="true">
    <div class="modal-dialog {{ $size??'modal-lg' }} modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ @$title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="mainform_{{ @$id }}" onsubmit="return ajaxPost(event, this)" method="post" action="{{ $action }}">
                    {{ @$slot }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="mainform_{{ @$id }}">Simpan</button>
            </div>
        </div>
    </div>
</div>