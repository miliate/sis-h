<div>
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/ward'); //runs the available left menu for preference ?>
        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?= lang('Ward List'); ?></b></div>
                <div id="patient_list">
                    <?php //echo $pager; ?>
                    <br>
                    <table>
                        <tr>
                            <td>
                                <?php
                                if (!empty($wards)) {
                                    foreach ($wards as $ward) {
                                        $war = $ward['WID'];
                                        echo '<div class="col-sm-3" style="display:fluid">
                                                <div class="list-group">
                                                    <a href="'.site_url("/ward/wardView").'/'.$ward['WID'].'?ward='.$ward['WID'].'&&n='.$ward['Name'].'" class="list-group-item list-group-item-info active">
                                                        <h5 class="card-title" style="color:#fff">
                                                            <i class="fa fa-bed" style="text-align: center; font-size: 5rem; color: #fff"></i>
                                                            <br>'.$ward['Name'].'
                                                        </h5>
                                                    </a>
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <i class="fa fa-bed"></i> '.lang('Total Beds').'<span class="badge badge-pill progress-bar-success">'.$ward['TotalBeds'].'</span>
                                                    </a>
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <i class="fa fa-check"></i> '.lang('Occupied Beds').' <span class="badge progress-bar-danger badge-pill"> '.$ward['OccupiedBeds'].' </span>
                                                    </a>
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <i class="fa fa-times"></i> '.lang('Free beds').' <span class="badge progress-bar-info badge-pill"> '.$ward['FreeBeds'].' </span>
                                                    </a>
                                                    <a href="#" class="list-group-item list-group-item-action">
                                                        <b><i class="fa fa-phone"></i> Tel.: '.$ward['Telephone'].'</b>
                                                    </a>
                                                    <a href="'.site_url("/ward/wardView").'/'.$ward['WID'].'?ward='.$ward['WID'].'&&n='.$ward['Name'].'" class="btn btn-info btn-sm lg btn-block">'.lang('See More Details').' <i class="fa fa-caret-right" aria-hidden="true"></i></a>
                                                </div>
                                              </div>';
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
