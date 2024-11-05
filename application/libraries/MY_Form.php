<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 2:26 PM
 * Description: This class contains all HTML input types that combine with Codeigniter and php.
   Codeigniter and php will get data from database and then display to these HTML input types.
   Furthermore, Codeigniter and php also can get data from these HTML input types and save in database.
   You can develop more HTML input types to develop HIS.
   See more about HTML input types: https://www.w3schools.com/html/html_form_input_types.asp .
 */


class MY_Form
{
    var $_name;
    var $type;

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct($name = 'Form')
    {
        $this->_name = $name;
    }

    /**
     * Submit form to current URL including GET parameters
     * See more about HTTP Request Methods: https://www.w3schools.com/tags/ref_httpmethods.asp
     */
    public function form_open_current_url()
    {
        echo validation_errors();
        $form_attributes = array(
            'class' => 'form-horizontal',
            'role' => 'form'
        );
        echo '<div class="well">';
        echo form_open(current_url() . "?" . $_SERVER['QUERY_STRING'], $form_attributes);
        echo '<fieldset>';
        echo '<legend>' . $this->_name . '</legend>';
    }

    public function form_open_current_url_upload()
    {
        echo validation_errors();
        $form_attributes = array(
            'class' => 'form-horizontal',
            'role' => 'form',
            'enctype' => 'multipart/form-data'
        );
        echo '<div class="well">';
        echo form_open(current_url() . "?" . $_SERVER['QUERY_STRING'], $form_attributes);
        echo '<fieldset>';
        echo '<legend>' . $this->_name . '</legend>';
    }

    /**
     * Close a form
     */
    public function form_close()
    {
        echo '</fieldset>';
        echo form_close();
        echo '</div>';
    }

    /**
     * Add HTML <legend> tag to form
     */
    public function legend($text = '')
    {
        echo '<legend style="font-size: 15px; color: #0d61a9">' . $text . '</legend>';
    }

    /**
     * Add HTML <hr> tag to form
     */
    public function hr()
    {
        echo '<hr>';
    }

