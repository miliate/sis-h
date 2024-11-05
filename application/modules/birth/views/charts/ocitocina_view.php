<?php
function ocitocina_view($key)  {
    $style = <<<HTML
    <style type="text/css">
        #view$key {
            background-color: #ffffff;
            padding: 10px 25px 10px 0;
            display: flex;
        }
        #titleView$key {
            width: 80px;
            height: 30px;
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
        #$key td input {
            width: 100%;
            height: 100%;
            border: none;
            background-color: transparent;
            font-size: 10px;
            text-align: center;
        }
        #$key td input::-webkit-inner-spin-button {
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;
        }
    </style>
HTML;

    $table = <<<HTML
    <div id="view$key">
        <div id="titleView$key">
            <p id="title$key">Gotas/minuto</p>
        </div>
        <table id="$key">
            <tr>
                <script type="text/javascript">
                    for(let j = 0; j < 25; j++ ) {
                        const name = '$key' + '_' + j;
                        document.write("<td>");
                        document.write("<input type='number' name='"+name+"' min='0' value='0' />")
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