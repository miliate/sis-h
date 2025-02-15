<!--<style>-->
<!--    .custom-combobox {-->
<!--        position: relative;-->
<!--        display: inline-block;-->
<!--        width: 100%;-->
<!--    }-->
<!---->
<!--    .custom-combobox-toggle {-->
<!--        position: absolute;-->
<!--        top: 0;-->
<!--        bottom: 0;-->
<!--        margin-left: -1px;-->
<!--        padding: 0;-->
<!--    }-->
<!---->
<!--    .custom-combobox-input {-->
<!--        margin: 0;-->
<!--        padding: 0.3em;-->
<!--        width: 100%;-->
<!--    }-->
<!--</style>-->

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('emergency_visit/info', $ref_id);
                    break;
                case 'ADM':
                    echo Modules::run('admission/info', $ref_id);
                    break;
                case 'OPD':
                    echo Modules::run('opd_visit/info', $ref_id);
                    break;
                default:
                    echo 'wrong department';
                    break;
            }
            $form_generator = new MY_Form(lang('Treatment Order'));
            $form_generator->form_open_current_url();
            $form_generator->dropdown(lang('Treatment'), 'treatment', $treatment_options, $default_treatment);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Remarks');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active);
//            $form_generator->dropdown('Doctor', 'order_confirm_user', Modules::run('order_confirmation/get_doctor'), $this->session->userdata('uid'));
//            $form_generator->password('Confirmation Password', 'order_confirm_password');
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<?php
echo Modules::run('template/footer');
?>

<!--<script>-->
<!--    (function ($) {-->
<!--        $.widget("custom.combobox", {-->
<!--            _create: function () {-->
<!--                this.wrapper = $("<span>")-->
<!--                    .addClass("custom-combobox")-->
<!--                    .insertAfter(this.element);-->
<!---->
<!--                this.element.hide();-->
<!--                this._createAutocomplete();-->
<!--                this._createShowAllButton();-->
<!--            },-->
<!---->
<!--            _createAutocomplete: function () {-->
<!--                var selected = this.element.children(":selected"),-->
<!--                    value = selected.val() ? selected.text() : "";-->
<!---->
<!--                this.input = $("<input>")-->
<!--                    .appendTo(this.wrapper)-->
<!--                    .val(value)-->
<!--                    .attr("title", "")-->
<!--                    .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")-->
<!--                    .autocomplete({-->
<!--                        delay: 0,-->
<!--                        minLength: 0,-->
<!--                        source: $.proxy(this, "_source")-->
<!--                    })-->
<!--                    .tooltip({-->
<!--                        tooltipClass: "ui-state-highlight"-->
<!--                    });-->
<!---->
<!--                this._on(this.input, {-->
<!--                    autocompleteselect: function (event, ui) {-->
<!--                        ui.item.option.selected = true;-->
<!--                        this._trigger("select", event, {-->
<!--                            item: ui.item.option-->
<!--                        });-->
<!--                    },-->
<!---->
<!--                    autocompletechange: "_removeIfInvalid"-->
<!--                });-->
<!--            },-->
<!---->
<!--            _createShowAllButton: function () {-->
<!--                var input = this.input,-->
<!--                    wasOpen = false;-->
<!---->
<!--                $("<a>")-->
<!--                    .attr("tabIndex", -1)-->
<!--                    .attr("title", "Show All Items")-->
<!--                    .tooltip()-->
<!--                    .appendTo(this.wrapper)-->
<!--                    .button({-->
<!--                        icons: {-->
<!--                            primary: "ui-icon-triangle-1-s"-->
<!--                        },-->
<!--                        text: false-->
<!--                    })-->
<!--                    .removeClass("ui-corner-all")-->
<!--                    .addClass("custom-combobox-toggle ui-corner-right")-->
<!--                    .mousedown(function () {-->
<!--                        wasOpen = input.autocomplete("widget").is(":visible");-->
<!--                    })-->
<!--                    .click(function () {-->
<!--                        input.focus();-->
<!---->
<!--                        // Close if already visible-->
<!--                        if (wasOpen) {-->
<!--                            return;-->
<!--                        }-->
<!---->
<!--                        // Pass empty string as value to search for, displaying all results-->
<!--                        input.autocomplete("search", "");-->
<!--                    });-->
<!--            },-->
<!---->
<!--            _source: function (request, response) {-->
<!--                var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");-->
<!--                response(this.element.children("option").map(function () {-->
<!--                    var text = $(this).text();-->
<!--                    if (this.value && ( !request.term || matcher.test(text) ))-->
<!--                        return {-->
<!--                            label: text,-->
<!--                            value: text,-->
<!--                            option: this-->
<!--                        };-->
<!--                }));-->
<!--            },-->
<!---->
<!--            _removeIfInvalid: function (event, ui) {-->
<!---->
<!--                // Selected an item, nothing to do-->
<!--                if (ui.item) {-->
<!--                    return;-->
<!--                }-->
<!---->
<!--                // Search for a match (case-insensitive)-->
<!--                var value = this.input.val(),-->
<!--                    valueLowerCase = value.toLowerCase(),-->
<!--                    valid = false;-->
<!--                this.element.children("option").each(function () {-->
<!--                    if ($(this).text().toLowerCase() === valueLowerCase) {-->
<!--                        this.selected = valid = true;-->
<!--                        return false;-->
<!--                    }-->
<!--                });-->
<!---->
<!--                // Found a match, nothing to do-->
<!--                if (valid) {-->
<!--                    return;-->
<!--                }-->
<!---->
<!--                // Remove invalid value-->
<!--                this.input-->
<!--                    .val("")-->
<!--                    .attr("title", value + " didn't match any item")-->
<!--                    .tooltip("open");-->
<!--                this.element.val("");-->
<!--                this._delay(function () {-->
<!--                    this.input.tooltip("close").attr("title", "");-->
<!--                }, 2500);-->
<!--                this.input.autocomplete("instance").term = "";-->
<!--            },-->
<!---->
<!--            _destroy: function () {-->
<!--                this.wrapper.remove();-->
<!--                this.element.show();-->
<!--            }-->
<!--        });-->
<!--    })(jQuery);-->
<!---->
<!--    $(function () {-->
<!--        $("#treatment").combobox();-->
<!--        $("#toggle").click(function () {-->
<!--            $("#combobox").toggle();-->
<!--        });-->
<!--    });-->
<!--<!--</script>-->-->
