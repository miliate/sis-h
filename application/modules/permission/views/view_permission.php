<?php
function check_permission($all_user_group_have_permission, $permission_id, $ugid, $type)
{
    foreach ($all_user_group_have_permission as $user_group_have_permission) {
        if ($user_group_have_permission->UGID == $ugid && $user_group_have_permission->PERID == $permission_id
            && $user_group_have_permission->Type == $type && $user_group_have_permission->Active == True
        ) {
            return 'checked="checked"';
        }
    }
    return '';
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/preference'); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10 ">
            <?php
            $form_generator = new MY_Form($user_group->Name.'`s Permission');
            $form_generator->form_open_current_url();
            foreach ($all_permission as $permission) {
                echo '<div class="form-group">';
                echo '<div class="col-sm-4">';
                echo '<label style="margin-top: 6px;">' . $permission->Name . '</label>';
                echo '</div>';
                echo '<div class="col-sm-6">';
                echo '<label class="checkbox-inline"><input type="checkbox" style="margin-top: 2px;" name="permission[' . $permission->PERID . '][1]" ' . check_permission($all_user_group_have_permission, $permission->PERID, $ugid, 'view') . '>View</label>';
                echo '<label class="checkbox-inline"><input type="checkbox" style="margin-top: 2px;" name="permission[' . $permission->PERID . '][2]" ' . check_permission($all_user_group_have_permission, $permission->PERID, $ugid, 'create') . '>Create</label>';
                echo '<label class="checkbox-inline"><input type="checkbox" style="margin-top: 2px;" name="permission[' . $permission->PERID . '][3]" ' . check_permission($all_user_group_have_permission, $permission->PERID, $ugid, 'edit') . '>Edit</label>';
                echo '<label class="checkbox-inline"><input type="checkbox" style="margin-top: 2px;" name="permission[' . $permission->PERID . '][4]" ' . check_permission($all_user_group_have_permission, $permission->PERID, $ugid, 'print') . '>Print</label>';
                echo '</div>';
                echo '</div>';
            }
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<?php
echo Modules::run('template/footer');
?>

