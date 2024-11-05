<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator = new MY_Form(lang('Observe Pathological Anatomy Patient'));
            $form_generator->form_open_current_url();
            $form_generator->hidden_field('pid', $pid);
            $form_generator->input(lang('Doctor'), 'doctor', $default_doctor, '', 'readonly');
            $form_generator->input(lang('Datetime Visit'), 'date_time_visit', date("Y-m-d H:i:s"), '', 'readonly');
            $form_generator->input('*' . lang('Complaint / Injury'), 'complaint', $default_complaint, '');
            $form_generator->text_area(lang('Remarks', 'remarks'), 'remarks', $default_remarks, '');
            $form_generator->button_submit_reset(0);
            $form_generator->form_close();
            ?>
        </div>
    </div>
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
</script>