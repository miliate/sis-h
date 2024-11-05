<?php
$now=date("Y-m-d");
$model = "<div class=\"modal-dialog\">\n";
$model .= "    <div class=\"modal-content\">\n";
$model .= "        <div class=\"modal-header\">\n";
$model .= "            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
$model .= "            <h4 class=\"modal-title\">" . $title . "</h4>\n";
$model .= "        </div>\n";
$model .= "        <div class=\"modal-body\">\n";
$model .= "            <table style=\"width: 100%\" class=\"table table-bordered\">\n";
$model .= "                <thead>\n";
$model .= "                <tr>\n";
$model .= "                    <th>Lote</th>\n";
$model .= "                    <th>Validade</th>\n";
$model .= "                </tr>\n";
$model .= "                </thead>\n";
$model .= "                <tbody>\n";
foreach ($batches as $lote) {
    $model .= "                <tr>\n";
    $model .= "                    <td>" . $lote['batch'] . "</td>\n"; // Acessa o campo 'batch'
    $model .= "                    <td>" . $lote['deadline'] . "</td>\n"; // Acessa o campo 'deadline'
    $model .= "                </tr>\n";
}
$model .= "                </tbody>\n";
$model .= "            </table>\n";
$model .= "        </div>\n";
$model .= "        <div class=\"modal-footer\">\n";
$model .= "            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\n";
$model .= "        </div>\n";
$model .= "    </div>\n";
$model .= "</div>";

$js = "<script type=\"text/javascript\">";
$js .= "</script>";
echo $model . $js;

?>