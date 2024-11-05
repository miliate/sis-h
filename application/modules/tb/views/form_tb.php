<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
                echo Modules::run('patient/banner', $pid);
                $form_generator = new MY_FORM('TB');
                $form_generator->form_open_current_url();
                ?>
                <h5>PATIENT RISK FACTORS</h5>
                <?php
                $form_generator->dropdown('Risk group', 'group',array(
                    "Miner" => "Miner",
                    "EX-Miner" => "EX-Miner",
                    "Silica exposure" => "Silica exposure",
                    "Prisoner" => "Prisoner",
                    "TB contact" => "TB contact",
                    "Inmate population" => "Inmate population",
                    "Health worker" => "Health worker",
                    "Conglomerate resident" => "Conglomerate resident",
                    "Other" => "Other"
                ));
                $form_generator->checkboxes('Risk factors for TB','risk_factors_tb',array(
                    "Smoking" => "Smoking",
                    "Use of illicit drugs" => "Use of illicit drugs",
                    "Alcohol abuse" => "Alcohol abuse",
                    "Other" => "Other" 
                ));
                ?>
                <h5>CHARACTERIZATION OF TUBERCULOSIS</h5>
                <?php
                $form_generator->dropdown('Anatomical location of TB', 'anatomical_location_tb',array(
                    "Pulmonary" => "Pulmonary",
                    "Extra-pulmonary" => "Extra-pulmonary",
                ));
                $form_generator->dropdown('Bacteriological confirmation', 'bacteriological_confirmation',array(
                    "TB bacteriologically confirmed" => "TB bacteriologically confirmed",
                    "TB clinically diagnosed" => "TB clinically diagnosed",
                ));
                $form_generator->dropdown('Previous TB treatment', 'previous_tb_treatment',array(
                    "New case" => "New case",
                    "Relapse" => "Relapse",
                    "Post-failure of 1st treatment" => "Post-failure of 1st treatment",
                    "Post failure of retreatment" => "Post failure of retreatment",
                    "After loss to follow-up" => "After loss to follow-up",
                    "Transferred" => "Transferred",
                    "Other" => "Other"
                ));
            ?>
            <script  type="text/javascript">
                $(document).ready(function(){
                    let tb_treatment_count = 1;
                    $("#tb_treatment_add").click(function(){
                        tb_treatment_count++;
                        const html = `
                            <h6>Line ${tb_treatment_count}</h6>
                            <div style="display:grid;grid-template-columns: repeat(2, minmax(0, 1fr));gap: 3rem;">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    Regime
                                    <input type="date" name="tb_treatment_start_${tb_treatment_count}" style="width: 100%;grid-column: span 2; margin-right: 2rem;"/>
                                </div>
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    End date
                                    <input type="date" name="tb_treatment_end_${tb_treatment_count}" style="width: 100%;grid-column: span 2;"/>
                                </div>
                            </div>
                        `;
                        $("#tb_treatment_histories").append(html);
                    });
                });
            </script>
            <button type="button" id="tb_treatment_add" style="float:right">Add New</button>
            <h5>History of previous TB treatment</h5>
            <div id="tb_treatment_histories">
                <h6>Line 1</h6>
                <div style="display:grid;grid-template-columns: repeat(2, minmax(0, 1fr));gap: 3rem;">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        Regime
                        <input type="date" name="tb_treatment_start_1" style="width: 100%;grid-column: span 2; margin-right: 2rem;"/>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        End date
                        <input type="date" name="tb_treatment_end_1" style="width: 100%;grid-column: span 2;"/>
                    </div>
                </div>
            </div>
            <br/>
            <h5>CO-MORBITIES</h5>
            <h6>TB / HIV</h6>
            <div style="display: grid;grid-template-columns: 1fr 3fr 3fr; column-gap: 3rem; row-gap: 1rem;">
                <!-- Header -->
                <p>Service</p>
                <p>Result</p>
                <p>Date</p>

                <!-- HIV testing 1 -->
                <div style="align-self: center;">HIV testing</div>
                <select style="width: 100%; height:100%;">
                    <option value="Pos">Pos</option>
                    <option value="Neg">Neg</option>
                    <option value="I">I</option>
                    <option value="NF">NF</option>
                </select>
                <input type="date" style="width: 100%;"/>

                <!-- HIV testing 2 -->
                <div style="align-self: center;">HIV testing</div>
                <select style="width: 100%; height:100%;">
                    <option value="Pos">Pos</option>
                    <option value="Neg">Neg</option>
                    <option value="I">I</option>
                    <option value="NF">NF</option>
                </select>
                <input type="date" style="width: 100%;"/>

                <!-- HIV testing 3 -->
                <div style="align-self: center;">HIV testing</div>
                <select style="width: 100%; height:100%;">
                    <option value="Pos">Pos</option>
                    <option value="Neg">Neg</option>
                    <option value="I">I</option>
                    <option value="NF">NF</option>
                </select>
                <input type="date" style="width: 100%;"/>

                <!-- HIV testing 4 -->
                <div style="align-self: center;">HIV testing</div>
                <select style="width: 100%; height:100%;">
                    <option value="Pos">Pos</option>
                    <option value="Neg">Neg</option>
                    <option value="I">I</option>
                    <option value="NF">NF</option>
                </select>
                <input type="date" style="width: 100%;"/>

                <!-- HIV testing 5 -->
                <div style="align-self: center;">HIV testing</div>
                <select style="width: 100%; height:100%;">
                    <option value="Pos">Pos</option>
                    <option value="Neg">Neg</option>
                    <option value="I">I</option>
                    <option value="NF">NF</option>
                </select>
                <input type="date" style="width: 100%;"/>

                <!-- TPC -->
                <div style="align-self: center;">TPC</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                    <option value="N Can">N Can</option>
                </select>
                <input type="date" style="width: 100%;"/>

                <!-- Dapsone -->
                <div style="align-self: center;">Dapsone</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                    <option value="N Done">N Done</option>
                </select>
                <input type="date" style="width: 100%;"/>

                <!-- ART -->
                <div style="align-self: center;">ART</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="date" style="width: 100%;"/>
            </div>
            <br/>
            <h6>Diabetes Mellitus</h6>
            <div style="display: grid;grid-template-columns: 3fr 3fr; column-gap: 3rem; row-gap: 1rem;">
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    Date of diagnosis
                    <input type="date" style="width: 100%;grid-column: span 2;"/>
                </div>
            </div>
            <br/>
            <h6>Blood Sugar: Result / Date</h6>
            <div style="display: grid;grid-template-columns: 3fr 3fr; column-gap: 3rem; row-gap: 1rem;">
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <input type="number" style="width: 100%;height:100%;"/>
                    g/dl or mmol/L					
                </div>
                <input type="date" style="width: 100%;"/>
            </div>
            <br/>
            <h6>ART Regimen</h6>
            <div style="display: grid;grid-template-columns: 1fr 1fr; column-gap: 3rem; row-gap: 1rem;">
                <!-- Header -->
                <p>Regime</p>
                <p>Date</p>

                <input type="text" style="width: 100%;"/>
                <input type="date" style="width: 100%;"/>

                <input type="text" style="width: 100%;"/>
                <input type="date" style="width: 100%;"/>

                <input type="text" style="width: 100%;"/>
                <input type="date" style="width: 100%;"/>
            </div>
            <br/>
            <h6>Diabetes treatment: drugs and start date</h6>
            <div style="display: grid;grid-template-columns: 1fr 1fr 1fr; column-gap: 3rem; row-gap: 1rem;">
                <!-- Header -->
                <p>Medication</p>
                <p>Dosage</p>
                <p>Date</p>

                <input type="text" style="width: 100%;"/>
                <input type="text" style="width: 100%;"/>
                <input type="date" style="width: 100%;"/>

                <input type="text" style="width: 100%;"/>
                <input type="text" style="width: 100%;"/>
                <input type="date" style="width: 100%;"/>

                <input type="text" style="width: 100%;"/>
                <input type="text" style="width: 100%;"/>
                <input type="date" style="width: 100%;"/>

                <input type="text" style="width: 100%;"/>
                <input type="text" style="width: 100%;"/>
                <input type="date" style="width: 100%;"/>
            </div>
            <br/>
            <h6>Chronic non-communicable diseases</h6>
            <div style="display: grid;grid-template-columns: 2fr 1fr 2fr 1fr 2fr; column-gap: 3rem; row-gap: 1rem;">
                <!-- Header -->
                <p>Pathology</p>
                <p>Diagnosed</p>
                <p>Date</p>
                <p>Controlled</p>
                <p>Medication</p>

                <!-- Hypertension -->
                <div style="align-self: center;">Hypertension</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="date" style="width: 100%;"/>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="text" style="width: 100%;"/>

                <!-- Epilepsy -->
                <div style="align-self: center;">Epilepsy</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="date" style="width: 100%;"/>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="text" style="width: 100%;"/>

                <!-- Mental illness -->
                <div style="align-self: center;">Mental illness</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="date" style="width: 100%;"/>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="text" style="width: 100%;"/>

                <!-- Liver disease -->
                <div style="align-self: center;">Liver disease</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="date" style="width: 100%;"/>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="text" style="width: 100%;"/>

                <!-- Renal insufficiency -->
                <div style="align-self: center;">Renal insufficiency</div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="date" style="width: 100%;"/>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="text" style="width: 100%;"/>

                <!-- Allergies -->
                <div style="height: 100%; align-items: center; display:grid; grid-template-columns: 1fr 2fr;">
                    Allergies
                    <input type="text" style="width: 100%; height: 100%;"/>
                </div>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="date" style="width: 100%;"/>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="text" style="width: 100%;"/>

                <!-- Other -->
                <div style="height: 100%; align-items: center;grid-column: span 2; display:grid; grid-template-columns: 1fr 2fr;">
                    Other
                    <input type="text" style="width: 100%; height:100%;"/>
                </div>
                
                <input type="date" style="width: 100%;"/>
                <select style="width: 100%; height:100%;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <input type="text" style="width: 100%;"/>
            </div>
        </div>
</div>