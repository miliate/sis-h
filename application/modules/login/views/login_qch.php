<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>HIS - Health Information System</title>
    <style type="text/css">
        .copy {
            font-family: "Arial";
            font-size: 12px;
            color: #fff;
        }

        fieldset {
            border: 1px solid #999;
            padding: 1em;
            font: 80%/1 sans-serif;
            border-radius: 8px;
            box-shadow: 0 0 10px #999;
            background: #fff;
            margin-bottom: 20px; /* Espaçamento entre a box e o final do container principal */
        }

        legend {
            padding: 0.2em 0.5em;
            border: 1px solid #999;
            box-shadow: 0 0 10px #999;
            color: green;
            font-size: 90%;
            text-align: left;
            background: #fff;
        }

        label {
            float: left;
            width: 25%;
            margin-right: 0.5em;
            padding-top: 0.2em;
            text-align: right;
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            display: block;
            margin: 0;
            font-family: sans-serif;
            font-size: 12px;
            appearance: none;
            box-shadow: none;
            border-radius: 2px;
            background-repeat: repeat-x;
            border: 1px solid #0cf;
            color: #333333;
            padding: 5px;
            margin-right: 4px;
            margin-bottom: 8px;
        }

        input:focus {
            outline: none;
            border: 1px solid #093;
        }

        .btnOK, .btnDng {
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            display: inline-block;
            cursor: pointer;
            color: #ffffff;
            font-family: Arial;
            font-size: 17px;
            padding: 5px 30px;
            text-decoration: none;
            text-shadow: 0px 1px 0px #2f6627;
        }

        .btnOK {
            background-color: #03adad;
            border: 1px solid #03adad;
        }

        .btnOK:hover {
            background-color: #5cbf2a;
            border: 1px solid #5cbf2a;
        }

        .btnOK:active {
            position: relative;
            top: 1px;
        }

        .btnDng {
            background-color: #666;
            border: 1px solid #666;
        }

        .btnDng:hover {
            background-color: #f30;
            border: 1px solid #f30;
        }

        #logo {
            max-width: 700px;
            display: flex;
            align-items: center;
            gap: 2rem;
            font-size: 1.4rem;
            text-transform: uppercase;
        }

        #logo #text #title {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #207eb2;
        }

        #logo #text #hospital-name {
            font-size: 1.2rem;
        }

    </style>
</head>

<body bgcolor="#66ccff">
<p>&nbsp; </p>
<p>&nbsp; </p>
<table width="550" border="0" align="center">
    <tr>
        <td>
            <form action="<?php echo base_url(); ?>index.php/login/auth" method="post">
                <?php
                if ($this->input->get('NEXT')) {
                    echo form_hidden("NEXT", $this->input->get('NEXT'));
                }
                ?>
                <fieldset>
                    <table width="550" height="310" border="0" align="center">
                        <tr>
                            <div id="logo">
                                <div>
                                    <img src="<?php echo base_url('images/moz.png')?>" alt="Logo" width="80">
                                </div>
                                <div id="text">
                                    <div id="title">
                                        <div>República de Moçambique</div>
                                        <div>Ministério da Saúde</div>
                                    </div>
                                    <span id="hospital-name"><?php echo $this->config->item('hospital_name'); ?></span>
                                </div>
                            </div>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <fieldset style="padding-bottom: 20px; margin-bottom: 30px;"> 
                                    <legend>User Login</legend>

                                    <p>
                                        <label for="name">Utilizador:</label>
                                        <input type="text" size="40" class="box" id="myusername" class="uname"
                                               name="username" type="text" value="<?php echo set_value('username') ?>" tabindex="1" lang="en"/>
                                    </p>

                                    <p>
                                        <label for="mail">Password:</label>
                                        <input type="password" size="40" class="box" id="mypassword" class="password"
                                               name="password" value="" tabindex="2" autocomplete="off" lang="en"/>
                                    </p>

                                    <p align="center">
                                        <input type="radio" name="department" value="1" checked="checked"/>Urg&ecirc;ncias
                                        <input type="radio" name="department" value="2" />Consultas Externas
                                        <input type="radio" name="department" value="3" /> Internamentos
                                    </p>

                                    <?php echo validation_errors(); ?>

                                    <p align="center">
                                        <input type="submit" id="login" value="Entrar" class="btnOK"/>
                                        &nbsp; &nbsp; <input type="reset" name="reset" id="reset" value="Cancelar"  class="btnDng"/>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </td>
    </tr>
    <tr>
        <td align="center">
            <div class="copy"> &copy; 11/2022 - <?= date("m/Y"); ?>. MISAU - Departamento de Tecnologias de Informa&ccedil;&atilde;o e Comunica&ccedil;&atilde;o </div>
        </td>
    </tr>
</table>
</body>
</html>
