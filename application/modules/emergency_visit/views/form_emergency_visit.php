<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $patient->PID);
            ?>
            <?php
            $form_generator = new MY_Form(lang('Emergency Visit'));
            $form_generator->form_open_current_url();
            $form_generator->hidden_field('pid', $patient->PID);
            //            $form_generator->input('Patient Name', 'patient_name', $patient->Full_Name_Registered, '', 'readonly');
            $form_generator->input(lang('Datetime Visit'), 'date_time_visit', $default_time, '', 'readonly');
            $form_generator->dropdown_severity(lang('Triage'), 'severity', $dropdown_severity, $default_severity);
            $form_generator->input(lang('Complaint / Injury'), 'complaint', $default_complaint, ''); 
            $form_generator->input(lang('Weight in KG'), 'weight', $default_weight, 'eg. 50');
            $form_generator->input(lang('Height in M'), 'height', $default_height, 'eg. 170');
            $form_generator->input_with_default_value_button(lang('sys BP'), 'sys_bp', $default_sys_bp, 120);
            $form_generator->input_with_default_value_button(lang('diast BP'), 'diast_bp', $default_diast_bp, 80);
            $form_generator->input_with_default_value_button(lang('Temperature in *C'), 'temperature', $default_temperature, 36.6);
            $form_generator->input_with_default_value_button(lang('Pulse'), 'pulse', $default_pulse, 120);
            $form_generator->input_with_default_value_button(lang('Saturation'), 'saturation', $default_saturation, 95);
            $form_generator->input_with_default_value_button(lang('Respiratory'), 'respiratory', $default_respiratory, 120);
           
            $form_generator->dropdown(lang('Alert'), 'alert', array('1' => lang('Yes'), '0' => lang('No')), $default_alert);
            $form_generator->dropdown(lang('Voice'), 'voice', array('1' => lang('Yes'), '0' => lang('No')), $default_voice);
            $form_generator->dropdown(lang('Pain'), 'pain', array('1' => lang('Yes'), '0' => lang('No')), $default_pain);
            $form_generator->text_area(lang('Un-Responsive'), 'remarks', '', 'Escala de Glasgow  (3 a 15), avaliação (Ocular, Verbal, Motora)');
            $form_generator->dropdown(lang('Area in Emergency Department'), 'destination', $dropdown_area, $default_destination);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
       
            $form_generator->button_submit_reset(0);
            $form_generator->form_close();
            ?>
        </div>
    </div>
    <div id="pop1" class="popbox"><img src="<?php echo base_url('images/severity.png') ?>" width="503" height="195"></div>


</div>

<script>
    $(function () {
        function split(val) {
            return val.split(/,\s*/);
        }

        function extractLast(term) {
            return split(term).pop();
        }

        $("#complaint")
        // don't navigate away from the field on tab when selecting an item
            .bind("keydown", function (event) {
                if (event.keyCode === $.ui.keyCode.TAB &&
                    $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function (request, response) {
                    $.getJSON("<?php echo site_url() ?>/complaints/search/" + extractLast(request.term), {}, response);
                },
                search: function () {
                    // custom minLength
                    var term = extractLast(this.value);
                    if (term.length < 2) {
                        return false;
                    }
                },
                focus: function () {
                    // prevent value inserted on focus
                    return false;
                },
                select: function (event, ui) {
                    var terms = split(this.value);
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push(ui.item.value);
                    // add placeholder to get the comma-and-space at the end
                    terms.push("");
                    this.value = terms.join(", ");
                    return false;
                }
            });
    });


    var moveLeft = 0;
    var moveDown = 0;
    $('a.popper').hover(function (e) {

        var target = '#' + ($(this).attr('data-popbox'));
        $(target).show();
        moveLeft = $(this).outerWidth();
        moveDown = ($(target).outerHeight() / 2);
    }, function () {
        var target = '#' + ($(this).attr('data-popbox'));
        if (!($("a.popper").hasClass("show"))) {
            $(target).hide();
        }
    });

    $('a.popper').mousemove(function (e) {
        var target = '#' + ($(this).attr('data-popbox'));

        leftD = e.pageX + parseInt(moveLeft);
        maxRight = leftD + $(target).outerWidth();
        windowLeft = $(window).width() - 40;
        windowRight = 0;
        maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);

        if (maxRight > windowLeft && maxLeft > windowRight) {
            leftD = maxLeft;
        }

//        topD = e.pageY - parseInt(moveDown);
//        maxBottom = parseInt(e.pageY + parseInt(moveDown) + 20);
//        windowBottom = parseInt(parseInt($(document).scrollTop()) + parseInt($(window).height()));
//        maxTop = topD;
//        windowTop = parseInt($(document).scrollTop());
//        if (maxBottom > windowBottom) {
//            topD = windowBottom - $(target).outerHeight() - 20;
//        } else if (maxTop < windowTop) {
//            topD = windowTop + 20;
//        }

        $(target).css('top', 330).css('left', leftD);
    });
//    $('a.popper').click(function (e) {
//        var target = '#' + ($(this).attr('data-popbox'));
//        if (!($(this).hasClass("show"))) {
//            $(target).show();
//        }
//        $(this).toggleClass("show");
//    });

//    <!--<script type="text/javascript">-->
//    <!--    function CheckDestination(val){-->
//    <!--//        var element=document.getElementById('destination1');-->
//    <!--        var currentdate = new Date();-->
//    <!--        var datetime = currentdate.getFullYear() + "/"-->
//    <!--            + (currentdate.getMonth()+1)  + "/"-->
//    <!--            + currentdate.getDate() + " "-->
//    <!--            + currentdate.getHours() + ":"-->
//    <!--            + currentdate.getMinutes() + ":"-->
//    <!--            + currentdate.getSeconds();-->
//    <!---->
//    <!--        if(val=='Discharged'){-->
//    <!--            $('#destination1').attr('placeholder', 'Notes on discharge');-->
//    <!--            $('#destination1').attr('value', '');-->
//    <!--        }-->
//    <!--        else if (val=='Appointment for') {-->
//    <!--            $('#destination1').attr('placeholder', 'Details');-->
//    <!--            $('#destination1').attr('value', '');-->
//    <!--        }-->
//    <!--        else if (val=='Admission on ward') {-->
//    <!--            $('#destination1').attr('placeholder', 'Ward number');-->
//    <!--            $('#destination1').attr('value', '');-->
//    <!--        } else {-->
//    <!--            $('#destination1').attr('value', datetime);-->
//    <!--        }-->
//    <!--    }-->
//    <!---->
//    <!--
</script>



</script>
