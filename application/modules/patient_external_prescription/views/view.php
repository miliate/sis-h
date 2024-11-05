<div class="container col-md-12" style="padding-bottom: 16px">
    <h2><?php echo lang('Detalhes da Prescrição Externa'); ?></h2>
    <style>
        .custom-table th {
            text-align: justify;
            padding-right: 10px;
            width: 200px;
        }
        .custom-table td {
            text-align: left;
        }

        strong {
            font-size: 15px;
        }
    </style>
    <table class="table custom-table">
        <tr>
            <th><strong><?php echo lang('Date'); ?>: </strong></th>
            <td><?php echo $prescription->CreateDate; ?></td>
            <th><strong><?php echo lang('NID'); ?>: </strong></th>
            <td><?php echo $prescription->PID; ?></td>
        </tr>
        <tr>
            <th><strong><?php echo lang('Patient Name'); ?>: </strong></th>
            <td><?php echo $prescription->PatientName; ?></td>
            <th><strong><?php echo lang('Patient Category'); ?>: </strong></th>
            <td><?php echo $prescription->Patient_type; ?></td>
        </tr>
        <tr>
            <th><strong><?php echo lang('Health Unit'); ?>: </strong></th>
            <td><?php echo $prescription->HealthUnit; ?></td>
            <th><strong><?php echo lang('Amount Paid'); ?>: </strong></th>
            <td><?php echo $prescription->Cost; ?></td>
        </tr>
        <tr>
            <th><strong><?php echo lang('Technician'); ?>: </strong></th>
            <td colspan="3"><?php echo $prescription->Technician; ?></td>
        </tr>
    </table>

    <h3><?php echo lang('Medicines'); ?></h3>
    <table class="table custom-table">
        <thead>
            <tr>
                <th><strong><?php echo lang('Medicine'); ?></strong></th>
                <th><strong><?php echo lang('Dosage'); ?></strong></th>
                <th><strong> <?php echo lang('Pharmaceutical Form'); ?></strong></th> 
                <th><strong><?php echo lang('Frequency'); ?></strong></th>
                <th><strong><?php echo lang('Period'); ?></strong></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drugs as $drug): ?>
                <tr>
                    <td><?php echo $drug->DrugName; ?></td>
                    <td><?php echo $drug->DoseID; ?></td>
                    <td><?php echo $drug->PharmaceuticalForm; ?></td>
                    <td><?php echo $drug->FrequencyName; ?></td>
                    <td><?php echo $drug->Period; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  
   <div class="row">
        <a href="<?php echo site_url('patient_external_prescription/search'); ?>" class="btn btn-primary"><?php echo lang('Back'); ?></a>

        <form id="printForm" action="<?php echo site_url('/report/pdf/externalPrescription/print/'); ?>" method="post" target="_blank" style="display: inline;">
            <input type="hidden" name="print_prescription" value="<?php echo $prescription->prescription_id; ?>">
            <button type="button" class="btn btn-success" onclick="submitPrintForm()">
                <i class="fa fa-print"></i> <?php echo lang('Print'); ?>
            </button>
        </form>
   </div>
    
</div>


<script> 
    function submitPrintForm() {
        document.getElementById('printForm').submit();
    }
</script>