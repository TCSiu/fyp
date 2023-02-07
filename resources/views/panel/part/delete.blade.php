<div class="modal fade" id="is_delete_modal" tabindex="-1" aria-labelledby="is_delete_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="is_delete_modal_label">{{ __('Delete Order') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ __('Do you want to delete Order ' . $id . '?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a class="btn btn-danger me-2" href="{{ route('cms.delete', ['model' => $model, 'id' => $id]) }}" role="button">
                    <i class="align-middle me-2" data-feather="trash-2"></i>{{ __('Confirm Delete') }}
                </a>
            </div>
        </div>
    </div>
</div>