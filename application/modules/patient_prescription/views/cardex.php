<div class="container-fluid">
    

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
             <div class="panel panel-info">
                <!-- Default panel contents -->
                <div class="panel-heading"><?= lang('Prescription'); ?></div>
                    <!-- Table -->
                    <table class="table input-sm" id="table_drug">
                        <thead>
                        <tr bgcolor="#e2e2e2">
                            <th><b>#</b></th>
                            <th><b>Nome</b></th>
                            <th><b>Dose</b></th>
                            <th><b>Frequência</b></th>
                            <th><b>Periodo</b></th>
                        </tr>

                        </thead>
                        <tbody>
                        <?php
                        foreach ($drug_list as $drug_order) {
                            echo '<tr>';
                            echo '<td>' . $drug_order['order'] . '</td>';
                            echo '<td>' . $drug_order['drug_info'] . '</td>';
                            echo '<td>' . $drug_order['dose'] . '</td>';
                            echo '<td>' . $drug_order['frequency'] . '</td>';
                            echo '<td>' . $drug_order['period'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>


    <div class="row">

    <div class="col-md-10 col-md-offset-2">
                <div class="panel panel-primary">
                    <!-- Default panel contents -->
                    <div class="panel-heading"><h1><?= lang('Cardex'); ?></h1></div>
                   <?php $form_generator = new MY_Form('Processo de Admissão');?>
                    <table class="table" id="drug_table">
                        <thead>
                        <tr bgcolor="#e2e2e2">
            
                            <th><b>Data</b></th>
                            <th><b>Medica&ccedil;&atilde;o</b></th>
                            <th><b>Dose</b></th>
                            <th><b>Via</b></th>
                            <th><b>Horas</b></th>
                            <th><b>&nbsp;</b></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>                  
                            <td><div class="btn btn-warning"><b><?= date('d-m-Y'); ?></b></div></td>
                            <td>
                                <select id="med" class="form-control">
                           <?php
                           $prescription_id=(int)17;
                         $patient_prescription_have_drug = $this->m_patient_prescription_have_drug->get_many_by(array('PrescriptionID' => $prescription_id));
                           $data['drug_list'] = array();
                           foreach ($patient_prescription_have_drug as $raw_drug) {
                               $tmp_data = array();
                               $tmp_data['order'] = $raw_drug->Order;
                               if ($raw_drug->DrugID > 0) {
                                   $drug = $this->m_who_drug->get($raw_drug->DrugID);
                                 //  $tmp_data['drug_info'] = $drug->name . ' ' . $drug->default_num . ' ' . $drug->formulation . '/' . $drug->dose;
                               }
                               echo "<option>".$drug->name."</option>";
                            } 
                         ?>
                            </select>
                        </td>
                        <td><select id="dose" class="form-control"><option>1 Vez</option> <option>2 Vezes</option></select></td>
                        <td>
                            <select id="via" name="drug_select" class="form-control" id="drug">
                                <option>Auricular ou Otológica</option> 
                                <option>Intranasal</option> 
                                <option>Intramuscular (IM)</option> 
                                <option>Subcutânea (SB)</option> 
                                <option>Intravenosa ou Endovenosa (IV ou EV)</option> 
                                <option>Intradérmica (ID)</option> 
                                <option>Ocular, Oftálmica ou Conjuntival</option> 
                                <option>Oral</option> 
                                <option>Sublingual</option> 
                                <option>Transdermal ou Transdérmica</option> 
                                <option>Respiratória ou Inalatória</option> 
                                <option>Tópica</option> 
                                <option>Epidural ou Intratecal</option> 
                                <option>Retal</option> 
                                <option>Intrapeniana</option> 
                                <option>Intrauretral</option> 
                                <option>Intravaginal</option> 
                            </select>
                        </td>
                        <td><input type="time" id="horas" value="<?= date('H:i'); ?>" class="form-control" name="horas"></td>
                        <td>                         
                            <button type="button" class="btn btn-info" id="add_drug_button" style="float:right">
                                <span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('Dispense') ?>
                            </button>
                        </td>
                    </tr>
                    </tbody>                
                </table>

                <table class="table input-sm" id="table_drug">
                    <thead>
                        <tr bgcolor="#e2e2e2">
                            <th><b>Nº Receita</b></th>
                            <th><b>Data | Hora da Medicação</b></th>
                            <th><b>Medicação</b></th>
                            <th><b>Dose</b></th>      
                            <th><b>Via de Administração</b></th>
                            <th><b>Status</b></th>
                            <th><b>&nbsp;</b></th>
                     
                        </tr>
                    </thead>
                    <tbody>
                        <?php
               
// Define the SQL query
$query = $conn->query("SELECT * FROM patient_medication_have_drug WHERE MedicationID = $prescription_id ORDER BY horas DESC");
                        foreach ($query as $cardex){
                            echo '<tr>';
                            echo '<td>' . $cardex['MedicationID'] . '</td>';
                            
                            echo '<td>' . $cardex['data'] .' | '.$cardex['horas']. '</td>';  
                      
                            echo '<td>' . utf8_encode($cardex['med']) . '</td>';                         
                            echo '<td>' . $cardex['dose'] . '</td>';
                            
                            echo '<td>' . utf8_encode($cardex['via']) . '</td>';
                            echo '<td>' . ($cardex['dispensed'] == 1 ? '<i class="fa fa-check" style="color:green" aria-hidden="true"></i>' : '<i class="fa fa-remove" style="color:red" aria-hidden="true"></i>') . '</td>';
                            echo '<td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this, ' . $cardex['ID'] . ')">
                            <span class="glyphicon glyphicon-remove-sign"></span></button></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
document.getElementById('add_drug_button').addEventListener('click', function() {
    var tableBody = document.querySelector('#drug_table tbody');
    var newRow = document.createElement('tr');

    // Extracting data from input fields
    var data = document.querySelector('#drug_table tbody tr:first-child td:first-child').textContent;
    var med = document.querySelector('#drug_table tbody tr:first-child td:nth-child(2) select').value;
    var dose = document.querySelector('#drug_table tbody tr:first-child td:nth-child(3) select').value;
    var via = document.querySelector('#drug_table tbody tr:first-child td:nth-child(4) select').value;
    var horas = document.querySelector('#drug_table tbody tr:first-child td:nth-child(5) input').value;
    var MedicationID = document.getElementById('MedicationID').value;
    
    // Creating the new row with extracted data and remove button
    newRow.innerHTML = `
        <td><div class="btn btn-success"><b>${data}</b></div>
        <input type="hidden" class="MedicationID" name="MedicationID" value="${MedicationID}">
        <input type="hidden" class="data" name="data[]" value="${data}">
        </td>
        <td>${med}
        <input type="hidden" class="med" name="med[]" value="${med}">
        </td>
        <td>${dose}
        <input type="hidden" class="dose" name="dose[]" value="${dose}">
        </td>
        <td>${via}
        <input type="hidden" class="via" name="via[]" value="${via}">
        </td>
        <td>${horas}
        <input type="hidden" class="horas" name="horas[]" value="${horas}">
        </td>
        <td>
        <button type="button" class="btn btn-danger" onclick="removeRow(this)">
            <span class="glyphicon glyphicon-remove-sign"></span>
        </button>
        </td>
    `;

    // Appending the new row to the table body
    tableBody.appendChild(newRow);

    // Prepare data to be sent to the server
    var drugData = {
        data: data.trim(),
        med: med.trim(),
        dose: dose.trim(),
        via: via.trim(),
        horas: horas.trim(),
        MedicationID: MedicationID
    };

    console.log('Sending data to server:', drugData); // Debug log

    // Send data to CodeIgniter controller via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo base_url() ?>index.php/patient_prescription/save_cardex');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Data saved successfully.');
            console.log('Server response:', xhr.responseText); // Debug log

            // Refresh the page after successful data save
            location.reload();
        } else {
            console.error('Error saving data:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Network error occurred.');
    };
    xhr.send(JSON.stringify(drugData));
});

function removeRow(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function deleteRow(button, ID) {
    // Prepare data to be sent to the server
    var deleteData = {
        ID: ID
    };

    console.log('Sending delete request to server:', deleteData); // Debug log

    // Send delete request to CodeIgniter controller via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo base_url() ?>index.php/patient_prescription/delete_cardex');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Data deleted successfully.');
            console.log('Server response:', xhr.responseText); // Debug log

            // Remove the row from the table
            removeRow(button);
        } else {
            console.error('Error deleting data:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Network error occurred.');
    };
    xhr.send(JSON.stringify(deleteData));
}


</script>

