<div id="items-modal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(@$modalTitle ?? 'Items'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row report-info">
                    <div class="col-sm-5">
                        <b id="user-label"></b>
                        <p id="user-name"></p>
                    </div>
                    <div class="col-sm-4">
                        <b>Invoice No.</b>
                        <p id="invoice-no"></p>
                    </div>
                    <div class="col-sm-3">
                        <b>Date</b>
                        <p id="date"></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="items-list-table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>GST (%)</th>
                                <th>GST Amount</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/transaction-reports/items-modal.blade.php ENDPATH**/ ?>