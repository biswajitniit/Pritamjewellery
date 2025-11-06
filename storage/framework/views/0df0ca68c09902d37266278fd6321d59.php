<!-- resources/views/exports/quality_check_excel.blade.php -->

<table>
    <thead>
        
        <tr>
            <th colspan="13" style="text-align: center; font-size: 14px; font-weight: bold; padding: 10px;">
                <?php echo e($company_name); ?>

            </th>
        </tr>

        
        <tr>
            <th colspan="13" style="text-align: center; font-size: 11px; font-weight: bold; padding: 8px;">
                Job Wise Delivery Details for <?php echo e(\Carbon\Carbon::parse($date)->format('d.m.Y')); ?>

            </th>
        </tr>

        
        <tr>
            <td colspan="13"></td>
        </tr>

        
        <tr>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">S. NO</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">QC VOUCHER</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">DATE</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">KARIGAR NAME</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">JOB NO</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">ITEM CODE</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">DESIGN</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">SOLDER ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">POLISH ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">FINISH ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">MINA ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">OTHER ITEMS</td>
            <td style="text-align: center; background-color: #D3D3D3; border: 1px solid #000; padding: 8px; font-weight: bold;">REMARK</td>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td style="text-align: center;"><?php echo e($index + 1); ?></td>
            <td style="text-align: left;"><?php echo e($item->qc_voucher); ?></td>
            <td style="text-align: center;"><?php echo e(\Carbon\Carbon::parse($item->qualitycheck_date)->format('d.m.Y')); ?></td>
            <td style="text-align: left;"><?php echo e($item->karigar_name); ?></td>
            <td style="text-align: right;"><?php echo e($item->job_no); ?></td>
            <td style="text-align: centre;"><?php echo e($item->item_code); ?></td>
            <td style="text-align: centre;"><?php echo e($item->design); ?></td>
            <td style="text-align: center;"><?php echo e($item->solder_items); ?></td>
            <td style="text-align: center;"><?php echo e($item->polish_items); ?></td>
            <td style="text-align: center;"><?php echo e($item->finish_items); ?></td>
            <td style="text-align: center;"><?php echo e($item->mina_items); ?></td>
            <td style="text-align: center;"><?php echo e($item->other_items); ?></td>
            <td style="text-align: left;"><?php echo e($item->remark_items); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="13" style="text-align: center; border: 1px solid #000; padding: 10px;">No records found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/exports/quality_check_excel.blade.php ENDPATH**/ ?>