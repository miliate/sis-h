<?php
function liquido_view($key) {
    $style = <<<HTML
    <style type="text/css">
        #view$key {
            background-color: #ffffff;
            padding: 10px 25px 10px 0;
            display: flex;
        }
        #titleView$key {
            width: 80px;
            height: 60px;
            display: flex;
            flex-direction: column;
        }
        #title$key {
            height: 30px;
            width: 80px;
            font-size: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        #$key {
            border-collapse: collapse;
            width: 100%;
        }
        #$key td {
            height: 30px;
            width: 4%;
            text-align: center;
            border: 1px solid black;
        }
        #$key td select {
            width: 100%;
            height: 100%;
            border: none;
            background-color: transparent;
            font-size: 10px;
            text-align: center;
            -webkit-appearance:none;
            -moz-appearance:none;
            appearance:none
        }
    </style>
HTML;

    $table = <<<HTML
    <div id="view$key">
        <div id="titleView$key">
            <p id="title$key">LIQUIDO<br/>AMNIOTICO</p>
            <p id="title$key">MOLDAGEM</p>
        </div>
        <table id="$key">
            <tr>
                <script type="text/javascript">
                    for(let j = 0; j < 25; j++ ) {
                        const name = '$key' + '_1_' + j;
                        document.write("<td>");
                        document.write("<select name='" + name + "'>");
                        document.write("<option selected value=''>-</option>");
                        document.write("<option value='T'>T</option>");
                        document.write("<option value='R'>R</option>");
                        document.write("<option value='A'>A</option>");
                        document.write("<option value='M'>M</option>");
                        document.write("<option value='B'>B</option>");
                        document.write("</select>");
                        document.write("</td>");
                    }
                </script>
            </tr>
            <tr>
                <script type="text/javascript">
                    for(let j = 0; j < 25; j++ ) {
                        const name = '$key' + '_2_' + j;
                        document.write("<td>");
                        document.write("<select name='" + name + "'>");
                        document.write("<option selected value=''>-</option>");
                        document.write("<option value='0'>0</option>");
                        document.write("<option value='+1'>+1</option>");
                        document.write("<option value='+2'>+2</option>");
                        document.write("<option value='+3'>+3</option>");
                        document.write("</select>");
                        document.write("</td>");
                    }
                </script>
            </tr>
        </table>
    </div>
HTML;

    echo $style;
    echo $table;
};