<div class="container">
    <h2><?php echo lang('Comorbidities for Patient') . ' ' . $PID; ?></h2>

    <!-- Verifica se há comorbidades -->
    <?php if (!empty($comorbidities)): ?>
        <ul>
            <?php foreach ($comorbidities as $comorbidity): ?>
                <li>
                    <strong><?php echo lang('Patology'); ?>:</strong> <?php echo $comorbidity['patology_name']; ?><br>
                    <strong><?php echo lang('Date'); ?>:</strong> <?php echo $comorbidity['date']; ?><br>
                    <strong><?php echo lang('Treatment'); ?>:</strong> <?php echo $comorbidity['treatment']; ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p><?php echo lang('No comorbidities found for this patient.'); ?></p>
    <?php endif; ?>

    <!-- Botão para Voltar -->
    <!-- <a href="<?php echo site_url('patients/view/' . $PID); ?>" class="btn btn-default"><?php echo lang('Back'); ?></a> -->
</div>
