<?php
if ($this->session->flashdata('msg')) {
    echo '<div class="row">';
    echo '<div class="col-lg-4">';
    echo '</div>';
    echo '<div class="col-lg-4">';
    echo '</div>';
    echo '<div class="col-lg-4">';
    echo '<div class="alert alert-warning alert-dismissable" id="top_message" style="position:absolute;z-index:999999;">';
    echo '<button type="button" class="close" data-dismiss="alert">×</button>';
    echo $this->session->flashdata('msg');
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>

<style>
    #logo {
        max-width: 700px;
        display: flex;
        align-items: center;
        gap: 2rem;
        font-size: 1.8rem;
        text-transform: uppercase;
    }

    #logo #text #title{
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #207eb2;
    }

  .logo-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .dep-user {
    display: flex;
    flex-direction: column;
    gap: 0px;
  }

  .dep-item {
    align-self: end;
  }


    
</style>

<div class="page-header">
    <div class="logo-container">
     
         <div id="logo">
            <div>
                <img src="<?php echo base_url('images/moz.png')?>" alt="Logo" width="80">
            </div>


            <div id="text">
                <div id="title">
                <d>República de Moçambique</d>
                <span>Ministério da Saúde</span>

                </div>

                <span id="hospital-name"><?php echo $this->config->item('hospital_name'); ?></span>
            </div>
         </div>
       

        <div class="dep-user">
            <div class="dep-item">
                <span  style="color: #999"><?php echo date('Y-m-d'); ?>
                <span class="label label-danger"><?php echo $this->session->userdata('department'); ?> Department</span></span><br>
            </div>
           
           
            <div class="user">
                <span
                    style="color: #999" > <i class="fa fa-user" style="font-size:24px;"></i> <b><?php echo $this->session->userdata('title') . ' ' . $this->session->userdata('name') . ' ' . $this->session->userdata('other_name'); ?></b></span>
                <span class="label label-primary"><?php echo $this->session->userdata('user_group_name'); ?></span>
           </div>
        </div>
    </div>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="row">
                <ul class="nav navbar-nav">
                    <?php
                    foreach ($top_menu as $menu) {
                        if (strtolower($menu['Link']) === strtolower($active_menu_link)) {
                            echo '<li class="active">';
                        } else {
                            echo '<li>';
                        }
                        echo '<a class="top-menu" href="' . site_url() . '/' . $menu['Link'] . '">' . $this->lang->line($menu['Name']) . '</a>';
                        echo '</li>';
                    }
                    ?>

                </ul>
                <ul class="nav navbar-nav navbar-right pull-right">
                    <li><a
                            href="<?php echo site_url('hhims') ?>"> <?= lang('Home') ?></a>
                    </li>
                    <?php
                    if ('user_config' === strtolower($active_menu_link)) {
                        echo '<li class="active">';
                    } else {
                        echo '<li>';
                    } ?>
                    <a href="<?php echo site_url('user_config') ?>"><?php echo $this->lang->line('Configure'); ?></a>
                    </li>
                    <li class="pull-right"><a
                            href="<?php echo site_url('login/logout') ?>"><?php echo $this->lang->line('Logout'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
