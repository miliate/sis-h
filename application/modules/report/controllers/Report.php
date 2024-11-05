<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends LoginCheckController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
//        $this->load->library('session');
//        if (isset($_GET["mid"])) {
//            $this->session->set_userdata('mid', $_GET["mid"]);
//        }
    }

    public function graphyc_report() {
        $data = [];
        $this->qch_template->load_form_layout('graphyc_report', $data);
    }
    
    public function getReportgraphycreportData($reportType = null, $startDate = null, $endDate = null) {
        if (!$reportType || !$startDate || !$endDate) {
            http_response_code(400); 
            echo json_encode(['error' => 'Parâmetros inválidos']);
            return;
        }
    
        try {
            switch ($reportType) {
                case 'admission':
                    $this->load->model('m_patient_active_list');
                    $report_data = $this->m_patient_active_list->get_admission_report_by_age_gender_and_reason($startDate, $endDate);
                    break;
                case 'observation':
                    $this->load->model('M_medical_history');
                    $report_data = $this->M_medical_history->get_doctor_observation_report($startDate, $endDate);
                    break;
                case 'discharge':
                    $this->load->model('M_discharge_order');
                    $report_data = $this->M_discharge_order->get_discharge_report($startDate, $endDate);
                    break;
                case 'diagnosis':
                    $this->load->model('M_patient_diagnosis');
                    $report_data = $this->M_patient_diagnosis->get_diagnosis_report($startDate, $endDate);
                    break;
                default:
                    $report_data = [];
            }
    
            if (empty($report_data)) {
                echo json_encode(['error' => 'Nenhum dado encontrado para os critérios especificados.']);
                return;
            }
    
            header('Content-Type: application/json');
            $json_data = json_encode($report_data);
            if ($json_data === false) {
                http_response_code(500); 
                echo json_encode(['error' => 'Erro ao gerar resposta JSON.']);
                return;
            }
            echo $json_data;
        } catch (Exception $e) {
            http_response_code(500); 
            echo json_encode(['error' => 'Erro ao gerar relatório: ' . $e->getMessage()]);
        }
    }
    
    public function index()
    {
        $this->report_home();
    }

    public function report_home($year=null,$month=null)
    {
        $data = array();
        if(!$year){
            $year=date('Y');
        }
        if(!$month){
            $month=date('m');
        }
        $data['year']=$year;
        $data['month']=$month;
        $data['calendar']=$this->render($year,$month);
//        $this->load->vars($data);
//        $this->load->view('report_home', 1);
        $this->qch_template->load_form_layout('report_home', $data);
    }





    public function admission_report() {
        $data = [];
        $this->qch_template->load_form_layout('admission_report', $data);
    }




    public function getAdmissionReportData($startDate = null, $endDate = null, $period = null)
    {
        $this->load->model('m_patient_active_list');
    
        $report_data = $this->m_patient_active_list->get_admission_report_by_age_gender_and_reason($startDate, $endDate, $period);
    
        header('Content-Type: application/json');
    
        echo json_encode($report_data);
    }
    


    public function obsevation_medico_report() {
        $data = [];
        $this->qch_template->load_form_layout('observation_medico_report', $data);
    }

    public function getObservationReportData($startDate = null, $endDate = null, $period = null)
    {
        $this->load->model('M_medical_history');
    
        $report_data = $this->M_medical_history->get_doctor_observation_report($startDate, $endDate, $period);
    
        header('Content-Type: application/json');
    
        echo json_encode($report_data);
    }



public function discharge_report() {
    $data = [];
    $this->qch_template->load_form_layout('discharge_report', $data);
}




public function getDischargeReportData($startDate = null, $endDate = null, $period = null)
{
    $this->load->model('M_discharge_order');
    $report_data = $this->M_discharge_order->get_discharge_report($startDate, $endDate, $period);

    header('Content-Type: application/json');

    echo json_encode($report_data);
}



public function diagnosis_report() {
    $data = [];
    $this->qch_template->load_form_layout('diagnosis_report', $data);
}

public function getDiagnosisReportData($startDate = null, $endDate = null, $period = null)
{
    $this->load->model('M_patient_diagnosis');

    $report_data = $this->M_patient_diagnosis->get_diagnosis_report($startDate, $endDate, $period);
    header('Content-Type: application/json');

    echo json_encode($report_data);
}



public function graphyc_medical_report() {
    $data = [];
    $this->qch_template->load_form_layout('graphyc_medical_report', $data);
}

public function getObservationgraphycReportData($startDate = null, $endDate = null, $period = null)
{
    $report_data = $this->M_medical_history->get_dgraphyc_medical_report($startDate, $endDate, $period);

    header('Content-Type: application/json');

    echo json_encode($report_data);
}




