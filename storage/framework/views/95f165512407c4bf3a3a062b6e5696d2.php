<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Regular Order Information</title>
  <style>
    @page { size: A5 portrait; margin: 10mm; }
    body { font-family: "Segoe UI", Arial, Helvetica, sans-serif; font-size: 11px; color: #000; margin: 0; }
    .container { width: 100%; margin: 0 auto; }
    h4 { font-size: 13px; font-weight: 600; margin-bottom: 6px; text-align: left; }

    .header-info { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .header-info th, .header-info td {
      border: 1px solid #dcdcdc;
      padding: 4px 6px;
      font-size: 11px;
      vertical-align: middle;
    }
    .header-info th { background-color: #f8f8f8; text-align: left; font-weight: 600; color: #333; width: 12%; }
    .header-info td { color: #222; }

    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #dcdcdc; padding: 4px 6px; line-height: 1.3; color: #222; }
    th { background-color: #f8f8f8; font-weight: 600; text-align: left; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total-row td { font-weight: bold; background: #f8f8f8; }

    th:nth-child(1), td:nth-child(1) { width: 4%; text-align:center; }
    th:nth-child(2), td:nth-child(2) { width: 10%; }
    th:nth-child(3), td:nth-child(3) { width: 20%; }
    th:nth-child(4), td:nth-child(4) { width: 8%; }
    th:nth-child(5), td:nth-child(5) { width: 6%; text-align:center; }
    th:nth-child(6), td:nth-child(6) { width: 9%; text-align:right; }
    th:nth-child(7), td:nth-child(7) { width: 6%; text-align:right; }
    th:nth-child(8), td:nth-child(8) { width: 9%; text-align:right; }
    th:nth-child(9), td:nth-child(9) { width: 9%; text-align:right; }
    th:nth-child(10), td:nth-child(10) { width: 9%; text-align:right; }

    @media print { .no-print { display: none; } body { margin: 0; } }
    .no-print { text-align: center; margin-top: 10px; }
  </style>
</head>
<body>

<div class="container">
  <h4>Regular Order Information</h4>

  <!-- Header Info -->
  <table style="width:100%; border-collapse: collapse; margin-bottom:8px; font-size:11px;">
    <tr>

      <!-- Right Column (JOB NO & KID) -->
      <td style="width:50%; vertical-align:top; border:1px solid #dcdcdc; padding:0;">
        <table style="width:100%; border-collapse:collapse;">
          <tr>
            <th style="background:#f8f8f8; text-align:left; border-bottom:1px solid #dcdcdc; padding:5px 6px; font-weight:600;">
              JOB NO.
            </th>
            <td style="border-bottom:1px solid #dcdcdc; padding:5px 6px;">
              <?php echo e($issuetokarigar->customerorder->jo_no ?? '-'); ?>

            </td>
          </tr>
          <tr>
            <th style="background:#f8f8f8; text-align:left; border-bottom:1px solid #dcdcdc; padding:5px 6px; font-weight:600;">
              KID
            </th>
            <td style="padding:5px 6px;">
              <?php if(isset($issuetokarigaritems[0])): ?>
                <?php echo e($issuetokarigaritems[0]->kid ?? '-'); ?>

              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </td>

      <!-- Left Column (Issue Date & Req. Date) -->
      <td style="width:50%; vertical-align:top; border:1px solid #dcdcdc; padding:0;">
        <table style="width:100%; border-collapse:collapse;">
          <tr>
            <th style="background:#f8f8f8; text-align:left; border-bottom:1px solid #dcdcdc; padding:5px 6px; font-weight:600;">
              Issue Date
            </th>
            <td style="border-bottom:1px solid #dcdcdc; padding:5px 6px;">
              <?php echo e(date('d/m/Y', strtotime($issuetokarigar->customerorder->jo_date))); ?>

            </td>
          </tr>
          <tr>
            <th style="background:#f8f8f8; text-align:left; border-bottom:1px solid #dcdcdc; padding:5px 6px; font-weight:600;">
              Req. Date
            </th>
            <td style="padding:5px 6px;">
              <?php echo e(date('d/m/Y', strtotime($issuetokarigaritems[0]->delivery_date))); ?>

            </td>
          </tr>
        </table>
      </td>

    </tr>
  </table>

  <!-- Item Table -->
  <table>
    <thead>
      <tr>
        <th>Sl.No.</th>
        <th>Design</th>
        <th>Description</th>
        <th>Size</th>
        <th>UOM</th>
        <th>Std. Wt.</th>
        <th>Qty.</th>
        <th>Total Wt.</th>
        <th>Min Wt.</th>
        <th>Max Wt.</th>
      </tr>
    </thead>

    <tbody>
    <?php 
      $totalQty = 0; 
      $totalTotalWt = 0; 
      $i = 1; 
    ?>

    <?php $__empty_1 = true; $__currentLoopData = $issuetokarigaritems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <?php
          $rowTotalWt = (float)$item->st_weight * (float)$item->qty;
          $totalQty += (float) $item->qty;
          $totalTotalWt += $rowTotalWt;
      ?>
      
      <tr>
        <td class="text-center"><?php echo e($i++); ?></td>
        <td><?php echo e($item->design ?? '-'); ?></td>
        <td><?php echo e($item->description ?? '-'); ?></td>
        <td><?php echo e($item->size ?? '-'); ?></td>
        <td class="text-center"><?php echo e($item->uom ?? '-'); ?></td>
        <td class="text-right"><?php echo e(number_format((float)$item->st_weight, 2)); ?></td>
        <td class="text-right"><?php echo e($item->qty); ?></td>
        <td class="text-right"><?php echo e(number_format($rowTotalWt, 2)); ?></td>
        <td class="text-right"><?php echo e(number_format((float)$item->min_weight, 2)); ?></td>
        <td class="text-right"><?php echo e(number_format((float)$item->max_weight, 2)); ?></td>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr><td colspan="10" class="text-center">No records found</td></tr>
    <?php endif; ?>

    <!-- FOOTER TOTAL ROW -->
    <tr class="total-row">
      <td colspan="6" class="text-right">TOTAL</td>
      <td class="text-right"><?php echo e($totalQty); ?></td>
      <td class="text-right"><?php echo e(number_format($totalTotalWt, 2)); ?></td>
      <td></td>
      <td></td>
    </tr>

    </tbody>
  </table>

  <div class="no-print">
    <button onclick="window.print()">🖨️ Print</button>
  </div>
</div>

<script> window.onload = () => window.print(); </script>

</body>
</html>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/issuetokarigars/print.blade.php ENDPATH**/ ?>