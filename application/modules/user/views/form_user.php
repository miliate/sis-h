<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('User'));
            $form_generator->form_open_current_url();
            if ($id > 0)
                $form_generator->input('*' . lang('Username'), 'username', $default_username, 'User name', 'disabled');
            else
                $form_generator->input('*' . lang('Username'), 'username', $default_username, 'User name');
            $form_generator->dropdown('*' . lang('Title'), 'title', array('Mr.' => 'Mr.', 'Mrs.' => 'Mrs.'), $default_title);
            $form_generator->input('*' . lang('Name'), 'name', $default_name, lang('Name'));
            $form_generator->input(lang('Other Name'), 'other_name', $default_other_name,lang('Other Name'));
            $form_generator->input_date(lang('Date of Birth'), 'date_of_birth', $default_date_of_birth, lang('Date of Birth'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->dropdown(lang('Gender'), 'gender', array('Male' => 'Male', 'Female' => 'Female'), $default_gender);
            if ($id === 0) {
                $form_generator->password('*' . lang('Password'), 'password', '', lang('Password'));
                $form_generator->password('*'.lang('Confirm password'), 'password_check', '', 'Password Confirmation');//adicionei confirmar senha
            } else {
                $form_generator->button(lang('Password'), 'Change User Password', 'btn-default', site_url('/user/change_password/' . $id));
//                $form_generator->button($this->lang->line('form_label_password'), 'Change Order Password', 'btn-default', site_url('/user/change_password/' . $id . '?password_type=2'));
            }
            ?>
            <div class="form-group">
                <label for="userGroup" class="col-sm-2 control-label">*<?php echo lang('User Groups') ?></label>

                <div class="col-sm-10">
                    <?php
                    $i = 0;
                    foreach ($departments as $department) {
                        $i++;
                        echo '<label for="user_groups" class="control-label">' . $department . '</label>';
                        foreach ($userGroups as $userGroup)
                            if ($userGroup ['DEP_NAME'] == $department) {
                                echo '<div class="checkbox">';
                                echo '<label>';
//                                                     echo '<input type="checkbox" value="' . $userGroup['UGID'] . '" ';
//                                                     echo 'name = "userGroups[' . $i . ']"';
//                                                     echo set_checkbox('userGroups[' . $i . ']', $userGroup['UGID'], in_array($userGroup['UGID'], $default_userGroups));
//                                                     echo '>';
//                                                     echo $userGroup ['UG_NAME'];
                                echo '<input type="checkbox" value="' . $userGroup['UGID'] . '" ';
                                echo 'name = "user_groups[]"';
                                echo set_checkbox('user_groups', $userGroup['UGID'], in_array($userGroup['UGID'], $default_userGroups));
                                echo '>';
                                echo $userGroup ['UG_NAME'];
                                echo '</label>';
                                echo '</div>';
                            }
                    }
                    ?>
                    <?php echo form_error("user_groups[]"); ?>
                </div>
            </div>
            <?php
            $form_generator->input($this->lang->line('Address'), 'address', $default_address, 'eg. No 32/2');
            $form_generator->input($this->lang->line('Village'), 'village', $default_village, lang('Village'));
            $form_generator->dropdown($this->lang->line('Language'), 'language', array('english' => 'English', 'portuguese' => 'Portuguese'), $default_language);
            $form_generator->button_submit_reset($id);
            $form_generator->form_close();
            ?>
        </div>
    </div>
    <script type="text/javascript">
        //        $("input:checkbox[name='user_groups[]']").parent().children().each(function () {
        //            console.log($(this));
        //        });
        //        department = null;
        //        user_group = {};
        //
        //        $("label[for='userGroup'] + div").children().each(function () {
        //            if ($(this)[0].nodeName === 'LABEL') {
        //                department = $(this).text();
        //            } else {
        //                console.log($(this).children('input:checkbox'));
        //            }
        //        });

        //        $("input:checkbox[name='user_groups[]']").click(function () {
        //            if ($(this).is(":checked")) {
        //                var group = "input:checkbox[name=\'" + $(this).attr("name") + "\']";
        //                $(group).prop("checked", false);
        //                $(this).prop("checked", true);
        //            } else {
        //                $(this).prop("checked", false);
        //            }
        //        });
    </script>