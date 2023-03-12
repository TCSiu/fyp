@php
@endphp

<div class="modal fade" id="assign_modal" tabindex="-1" aria-labelledby="assign_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assign_modal_label">{{ __('Assgin Task') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="routePlanningCapacity" class="form-label form-required">{{ __('Assign Task:') }}{!! Utility::required() !!}</label>
                        <select class="form-select" name="assignTask" id="select_assign_task">
                            <option value="Default" disable>{{ __('--Select a Staff--') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success white-space-nowrap" id="btn_route_assign" data-bs-dismiss="modal">
                    {{ __('Submit') }}
                </button>
            </div>
        </div>
    </div>
</div>

