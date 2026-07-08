<div class="modal" data-backdrop="static" id="{{ $reorderModalId }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('snawbar-datatable::datatable.reorder-columns') ?? 'Reorder Columns' }}</h4>
            </div>
            <div class="modal-body">
                <form id="{{ $reorderModalId }}_form">
                    <ul class="list-group" id="{{ $reorderModalId }}_list" style="cursor: grab;">
                        @foreach($columns as $column)
                            <li class="list-group-item d-flex justify-content-between align-items-center" data-column-name="{{ $column->data }}">
                                <span>
                                    <i class="fas fa-arrows-alt-v mr-2 ml-2 text-muted"></i>
                                    {{ datatablePrintHtml($column->title) }}
                                </span>
                                <input type="hidden" name="columns[]" value="{{ $column->data }}">
                            </li>
                        @endforeach
                    </ul>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="badge badge-light border text-muted py-2 px-3" style="cursor:pointer;" id="{{ $reorderModalId }}_reset" onclick="{{ $buttonResetReorderFunction }}">
                    <i class="fas fa-undo mr-1"></i> {{ __('snawbar-datatable::datatable.reset') ?? 'Reset' }}
                </button>
                <div>
                    <button type="button" class="btn btn-danger mr-1" data-dismiss="modal">{{ __('snawbar-datatable::datatable.daxstn') }}</button>
                    <button type="button" class="btn btn-primary" id="{{ $reorderModalId }}_save" onclick="{{ $buttonReorderFunction }}">{{ __('snawbar-datatable::datatable.save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
