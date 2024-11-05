<body>
    <?php
    echo '<div class="container mt-4" style="padding:20px;">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-heading bg-info text-white p-2">';
    echo '<h5 class="font-weight-bold m-0">Visão Geral do Paciente</h4>';
    echo '</div>';
    echo '<div class="panel-body bg-light p-3 rounded">';

    echo '<table class="table table-borderless mb-0">';

    echo '<tr>';
    echo '<th class="w-25 text-primary">' . lang('Full name') . '</th>';
    echo '<td class="w-25">Sr. ' . $nome_do_doente . '</td>';
    echo '<th class="w-25 text-primary">' . lang('PID') . '</th>';
    echo '<td class="w-25"><b>' . $pid . '</b></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th class="w-25 text-primary">' . lang('Address') . '</th>';
    echo '<td class="w-25">' . $morada . '</td>';
    echo '<th class="w-25 text-primary">' . lang('Gender') . '</th>';
    echo '<td class="w-25">' . $sexo . '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th class="w-25 text-primary">' . lang('Age') . '</th>';
    echo '<td class="w-25">' . $idade . '</td>';
    echo '<th class="w-25 text-primary">' . lang('Weight') . ' (kg)</th>';
    echo '<td class="w-25">' . $peso . '</td>';
    echo '</tr>';

    echo '</table>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    ?>

</body>