public function surveillance_report() {
    $data = [];
    $this->qch_template->load_form_layout('surveillance_report', $data);
}

public function getDiagnosissurveillanceReportData($startDate = null, $endDate = null, $period = null)
{
    $this->load->model('M_patient_diagnosis');
    $report_data = $this->M_patient_diagnosis->get_surveillance_report($startDate, $endDate, $period);
    header('Content-Type: application/json');
    echo json_encode($report_data);
}



    public function show($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array()){

        //// THIS METHOD NOT USED

        $first_of_month = gmmktime(0,0,0,$month,1,$year);
        $day_names = array(); #generate all the day names according to the current locale
        for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
            $day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

        list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
        $weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
        $title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

        @list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
        if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
        if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
        $calendar = '<table class="calendar" border=1 >'."\n".
            '<caption class="calendar-month">'.$p.($month_href ? '<a href="'.htmlspecialchars($month_href).'">'.$title.'</a>' : $title).$n."</caption>\n<tr>";

        if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
            foreach($day_names as $d)
                $calendar .= '<th abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
            $calendar .= "</tr>\n<tr>";
        }

        if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
        for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
            if($weekday == 7){
                $weekday   = 0; #start a new week
                $calendar .= "</tr>\n<tr>";
            }
            if(isset($days[$day]) and is_array($days[$day])){
                @list($link, $classes, $content) = $days[$day];
                if(is_null($content))  $content  = $day;
                $calendar .= '<td'.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
                    ($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
            }
            else $calendar .= "<td>$day</td>";
        }
        if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days

        return $calendar."</tr>\n</table>\n";
    }
    public function render($y,$m){
        if (($y <= 0)||($y <= 2000)||($m <= 0)||($m > 12)){
            $mnt=date('n');
            $yr=date('Y');
        }
        else {
            $mnt=$m;
            $yr=$y;
        }
        //  echo $mnt;
        //     echo $yr;
        // echo date('d',mktime(0,0,0,$mnt,2,$yr))."<br>";
        // echo mktime(0,0,0,$mnt=date('n'),date('d'),date('Y'))."<br>";

        $this->year =$yr;
        $this->month =$mnt;
        $week=date('w', mktime(0,0,0,$mnt,1,$yr));
        $insgesamt=date('t', mktime(0,0,0,$mnt,1,$yr));
        $d=date('d');
        $months=array(lang('January'),lang('February'),lang('March'),lang('April'),lang('May'),lang('June'),lang('July'),lang('August'),lang('September'),lang('October'),lang('November'),lang('December'));
        if(isset($erster)&&$erster==0){$erster=7;}
        $html= '<div style=""><table border=1 cellspacing=5 style="font-size:8pt; font-family:Verdana;border:1px solid #f1f1f1;margin: 15px auto;" align=center width=97% height=500px>';
        $html.= '<th colspan=7 align=center style="font-size:18pt; font-family:Arial; color:#ff9900;text-align: center;">';
        $html.= '<input class="btn btn-default" type=button value="&laquo;"   onclick=self.document.location="'.site_url('report/report_home/'.$this->getYear(-1).'/'.$this->getMonth(-1)).'">&nbsp;';
        $html.= $months[$mnt-1].' '.$yr;
        $html.= '&nbsp;<input class="btn btn-default" type=button value="&raquo;"  onclick=self.document.location="'.site_url('report/report_home/'.$this->getYear(1).'/'.$this->getMonth(1)).'">';
        $html.= '</th>';
        $html.= '<tr><td style="color:#666666"><b>'.lang('Monday').'</b></td><td style="color:#666666"><b>'.lang('Tuesday').'</b></td>';
        $html.= '<td style="color:#666666"><b>'.lang('Wednesday').'</b></td><td style="color:#666666"><b>'.lang('Thursday').'</b></td>';
        $html.= '<td style="color:#666666"><b>'.lang('Friday').'</b></td><td style="color:#0000cc"><b>'.lang('Saturday').'</b></td>';
        $html.= '<td style="color:#cc0000"><b>'.lang('Sunday').'</b></td></tr>';
        $html.= "<tr>\n";
        $i=1;
        if ($week == 0) $week =7;
        while($i<$week){$html.= '<td>&nbsp;</td>'; $i++;}
        $i=1;
        while($i<=$insgesamt)
        {
            $rest=($i+$week-1)%7;
            if($i==$d){
                $html.= '<td style="font-size:8pt; font-family:Verdana; background:#fff1f1;" valign=top align=left>';
            }
            else{
                $html.= '<td style="font-size:8pt; font-family:Verdana" valign=top align=left>';
            }

            if($i==$d){
                $html.= $this->getOption(date('Y-m-d',mktime(0,0,0,$mnt,$i,$yr)),$i);
            }
            else if ($i > $d){
                $html.= '<span style="color:#cccccc;font-size:18pt; ">'.$this->getOption(date('Y-m-d',mktime(0,0,0,$mnt,$i,$yr)),$i).'</span>';
            }
            else if($rest==6){
                $html.= $this->getOption(date('Y-m-d',mktime(0,0,0,$mnt,$i,$yr)),$i);
            }
            else if($rest==0){
                $html.= $this->getOption(date('Y-m-d',mktime(0,0,0,$mnt,$i,$yr)),$i);
            }
            else{
                $html.= $this->getOption(date('Y-m-d',mktime(0,0,0,$mnt,$i,$yr)),$i);
            }
            $html.= "</td>\n";
            if($rest==0){$html.= '</tr><tr>';}
            $i++;
        }
        $html.= '</tr>';
        $html.= '</table></div>';
        return $html;
    }
    private function getOption($dte,$day){
        $ops = "";
        $ops .= "<table border=0 cellspacing=0 cellpading=2 width=100%>";
        $ops .= "<tr>";
        if ($dte == date('Y-m-d')) {
            $ops .= "<td width=15 style='color:#00a84b;font-size:18pt;font-weight:bold;' valign=top align=left>".$day ;
        }
        else {
            $ops .= "<td width=15 style='color:#000000;font-size:18pt;' valign=top align=left>".$day;
        }
        $ops .= "</td>";
        if ($this->config->item('purpose') !="PP"){
        	$ops .= "<td valign=top align=right style='color:#000000;font-size:8pt;'>" ;
        }else{
        	$ops .= "<td valign=top align=right style='color:#000000;font-size:10pt;'>" ;
        }

        $ops .= "<a onclick=\"openWindow('" . site_url(
                "report/pdf/dailyRegistration/print/".$dte
            ) . "')\" href='#'>".lang('Registration')."</a>";

        $ops .= "<br><a onclick=\"openWindow('" . site_url(
                "report/pdf/dailyVisits/print/".$dte
            ) . "')\" href='#'>".lang('OPD Visits')."</a>";

        $ops .= "<br/><a onclick=\"openWindow('" . site_url(
                "report/pdf/dailyActiveList/OPD/print/".$dte
            ) . "')\" href='#'>".lang('OPD Active List')."</a>";
        $ops .= "<br/><a onclick=\"openWindow('" . site_url(
                "report/pdf/dailyActiveList/EMR/print/".$dte
            ) . "')\" href='#'>".lang('EMR Active List')."</a>";
        $ops .= "<br/><a onclick=\"openWindow('" . site_url(
                "report/pdf/dailyReservation/print/".$dte
            ) . "')\" href='#'>".lang('Reservation List')."</a>";

        $ops .= "<br/><a onclick=\"openWindow('" . site_url(
                "report/pdf/dailyDischarges/print/".$dte
            ) . "')\" href='#'>".lang('Discharges')."</a>";
//
        //$ops .= "<br><a onclick=\"openWindow('" . site_url(
        //        "report/pdf/pharmacyBalance/print/".$dte
        //    ) . "')\" href='#'>Drugs dispensed</a><br>";
//        $ops .= "<a onclick=\"openWindow('" . site_url(
//                "report/pdf/dailyClinics/print/".$dte
//            ) . "')\" href='#'>Clinics</a>";
//
//        if ($this->config->item('purpose') !="PP"){
//	        $ops .= "<br><a onclick=\"openWindow('" . site_url(
//	                "report/pdf/midnightCensus/print/".$dte
//	            ) . "')\" href='#'>Midnight Census</a><br>";
//        }
        
//        $ops .= "<a href='#' onclick=printReport('".$dte."','visits');>Visits</a><br>";
//        $ops .= "<a href='#' onclick=printReport('".$dte."','clinics');>Clinics</a><br>";
//        $ops .= "<a href='#' onclick=printReport('".$dte."','admissions');>Admissions</a><br>";
//        $ops .= "<a href='#' onclick=printReport('".$dte."','discharges');>Discharges</a><br>";
//        $ops .= "<a href='#' onclick=printReport('".$dte."','drugsdispensed');>Drugs dispensed</a><br>";
        $ops .= "</td>";
        $ops .= "<tr>";
        $ops .= "</table>";
        return $ops;
    }
    private function getYear($inc){

        $m = $this->month + $inc;
        if ( $m <1 ){
            $y = $this->year-1;
            return $y;
        }
        else if ( $m >12 ){
            $y = $this->year+1;
            return $y;
        }
        else {
            $y = $this->year;
            return $y;
        }
    }
    private function getMonth($inc){
        $m = $this->month + $inc;
        if ($m <= 0 )
            return 12;
        else if ($m > 12 )
            return 1;
        else
            return $m;
    }
}


//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */