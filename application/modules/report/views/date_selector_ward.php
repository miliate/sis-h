<?php
$now=date("Y-m-d");
$model = "<div class=\"modal-dialog\">\n";
$model .= "    <div class=\"modal-content\">\n";
$model .= "        <div class=\"modal-header\">\n";
$model .= "            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>\n";
$model .= "            <h4 class=\"modal-title\">" . $title . "</h4>\n";
$model .= "        </div>\n";
$model .= "        <div class=\"modal-body\" style='padding-bottom: 5px;'>\n";
$model .= "            <table style=\"width: 100%\">\n";
$model .= "                <thead>\n";
$model .= "                <tr>\n";
$model .= "                    <th>Date: <input type=\"text\" class=\"span2\" value=\"$now\" id=\"{$id}_dpd1\"></th>\n";
$model .= "                    <th>Ward: ". form_dropdown('ward', $ward, '', ' id="ward"') ."</th>\n";
$model .= "                </tr>\n";
$model .= "                </thead>\n";
$model .= "            </table>\n";
if (isset($description)) {
    $model .= "            <div class='alert alert-info' style='margin-top:18px; '>\n";
    $model .= "            $description\n";
    $model .= "            </div>\n";
}
$model .= "        </div>\n";
$model .= "        <div class=\"modal-footer\">\n";
$model .= "            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\n";
$model .= "            <button type=\"button\" class=\"btn btn-primary\" onclick='$id()'>Generate</button>\n";
$model .= "        </div>\n";
$model .= "    </div>\n";
$model .= "</div>";

//$js = "<script type=\"text/javascript\">\n";
//$js .= "    var dp=$(\"#{$id}_dpd1\").datepicker({
//				format: 'yyyy-mm-dd'
//			}).on(\"changeDate\",function(ev){dp.hide()}).data('datepicker');";

$js = "<script type=\"text/javascript\">";
$js .= " var nowTemp = new Date();\n";
$js .= "    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);\n";
$js .= "\n";
$js .= "    var checkin = $('#{$id}_dpd1').datepicker({\n";
$js .= "        dateFormat:'yy-mm-dd'\n";
$js .= "    }).on('changeDate',function (ev) {\n";
$js .= "            if (ev.date.valueOf() > checkout.date.valueOf()) {\n";
$js .= "                var newDate = new Date(ev.date)\n";
$js .= "                newDate.setDate(newDate.getDate() + 1);\n";
$js .= "                checkout.setValue(newDate);\n";
$js .= "            }\n";
$js .= "            checkin.hide();\n";
$js .= "            $('#{$id}_dpd2')[0].focus();\n";
$js .= "        }).data('datepicker');\n";


$js .= "    function $id() {\n";
$js .= "        var params = \"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=750,height=700\";\n";
$js .= "        var lookUpW = window.open(\"" . $url . "/\" + $(\"#{$id}_dpd1\").val() + \"/\" + $(\"#ward\").val(), \"lookUpW\", params);\n";
$js .= "    }\n";
$js .= "</script>";

echo $model . $js;
?>
