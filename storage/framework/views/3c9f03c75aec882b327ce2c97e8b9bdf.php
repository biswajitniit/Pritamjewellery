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
	th,
	td {
		border: 1px solid #000;
		padding: 4px 6px;
		text-align: center;
		font-size: 11px;
	}
	th {
		background-color: #f3f3f3;
		font-weight: bold;
	}
	h3,
	h5 {
		text-align: center;
		margin: 5px 0;
		font-weight: bold;
	}
	h3 {
		font-size: 18px;
	}
	h5 {
		font-size: 14px;
	}
	.section-title {
		font-weight: bold;
		margin: 20px 0 5px;
		background-color: #fff;
		padding: 5px 0;
		font-size: 13px;
	}
	.text-right {
		text-align: right;
	}
	.text-left {
		text-align: left;
	}
	@media print {
		.no-print {
			display: none;
		}
	}
</style>

<main class="main-wrapper">
	<div class="main-content report-container mb-3">

		<div class="text-center mt-3 no-print">
			<a href="<?php echo e(route('new-report')); ?>" class="btn btn-secondary">Back</a>

			<form method="POST" action="<?php echo e(route('export.ledger.excel')); ?>" style="display: inline;">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="ledger_type" value="<?php echo e($ledger_type); ?>">
				<input type="hidden" name="ledger_name" value="<?php echo e($ledger_name); ?>">
				<input type="hidden" name="date_from" value="<?php echo e($date_from); ?>">
				<input type="hidden" name="date_to" value="<?php echo e($date_to); ?>">
				<button type="submit" class="btn btn-success">
					<i class="fa fa-file-excel"></i>
					Export to Excel
				</button>
			</form>

			<button class="btn btn-danger" onclick="window.print()">Print Report</button>
		</div>

        <div class="no-print mb-3">
            <form method="GET" action="<?php echo e(route('transaction.report.generate')); ?>" class="form-inline">
                <input type="hidden" name="ledger_type" value="<?php echo e($ledger_type); ?>">
                <input type="hidden" name="ledger_name" value="<?php echo e($ledger_name); ?>">
                <input type="hidden" name="date_from" value="<?php echo e($date_from); ?>">
                <input type="hidden" name="date_to" value="<?php echo e($date_to); ?>">

                <label for="vou_no" class="mr-2"><strong>Filter by Voucher No:</strong></label>
                <input type="text" name="vou_no" id="vou_no" value="<?php echo e(request('vou_no')); ?>"
                    class="form-control mr-2" placeholder="Enter Voucher No">

                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="<?php echo e(route('transaction.report.generate', [
                    'ledger_type' => $ledger_type,
                    'ledger_name' => $ledger_name,
                    'date_from' => $date_from,
                    'date_to' => $date_to
                ])); ?>" class="btn btn-secondary ml-2">Reset</a>
            </form>
        </div>


		<h3><?php echo e($company_name); ?></h3>
		<h5>Ledger REPORT of -
			<?php echo e($ledger_name); ?>

			(<?php echo e($ledger_type); ?>)
						      for
			<?php echo e(\Carbon\Carbon::parse($date_from)->format('d.m.Y')); ?>

			to
			<?php echo e(\Carbon\Carbon::parse($date_to)->format('d.m.Y')); ?>

		</h5>

		<br>

		<!-- MAIN LEDGER TABLE -->
		<table>
			<thead>
				<tr>
					<th rowspan="2">SL NO</th>
					<th rowspan="2">Voucher No</th>
					<th rowspan="2">Date</th>
					<th rowspan="2">Issued Product</th>
					<th rowspan="2">Purity</th>
					<th rowspan="2">Wt/pcs</th>
					<th colspan="2">Opening</th>
					<th colspan="2">Issue</th>
					<th colspan="2">Receive</th>
					<th colspan="1">Loss</th>
					<th colspan="2">Closing</th>
				</tr>
				<tr>
					<th>GOLD<br>(100%)</th>
					<th>Other</th>
					<th>GOLD<br>(100%)</th>
					<th>Others</th>
					<th>GOLD<br>(100%)</th>
					<th>Others</th>
					<th>GOLD<br>(100%)</th>
					<th>GOLD<br>(100%)</th>
					<th>Others</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    $current_group = null;
                    $group_start = 0;
                ?>

								        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                                    <?php
                                                                                        // Group by voucher number
                                                                                        $vou_group = $t['vou_no'] . '_' . $t['date'];
                                                                                        if ($current_group !== $vou_group) {
                                                                                            $current_group = $vou_group;
                                                                                            $group_start = $index;
                                                                                        }
                                                                                        $is_first_in_group = ($index === $group_start);
                                                                                    ?>

                                            <tr>
                                                <?php if($is_first_in_group): ?>
                                                                <td rowspan="<?php echo e(collect($transactions)->filter(function ($item) use ($t) {
                                                        return $item['vou_no'] === $t['vou_no'] && $item['date'] === $t['date'];
                                                    })->count()); ?>"><?php echo e($index + 1); ?></td>
                                                <?php endif; ?>

                                                <td><?php echo e($t['vou_no']); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($t['date'])->format('d.m.Y')); ?></td>
                                                <td><?php echo e($t['metal_name']); ?></td>
                                                <td><?php echo e($t['purity'] ? number_format($t['purity'], 1) : ''); ?></td>
                                                <td><?php echo e(number_format($t['wt_pcs'], 3)); ?></td>
                                                <td><?php echo e($t['opening_gold'] > 0 ? number_format($t['opening_gold'], 3) : ''); ?></td>
                                                <td><?php echo e($t['opening_other'] > 0 ? number_format($t['opening_other'], 3) : ''); ?></td>
                                                <td><?php echo e($t['issue_gold'] > 0 ? number_format($t['issue_gold'], 3) : ''); ?></td>
                                                <td><?php echo e($t['issue_other'] > 0 ? number_format($t['issue_other'], 3) : ''); ?></td>
                                                <td><?php echo e($t['receive_gold'] > 0 ? number_format($t['receive_gold'], 3) : ''); ?></td>
                                                <td><?php echo e($t['receive_other'] > 0 ? number_format($t['receive_other'], 3) : ''); ?></td>
                                                <td><?php echo e($t['loss_gold'] > 0 ? number_format($t['loss_gold'], 3) : ''); ?></td>
                                                <td><?php echo e(number_format($t['closing_gold'], 3)); ?></td>
                                                <td><?php echo e(number_format($t['closing_other'], 3)); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="15">No records found for the selected period.</td>
                    </tr>
                <?php endif; ?>
			</tbody>
		</table>

		<!-- GOLD SUMMARY TABLE -->
		<?php if(count($gold_summary_table) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>SL NO</th>
                        <th>DESCRIPTION</th>
                        <th colspan="2">Customer</th>
                        <th>PURITY</th>
                        <th>OP. BAL</th>
                        <th>RECD.</th>
                        <th>ISSUE</th>
                        <th>CLG BAL.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $gold_summary_table; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td class="text-left"><?php echo e($item['description']); ?></td>
                            <td colspan="2"><?php echo e($item['customer']); ?></td>
                            <td><?php echo e($item['purity']); ?></td>
                            <td><?php echo e($item['opening']); ?></td>
                            <td><?php echo e($item['receive']); ?></td>
                            <td><?php echo e($item['issue']); ?></td>
                            <td><?php echo e($item['closing']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
		<?php if(count($other_summary_table) > 0): ?>
                <div class="section-title"> Other articles</div>
            <table>
                <thead>
                    <tr>
                        <th>SL NO</th>
                        <th>DESCRIPTION</th>
                        <th>OP. BAL.</th>
                        <th>RECD. By Karigar</th>
                        <th>ISSUE BY Karigar</th>
                        <th>CLG BAL.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $other_summary_table; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td class="text-left"><?php echo e($item['description']); ?></td>
                            <td><?php echo e($item['opening']); ?></td>
                            <td><?php echo e($item['receive']); ?></td>
                            <td><?php echo e($item['issue']); ?></td>
                            <td><?php echo e($item['closing']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

		<div class="text-center mt-3 no-print">
			<a href="<?php echo e(route('new-report')); ?>" class="btn btn-secondary">Back</a>
			<button class="btn btn-danger" onclick="window.print()">Print Report</button>
		</div>

	</div>
</main>

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php /**PATH D:\Lara\Pritamjewellery\resources\views/transaction-reports/view_report.blade.php ENDPATH**/ ?>