    /**
     * Add HTML <input> tag to form
     * @param $label            (string)        Label for input
     * @param $name             (string)        Name of input
     * @param $default_value    (string)        Default value that is got from database
     * @param $place_holder     (string)        Place holder for html input tag
     * @param $extra            (string)        extra attributes for input tag
     */
    public function input($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_input($data_text, set_value($name, $default_value), $extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    public function input_number($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'type'  => 'number',
            'class' => 'form-control input-sm',
            'name'  => $name,
            'id'    => $name,
            'placeholder' => $place_holder,
            'min'   => '0',
            'step' => '0.01'
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_input($data_text, set_value($name, $default_value), $extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    public function input_1($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-1 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_input($data_text, set_value($name, $default_value), $extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add HTML <input> tag with search button to form
     * @param $label            (string)        Label for input
     * @param $name             (string)        Name of input
     * @param $default_value    (string)        Default value that is got from database
     * @param $place_holder     (string)        Place holder for html input tag
     * @param $extra            (string)        extra attributes for input tag
     */
    public function input_with_search($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<div class="input-group">';
        echo form_input($data_text, set_value($name, $default_value), $extra);
        echo '<div class="input-group-btn"><button id="' . $name . '_btn_search" type="button" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-search"></span>  ' . lang('Search') . '</button></div>';
        echo '</div>';
        echo '<div id="' . $name . '_search_result"></div>';
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add HTML <input> tag with unit to form
     * @param $unit             (string)        Unit of data
     * @param $label            (string)        Label for input
     * @param $name             (string)        Name of input
     * @param $default_value    (string)        Default value that is got from database
     * @param $place_holder     (string)        Place holder for html input tag
     * @param $extra            (string)        extra attributes for input tag
     */
    public function input_with_unit($unit, $label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<div class="input-group">';
        echo form_input($data_text, set_value($name, $default_value), $extra);
        echo '<span class="input-group-addon">' . $unit . '</span>';
        echo '</div>';
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add HTML <input> tag with unit to form
     * @param $label            (string)        Label for input
     * @param $name             (string)        Name of input
     * @param $default_value    (string)        Default value that is got from database
     * @param $place_holder     (string)        Place holder for html input tag
     * @param $checkbox_label   (string)        label of checkbox
     * @param $default_checked  (bool)         Checkbox is checked or not
     */
    public function input_inline_checkbox($label = '', $name = '', $default_value = '', $place_holder = '', $checkbox_label, $default_checked)
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<div class="input-group">';
        echo form_input($data_text, set_value($name, $default_value));
        echo '<span class="input-group-addon">';
        echo form_hidden($name . '_checkbox', 0);
        echo form_checkbox($name . '_checkbox', 1, set_value($name . '_checkbox', $default_checked), 'id="' . $name . '_checkbox' . '"');

        echo ' ' . $checkbox_label;
        echo '</span>';
        echo '</div>';
        echo form_error($name);
        echo form_error($name . '_checkbox');
        echo '</div>';
        echo '</div>';
        echo '
        <script>
            if ($("#' . $name . '_checkbox' . '").prop("checked")) {
            $(":input[name=\'' . $name . '\']").prop("disabled", true);
        }
        </script>';
    }

    /**
     * Add a hidden field to form
     * @param $name             (string)        Name of field
     * @param $default_value    (string)        Default value that got from database
     */
    public function hidden_field($name = '', $default_value = '')
    {
        echo form_hidden($name, $default_value);
    }

    /**
     * Add HTML <input> tag with unit to form
     * @param $label                (string)        Label for input
     * @param $name                 (string)        Name of input
     * @param $default_value        (string)        Default value that is got from database
     * @param $button_click_value   (string)        Value of button
     */
    public function input_with_default_value_button($label = '', $name = '', $default_value = '', $button_click_value)
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<div class="input-group">';
        echo form_input($data_text, set_value($name, $default_value));
        echo '<span class="input-group-btn">';
        echo '<button type="button" class="btn btn-default" onclick="$(\'#' . $name . '\').val(' . $button_click_value . ');">';
        echo '<span class="glyphicon glyphicon-thumbs-up pull-right"></span>';
        echo '</button>';
        echo '</span>';
        echo '</div>';
        echo '</div>';
        echo form_error($name);
        echo '</div>';
    }

    /**
     * Add HTML <input> that can select date to form
     * @param $label                (string)        Label for input
     * @param $name                 (string)        Name of input
     * @param $default_value        (string)        Default value that is got from database
     */
    public function input_date($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $js = $extra . ' onmousedown="onmousedown=$(\'#' . $name . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-100:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+0D\'});"';
        $this->input($label, $name, $default_value, $place_holder, $js);
    }

    public function input_date_only_future($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $js = $extra . ' onmousedown="onmousedown=$(\'#' . $name . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-100:c+40\',dateFormat: \'yy-mm-dd\',minDate: \'+0D\'});"';
        $this->input($label, $name, $default_value, $place_holder, $js);
    }

    public function input_date_future(
        $label = '',
        $name = '',
        $default_value = '',
        $place_holder = '',
        $extra = ''
    ) {
        $js = $extra . ' onmousedown="onmoused-1own=$(\'#' . $name . '\').
        datepicker({changeMonth: true,changeYear: true,yearRange: \'c-100:c+40\',dateFormat: \'yy-mm-dd\'});"';
        $this->input($label, $name, $default_value, $place_holder, $js);
    }

    public function input_date_future_1(
        $label = '',
        $name = '',
        $default_value = '',
        $place_holder = '',
        $extra = ''
    ) {
        $js = $extra . ' onmousedown="onmousedown=$(\'#' . $name . '\').
        datepicker({changeMonth: true,changeYear: true,yearRange: \'c-100:c+40\',dateFormat: \'yy-mm-dd\'});"';
        $this->input_1($label, $name, $default_value, $place_holder, $js);
    }

    /**
     * Add HTML <input> that can select date and time to form
     * @param $label                (string)        Label for input
     * @param $name                 (string)        Name of input
     * @param $default_value        (string)        Default value that is got from database
     */
    public function input_date_and_time($label = '', $name = '', $default_value = '', $place_holder = '')
    {
        $tmp = explode(' ', $default_value);
        if (sizeof($tmp) == 2) {
            $default_date = $tmp[0];
            $default_time = $tmp[1];
        } else {
            $default_date = '';
            $default_time = '';
        }
        $js = 'onmousedown="onmousedown=$(\'#' . $name . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-100:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+0D\'});"';
        $this->input($label, $name, $default_date, $place_holder, $js);
        echo '<div class="form-group">';
        echo '<label class="col-sm-2 control-label"></label>';
        echo '<div class="col-sm-10">';
        echo '<input id = "' . $name . '_time" class="form-control input-sm" type="time" name="' . $name . '_time" value="' . set_value($name . '_time', $default_time) . '">';
        echo form_error($name);
        echo form_error($name . '_time');
        echo '</div>';
        echo '</div>';
    }



    /**
     * Add HTML <textarea> tag to form
     */
    public function text_area($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text_aria = array(
            'class' => 'form-control input-md',
            'name' => $name,
            'id' => $name,
            'rows' => 2,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_textarea($data_text_aria, set_value($name, $default_value), $extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    public function text_area_lg($label = '', $name = '', $default_value = '', $place_holder = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text_aria = array(
            'class' => 'form-control input-md',
            'name' => $name,
            'id' => $name,
            'rows' => 10,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_textarea($data_text_aria, set_value($name, $default_value), $extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add HTML <input> tag with type="password" to form
     */
    public function password($label = '', $name = '', $default_value = '', $place_holder = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_password($data_text, set_value($name, $default_value));
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    public function password_small($label = '', $name = '', $default_value = '', $place_holder = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_text = array(
            'class' => 'form-control input-sm',
            'name' => $name,
            'id' => $name,
            'placeholder' => $place_holder
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-6">';
        echo form_password($data_text, set_value($name, $default_value));
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add button to form
     * @param $label        (string)        Label for button
     * @param $content      (string)        Content inside button
     * @param $class        (string)        Set class for button (css)
     * @param $url          (string)        url to redirect after clicking button
     */
    public function button($label = '', $content = '', $class = '', $url = '#')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $data_button = array(
            'class' => 'btn ' . $class,
        );
        echo '<div class="form-group">';
        echo form_label($label, $content, $data_label);
        echo '<div class="col-sm-10">';
        echo '<a href="' . $url . '">';
        echo form_button($data_button, $content);
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add dropdown list to form
     * @param $label            (string)        Label for button
     * @param $name             (string)        Name of dropdown list
     * @param $option           (array)         All options for dropdown list
     * @param $selected_value   (string)        Selected value of dropdown list
     */
    public function dropdown($label = '', $name = '', $option = array(), $selected_value = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $dropdown_extra = 'class="form-control input-sm" name="' . $name . '" id="' . $name . '" ' . $extra;
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_dropdown($name, $option, set_value($name, $selected_value), $dropdown_extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    public function dropdown_small($label = '', $name = '', $option = array(), $selected_value = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $dropdown_extra = 'class="form-control input-sm" name="' . $name . '" id="' . $name . '" ' . $extra;
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_dropdown($name, $option, set_value($name, $selected_value), $dropdown_extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add checkboxes to form
     * @param $label            (string)        Label for button
     * @param $name             (string)        Name of checkboxes
     * @param $options          (array)         All options for checkboxes
     * @param $checked_value    (array)         Checked values of checkboxes
     */
    public function checkboxes($label = '', $name = '', $options = array(), $checked_value = array())
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label'
        );
        $data_checkbox = array(
            'name' => $name . '[]',
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<div class="well well-sm" style="background: white">';
        foreach ($options as $id => $value) {
            echo '<div class = "checkbox">';
            echo '<label>';
            echo form_checkbox($data_checkbox, $id, set_checkbox($name, $id, in_array($id, $checked_value)));
            echo $value;
            echo '</label>';
            echo '</div>';
        }
        echo form_error($name);
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add checkboxes for pathological anatomy module to form. You can select multiple options.
     * @param $label            (string)        Label for button
     * @param $name             (string)        Name of checkboxes
     * @param $options          (array)         All options for checkboxes
     * @param $checked_value    (array)         Checked values of checkboxes
     * @param $default_value    (array)         Default values for inputs
     * @param $group            (string)        "CYTOLOGY OF LIQUIDS" group or "PAAF" group
     */
    public function checkboxes_pa($label = '', $name = '', $options = array(), $checked_value = array(), $default_value = array(), $group = 1, $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label'
        );
        $data_checkbox = array(
            'name' => $name . '[]',
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<div class="well well-sm" style="background: white">';
        foreach ($options as $id => $value) {
            echo '<div class = "checkbox">';
            echo '<label>';
            echo form_checkbox($data_checkbox, $id, set_checkbox($name, $id, in_array($id, $checked_value)), $extra);
            echo $value;
            if ($id == 'Washes') {
                echo '<input type="input" value="' . $default_value[0] . '" name="' . strtolower($id) . '_info' . '" class="form-control input-sm" id="' . strtolower($id) . '_info' . '" style="display: none;" ' . $extra . '>';
            } elseif ($id == 'Others_Liquid') {
                echo '<input type="input" value="' . $default_value[1] . '" name="' . strtolower($id) . '_info' . '" class="form-control input-sm" id="' . strtolower($id) . '_info' . '" style="display: none;" ' . $extra . '>';
            } elseif ($id == 'Ganglion') {
                echo '<input type="input" value="' . $default_value[2] . '" name="' . strtolower($id) . '_info' . '" class="form-control input-sm" id="' . strtolower($id) . '_info' . '" style="display: none;" ' . $extra . '>';
            } elseif ($id == 'Soft_Tissues') {
                echo '<input type="input" value="' . $default_value[3] . '" name="' . strtolower($id) . '_info' . '" class="form-control input-sm" id="' . strtolower($id) . '_info' . '" style="display: none;" ' . $extra . '>';
            } elseif ($id == 'Others_PAAF') {
                echo '<input type="input" value="' . $default_value[4] . '" name="' . strtolower($id) . '_info' . '" class="form-control input-sm" id="' . strtolower($id) . '_info' . '" style="display: none;" ' . $extra . '>';
            }
            echo '</label>';
            echo '</div>';
        }
        if ($group == 1) {
            echo '<div class = "checkbox">';
            echo '<label for="clinical_diagnosis_liquid"><b>' . lang('Clinical Diagnosis') . '</b></label>';
            echo '<input type="input" value="' . $default_value[5] . '" name="clinical_diagnosis_liquid" class="form-control input-sm" id="clinical_diagnosis_liquid" ' . $extra . '>';
            echo '</div>';
        } else {
            echo '<div class = "checkbox">';
            echo '<label for="clinical_diagnosis_liquid"><b>' . lang('Clinical Information / Diagnosis') . '</b></label>';
            echo '<input type="input" value="' . $default_value[6] . '" name="clinical_diagnosis_PAAF" class="form-control input-sm" id="clinical_diagnosis_PAAF" ' . $extra . '>';
            echo '</div>';
        }
        echo form_error($name);
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add checkboxes for pathological anatomy module to form. You can select multiple options.
     * @param $label            (string)        Label for button
     * @param $name             (string)        Name of checkboxes
     * @param $options          (array)         All options for checkboxes
     * @param $checked_value    (array)         Checked values of checkboxes
     * @param $default_value    (array)         Default values for inputs
     */
    public function checkboxes_cv($label = '', $name = '', $options = array(), $checked_value = array(), $default_value = array(), $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label'
        );
        $data_checkbox = array(
            'name' => $name . '[]',
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<div class="well well-sm" style="background: white">';
        foreach ($options as $id => $value) {
            echo '<div class = "checkbox">';
            echo '<label>';
            echo form_checkbox($data_checkbox, $id, set_checkbox($name, $id, in_array($id, $checked_value)), $extra);
            echo $value;
            if ($id == 'Contraception_Other') {
                echo '<input type="input" value="' . $default_value[0] . '" name="' . strtolower($id) . '_info' . '" class="form-control input-sm" id="' . strtolower($id) . '_info' . '" style="display: none;" ' . $extra . '>';
            } elseif ($id == 'Tratamento_Anterior_Other') {
                echo '<input type="input" value="' . $default_value[1] . '" name="' . strtolower($id) . '_info' . '" class="form-control input-sm" id="' . strtolower($id) . '_info' . '" style="display: none;" ' . $extra . '>';
            }
            echo '</label>';
            echo '</div>';
        }
        echo form_error($name);
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add radio to form. You only can select one option.
     * @param $label            (string)        Label for button
     * @param $name             (string)        Name of radio
     * @param $options          (array)         All options for radio
     * @param $checked_value    (string)        Checked value of radio
     */
    public function radio($label = '', $name = '', $options = array(), $checked_value = '', $extra = '', $default_value = '')
    {
        $data_label = array(
            'class' => 'col-sm-3 control-label'
        );
        $data_checkbox = array(
            'name' => $name,
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-9">';
        echo '<div class="well well-sm" style="background: white">';
        foreach ($options as $id => $value) {
            echo '<div class = "radio">';
            echo '<label>';
            echo form_radio($data_checkbox, $id, set_radio($name, $id, $id == $checked_value), $extra);
            echo $value;
            if ($value == "Outro") {
                echo '<input type="input" value="' . $default_value . '" name="sample_taken_by_info" class="form-control input-sm" id="sample_taken_by_info" style="display: none;" ' . $extra . '>';
            }
            echo '</label>';
            echo '</div>';
        }
        echo form_error($name);
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add button "Submit", "back", and "reset" to form. Click "Submit" to save data from form to database.
       Click "back" to go back to previous page. Click "reset" to reset form to default or empty.
     */
    public function button_submit_reset($id = '')
    {
        echo '<div class="form-group">';
        echo '    <div class="col-sm-offset-2 col-sm-10">';
        echo '        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> ' . lang('Save') . '</button>';
        echo '        <button type="button" class="btn btn-warning" onclick="window.history.back();"><span class="glyphicon glyphicon-remove"></span> ' . lang('Cancel') . '</button>';
        echo '        <button  id="resetButton" type = "reset" class="btn btn-success" ><i class="fa fa-refresh"></i> ' . lang('Reset') . '</button>';
        echo '    </div>';
        echo '</div>';
    }

    public function button_back()
    {
        echo '<div class="form-group">';
        echo '    <div class="col-sm-12 text-center">';
        echo '        <button type="button" class="btn btn-warning" onclick="window.history.back();">';
        echo '            <span class="glyphicon glyphicon-arrow-left"></span> ' . lang('Back');
        echo '        </button>';
        echo '    </div>';
        echo '</div>';
    }



    /**
     * Add multiple_select_box to form.
     * @param $label            (string)        Label for button
     * @param $name             (string)        Name of radio
     * @param $option           (array)         All options for multiple_select_box
     * @param $selected_value   (string)        Selected value of multiple_select_box
     */
    public function multiple_select_box($label = '', $name = '', $option = array(), $selected_value = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $extra = 'class="form-control input-sm" id="' . $name . '"';
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_multiselect($name, $option, $selected_value, $extra);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add checkbox to confirm something to form.
     * @param $label            (string)        Label for button
     * @param $name             (string)        Name of checkboxes
     * @param $default_value    (array)         Default values for inputs
     */
    public function checkbox_confirm($label = '', $name = '', $default_value)
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo form_checkbox($name, '1', $default_value);
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add dropdown with display way and value to form
     */
    public function dropdown_reason($label = '', $name = '', $option = array(), $selected_value = '', $extra = '', $display = '', $value = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $dropdown_extra = 'class="form-control input-sm" id="' . $name . '" ' . $extra;
        echo '<div class="form-group" style="height:75px;">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10" style="height:30px">';
        echo form_dropdown($name, $option, $selected_value, $dropdown_extra);
        echo form_error($name);
        echo '<input type="text" value="' . $value . '" name="hos_reason" id="hos_reason" style="display:' . $display . '; width:573px; height:30px; margin: 10px 0px; border: solid red 1px;" placeholder="Please enter a reason"  />';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add another dropdown to form.
     */
    public function dropdown_reason_1($label = '', $name = '', $option = array(), $selected_value = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $dropdown_extra = 'class="form-control input-sm" id="' . $name . '" ' . $extra;
        echo '<div class="form-group" style="height:75px;">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10" style="height:30px">';
        echo form_dropdown($name, $option, $selected_value, $dropdown_extra);
        echo form_error($name);
        echo '<input type="text" name="hos_reason" id="hos_reason" style="display:none; width:573px; height:30px; margin: 10px 0px; border: solid red 1px;" placeholder="Please enter a reason"  />';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add another dropdown to form.
     */
    public function dropdown_destination($label = '', $name = '', $option = array(), $selected_value = '', $extra = '', $detail = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $dropdown_extra = 'class="form-control input-sm" id="' . $name . '" ' . $extra;
        echo '<div class="form-group" style="height:75px;">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10" style="height:30px">';
        echo form_dropdown($name, $option, $selected_value, $dropdown_extra);
        echo form_error($name);
        echo '<input type="text" name="destination1" id="destination1" style="display:block; width:573px; height:30px; margin: 10px 0px; border: solid red 1px;" placeholder="' . $detail . '"  />';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add another dropdown to form.
     */
    public function dropdown_destination1($label = '', $name = '', $option = array(), $selected_value = '', $extra = '', $detail = '', $value = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $dropdown_extra = 'class="form-control input-sm" id="' . $name . '" ' . $extra;
        echo '<div class="form-group" style="height:75px;">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10" style="height:30px">';
        echo form_dropdown($name, $option, $selected_value, $dropdown_extra);
        echo form_error($name);
        echo '<input type="text" name="destination1" id="destination1" style="display:block; width:573px; height:30px; margin: 10px 0px; border: solid red 1px;" value="' . $value . '" placeholder="' . $detail . '"  />';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add another dropdown to form.
     */
    public function dropdown_severity($label = '', $name = '', $option = array(), $selected_value = '', $extra = '')
    {
        $data_label = array(
            'class' => 'col-sm-2 control-label',
        );
        $dropdown_extra = 'class="form-control input-sm" id="' . $name . '" ' . $extra;
        echo '<div class="form-group">';
        echo form_label($label, $name, $data_label);
        echo '<div class="col-sm-10">';
        echo '<a href="#" class="popper" data-popbox="pop1">';
        echo form_dropdown($name, $option, $selected_value, $dropdown_extra);
        echo '</a>';
        echo form_error($name);
        echo '</div>';
        echo '</div>';
    }

    /**
     * Add another dropdown to form.
     */
    public function diagnosis($label = '', $name = '', $selected_value = '')
    {
        $icd_options = Modules::run('patient_diagnosis/get_all_icd10');
        $icd_js = '<script type="text/javascript">
                        $(document).ready(function () {
                            $("#' . $name . '").select2();
                        });
                    </script>';
        $this->dropdown($label, $name, $icd_options, $selected_value);
        echo $icd_js;
    }

    public function get_pharmacy_manager_reports_menu()
    {
        echo
        '<ul class="nav nav-tabs justify-content-center">' .
            '<li ><a href="../drug_stock/view">' . lang('Stock Report') . '</a></li>' .
            ' <li><a href="../drug_stock/drug_entries">' . lang('Medication Entry Report') . '</a></li>' .
            '<li><a href="../drug_stock/drug_exits">' . lang('Medication Exit Report') . '</a></li>' .
            '<li><a href="../drug_stock/drug_sales">' . lang('Drug Sales Roprt') . '</a></li>' .
            '<li><a href="' . site_url('drug_stock/drug_sales') . '">' . lang('Sales Roprt') . '</a></li>' .


            ' </ul>';
    }

    public function get_pharmacy_dispense_menu()
    {
        echo '<ul class="nav nav-tabs justify-content-center">
        <li><a href="' . site_url('patient_prescription') . '">' . lang('Prescription') . '</a></li>
        <li><a href="' . site_url('patient_external_prescription/dispense') . '">' . lang('External Prescription') . '</a></li>
      </ul>';
    }

    public function get_pnct_forms_menu($id)
    {
        echo
        '<ul class="nav nav-tabs justify-content-center">' .
            '<li><a href="' . site_url('patient_pnct/index/' . $id) . '">' . lang('Patient Information') . '</a></li>' .
            '<li><a href="' . site_url('pnct/show_tb_characterization/' . $id) . '">' . lang('Characterization') . '</a></li>' .
            '<li><a href="' . site_url('tb_treatment_history/add/' . $id) . '">' . lang('Treatment History') . '</a></li>' .
            '<li><a href="' . site_url('pnct/add/' . $id) . '">' . lang('Comorbidities') . '</a></li>' .
            '<li><a href="' . site_url('diabetes_screening/add/' . $id) . '">' . lang('Diabetes Screening Form') . '</a></li>' .
            '<li><a href="' . site_url('hiv_screening/add/' . $id) . '">' . lang('HIV Screening Form') . '</a></li>' .
            '</ul>';
    }

    public function get_clinical_diary_menu($id, $ref_id)
    {
        echo
        '<ul class="nav nav-tabs justify-content-center">' .
            '<li><a href="' . site_url('patient_history/create_adm_history/' . $id . '/' . $ref_id . '/?CONTINUE=/clinical_diary/add/' . $id . '/' . $ref_id) . '">' . lang('Clinic History') . '</a></li>' .
            '<li><a href="' . site_url('patient_examination/create_adm_examination/' . $id . '/' . $ref_id . '/?CONTINUE=/clinical_diary/add/' . $id . '/' . $ref_id) . '">' . lang('Examinations') . '</a></li>' .
            '</ul>';
    }
    public function get_nurse_diary_menu($id, $ref_id)
    {
        echo
        '<ul class="nav nav-tabs justify-content-center">' .
            '<li><a href="' . site_url('patient_note/add_nurse_entry/' . $id . '/' . $ref_id) . '">' . lang('Clinic History') . '</a></li>' .
            '<li><a href="' . site_url('patient_examination/create_inw_nurse_examination/' . $id . '/' . $ref_id) . '">' . lang('Examinations') . '</a></li>' .
            '</ul>';
    }

    public function get_nursing_note_menu($id, $ref_id)
    {
        echo
        '<ul class="nav nav-tabs justify-content-center" style="margin-bottom: 20px;">' .
            '<li><a href="' . site_url('patient_note/add_general_note/' . $id . '/?CONTINUE=patient/view/' . $id) . '">' . lang('Add Nursing notes') . '</a></li>' .
            '<li><a href="' . site_url('patient_examination/create_adm_examination/' . $id . '/' . $ref_id . '/?CONTINUE=/clinical_diary/add/' . $id . '/' . $ref_id) . '">' . lang('Examinations') . '</a></li>' .
            '</ul>';
    }


    public function get_nursing_notes_tab($id, $ref_id)
    {
        echo
        '<ul class="nav nav-tabs justify-content-center" style="margin-bottom: 20px;">' .
            '<li><a href="' . site_url('patient_note/add_nurse_note/' . $id . '/' . $ref_id  . '/?CONTINUE=patient/view/' . $id . '/' . $ref_id) . '">' . lang('Nursing Note') . '</a></li>' .
            '<li><a href="' . site_url('treatment_order/nursing_care/' . $ref_id . '/Care') . '">' . lang('Nursing Cares') . '</a></li>' .
            '<li><a href="' . site_url('treatment_order/nursing_care/' . $ref_id . '/Procedure') . '">' . lang('Nursing Procedures') . '</a></li>' .
            '</ul>';
    }
}
