<div>
    <div class="row">
        <div class="col-md-2 ">
            <?php // echo Modules::run('leftmenu/active_list', $department); //runs the available left menu for preferance ?>

        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?php echo "<i class='fa fa-group'></i> Factura&ccedil;&atilde;o - Clinica Especial"; ?></b></div>
                <div id="patient_list">
                    
<?php
  if (empty($bid))
  die ('Not found');

 $query="SELECT DISTINCT  doctor.Doctor_ID as IDD, doctor.Name as Doctor, sap_bill.active_id as Active
  FROM sap_bill_item , sap_bill, sap_procedures, doctor
  WHERE sap_bill_item.bill_id = sap_bill.id
    And sap_bill_item.doctor = doctor.Doctor_ID
    And sap_bill_item.item_id = sap_procedures.id
    And sap_bill.id = $bid
    GROUP BY sap_bill_item.doctor
    ORDER BY doctor.Name
 ";

$query2="SELECT DISTINCT user.UID as ID, user.Name as User, sap_bill.CreateDate as cDate, sap_bill_item.CreateUser as CUser,sap_bill_item.bill_id as billI, sap_bill_item.unit_price, sap_bill.active_id as Active
FROM sap_bill_item, sap_bill, user
WHERE sap_bill_item.bill_id=sap_bill.id
    AND sap_bill_item.CreateUser=user.UID
    AND sap_bill_item.CreateUser=sap_bill.CreateUser
    AND sap_bill.id = $bid  
    
";

     

$result=$this->db->query($query);
// var_dump($query);
$result2=$this->db->query($query2);
$number = 1;
$util = 1;
$total =0; ?>
 
 <div class="row">
 <div class="col-md-2">

 <table class="table">
     <tr><td>&nbsp;</td></tr>
</table>


 </div>

 <div class="col-md-10">
 <table class="table table-striped">
<tr><td colspan="3"> <h3>Factura&ccedil;&atilde;o por M&eacute;dico</h3> </td></tr>

<?php foreach ($result->result_array() as $row) { 

echo '<tr style="font-size:1em;font-weight:bold"><td>'.$number.".</td>
<td> ".$row["Doctor"]."</td>
<td> <a href='". base_url() . "index.php/report/pdf/clinicBill/print/".$row["Active"]."?d=". $row["IDD"]."'> <i class='fa fa-print fa-lg'></i> </a></td><tr>";
$number++;
}
?>
<tr><td colspan="3"> <h3>Factura&ccedil;&atilde;o por Utilizador</h3> </td></tr>
<?php foreach ($result2->result_array() as $row2) { 
echo '<tr style="font-size:1em;font-weight:bold"><td>'.$util.".</td>
<td> </td>
<td> 
<a href='". base_url() . "index.php/report/pdf/clinicBillUser/print/".$row2["Active"]."?u=".$row2["ID"]."&d=".$row2["cDate"]."'> <i class='fa fa-print fa-lg'></i> </a>
</td><tr>";
$util++;
}
?>

</table>
</div>
</div>





                </div>
            </div>
        </div>
    </div>
</div>


