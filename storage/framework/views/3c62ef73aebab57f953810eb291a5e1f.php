<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    .report-container {
        background: #fff;
        padding: 30px;
        font-size: 12px;
        font-family: "Arial", sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px 6px;
        text-align: center;
        font-size: 11px;
    }

    th {
        background-color: #f3f3f3;
        font-weight: bold;
        color: #000;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .header-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .header-section h2 {
        margin: 5px 0;
        font-size: 16px;
        font-weight: bold;
    }

    .header-section p {
        margin: 3px 0;
        font-size: 12px;
    }

    .date-info {
        text-align: right;
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 12px;
    }

    .action-buttons {
        text-align: center;
        margin: 20px 0;
    }

    .action-buttons button, .action-buttons a {
        margin: 0 5px;
        padding: 8px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 12px;
    }

    .action-buttons .btn-success {
        background-color: #28a745;
    }

    .action-buttons .btn-secondary {
        background-color: #6c757d;
    }

    .action-buttons .btn-danger {
        background-color: #dc3545;
    }

    .action-buttons button:hover, .action-buttons a:hover {
        opacity: 0.9;
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #666;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    @media print {
        .action-buttons {
            display: none;
        }
        body {
            margin: 0;
            padding: 0;
        }
        .no-print {
            display: none;
        }
    }
</style>

<div class="report-container">

    <div class="action-buttons no-print">
        <a href="<?php echo e(route('new-report')); ?>" class="btn-secondary">Back</a>

        

        <button class="btn-danger" onclick="window.print()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>

    <div class="date-info">
        Date: <?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?>

    </div>

    <div class="header-section">
        <h2>QUALITY CHECK REPORT</h2>
        <p>Accepted Items for <?php echo e(\Carbon\Carbon::parse($date)->format('d-m-Y')); ?></p>
    </div>

    <?php if($data->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>S. NO</th>
                    <th>QC VOUCHER</th>
                    <th>DATE</th>
                    <th>KARIGAR NAME</th>
                    <th>JOB NO</th>
                    <th>ITEM CODE</th>
                    <th>DESIGN</th>
                    <th>SOLDER</th>
                    <th>POLISH</th>
                    <th>FINISH</th>
                    <th>MINA</th>
                    <th>OTHER</th>
                    <th>REMARK</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($index + 1); ?></td>
                        <td class="text-left"><?php echo e($item->qc_voucher); ?></td>
                        <td class="text-center"><?php echo e(\Carbon\Carbon::parse($item->qualitycheck_date)->format('d-m-Y')); ?></td>
                        <td class="text-left"><?php echo e($item->karigar_name); ?></td>
                        <td class="text-right"><?php echo e($item->job_no); ?></td>
                        <td class="text-centre"><?php echo e($item->item_code); ?></td>
                        <td class="text-centre"><?php echo e($item->design); ?></td>
                        <td class="text-center"><?php echo e($item->solder_items); ?></td>
                        <td class="text-center"><?php echo e($item->polish_items); ?></td>
                        <td class="text-center"><?php echo e($item->finish_items); ?></td>
                        <td class="text-center"><?php echo e($item->mina_items); ?></td>
                        <td class="text-center"><?php echo e($item->other_items); ?></td>
                        <td class="text-center"><?php echo e($item->remark_items); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <p>No accepted records found for the selected date.</p>
        </div>
    <?php endif; ?>

    <div class="action-buttons no-print" style="margin-top: 30px;">
        <form method="POST" action="<?php echo e(route('karigar.qualitycheck.export')); ?>" style="display: inline;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($date); ?>">
            <button type="submit" class="btn-success">
                <i class="fa fa-file-excel"></i> Export to Excel
            </button>
        </form>
        <button class="btn-danger" onclick="window.print()">
            <i class="fa fa-print"></i> Print Report
        </button>
    </div>

</div>

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php /**PATH E:\webdev\Pritamjewellery\resources\views/transaction-reports/quality-check-report.blade.php ENDPATH**/ ?>