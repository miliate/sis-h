<?php
        function sexo ($data)
        {$data=='M'?$data="Masculino":$data="Feminino";
        return $data;
        }


        function idade($birthDate) {
          $birthDate = explode("-", $birthDate);
            //get age from date or birthdate
            $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
              ? ((date("Y") - $birthDate[2]) - 1)
              : (date("Y") - $birthDate[2]));
          return  date("Y")-$age;
        }



  $conn = new mysqli("localhost", "root", "", "hhimsv2_1");

        $result=array();
        $table_first = 'his_sisma_ward';

if(isset($_REQUEST['alta'])&&($_REQUEST['alta']>0)) { $alta=$_REQUEST['alta'];} else {$alta=8;}

        $query = "SELECT * FROM $table_first";
        $result = mysqli_query($conn,$query); // usernames result from DB.


  $json = "{"; //Create variable with prepended bracket ready to append to.
  $i=0; // Index to manage where our commas go.

$json.='"internamentos": [
 {';

  while ($row = $result->fetch_assoc()) {
      if ($i == 8) // Run this if block once.
      {
       $json.='"US": "",
        "PIG_DataDaAlta": "'.	$row["DeathDate"].'",';

    $json .= '"valores": [';

    $json .= '{  "name": "PIG - NID",
                                      "value": ' . $row["PID"] . '
                                  },
                                  {
                                      "name": "PIG - Nome",
                                      "value": "' . $row["Firstname"] . '"
                                  },
                                  {
                                      "name": "PIG - Apelido",
                                      "value": "' . $row["Name"] . '"
                                  },
                                  {
                                      "name": "PIG - Sexo",
                                      "value": null
                                  },
                                  {
                                      "name": "PIG - Dia de Admissão",
                                      "value": "' . substr($row['AdmissionDate'], 0, 10) . '"
                                  },
                                  {
                                      "name": "CO - Data de certificacao",
                                      "value": null
                                  },
                                  {
                                      "name": "PIG - Tipo de Admissão",
                                      "value": null
                                  },
                                  {
                                      "name": "PIG - Motivo de Admissão",
                                      "value": null
                                  },
                                  {
                                      "name": "PIG - Tipo de Alta",
                                      "value": null
                                  },
                                  {
                                      "name": "PIG - Resultado Global",
                                      "value": null
                                  },
                    {
                        "name": "PIG - Servico de Internamento",
                        "value": null
                    },
                    {
                        "name": "PIG - Admissão Compulsiva",
                        "value": null
                    },
                    {
                        "name": "PIG - Apelido de Pessoa de Referencia",
                        "value": null
                    },
                    {
                        "name": "PIG - Bairro",
                        "value": null
                    },
                    {
                        "name": "PIG - Data de Nascimento",
                        "value": null
                    },
                    {
                        "name": "eROH - Dias de Hospitalizacao",
                        "value": null
                    },
                    {
                        "name": "PIG - Endereco",
                        "value": null
                    },
                    {
                        "name": "PIG - Hora da Alta",
                        "value": null
                    },
                    {
                        "name": "PIG - Hora de Admissão",
                        "value": null
                    },
                    {
                        "name": "PIG  - Idade",
                        "value": null
                    },
                    {
                        "name": "PIG - Província - residência habitual",
                        "value": null
                    },
                    {
                        "name": "PIG - Localidade",
                        "value": null
                    },
                    {
                        "name": "PIG - Nome de Pessoa de Referencia",
                        "value": null
                    },
                    {
                        "name": "PIG - Numero de Identificacão",
                        "value": null
                    },
                    {
                        "name": "PIG - Tipo de Documento",
                        "value": null
                    },
                    {
                        "name": "PIG - Outro Tipo de Admissão",
                        "value": null
                    },
                    {
                        "name": "PIG - Telefone de Pessoa de Referencia",
                        "value": null
                    },
                    {
                        "name": "PIG - Minutos da Alta",
                        "value": null
                    },
                    {
                        "name": "PIG - Minutos de Admissão",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico de alta principal",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnistico de alta secundario 2",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico de alta secundario 3",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico de alta secundario 4",
                        "value": null
                    },
                    {
                        "name": "CO  - Duração da gestação",
                        "value": null
                    },
                    {
                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Bairro",
                        "value": null
                    },
                    {
                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Distrito/Cidade",
                        "value": null
                    },
                    {
                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - País",
                        "value": null
                    },
                    {
                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Província",
                        "value": null
                    },
                    {
                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Rua/Av",
                        "value": null
                    },
                    {
                        "name": "CO  - Escolaridade da mãe",
                        "value": null
                    },
                    {
                        "name": "CO  - Idade da mãe",
                        "value": null
                    },
                    {
                        "name": "CO  - Local da ocorrência",
                        "value": null
                    },
                    {
                        "name": "CO  - Local da Ocorrência - Código da US",
                        "value": null
                    },
                    {
                        "name": "CO  - Local da Ocorrência - Departamento",
                        "value": null
                    },
                    {
                        "name": "CO  - Local da Ocorrência - NID",
                        "value": null
                    },
                    {
                        "name": "CO  - Local da Ocorrência - Serviço",
                        "value": null
                    },
                    {
                        "name": "CO  - Morte durante gravidez, parto ou aborto",
                        "value": null
                    },
                    {
                        "name": "CO  - Morte ocorrida após parto",
                        "value": null
                    },
                    {
                        "name": "CO  - Número de filhos nascidos mortos",
                        "value": null
                    },
                    {
                        "name": "CO  - Número de filhos nascidos vivos",
                        "value": null
                    },
                    {
                        "name": "CO  - Ocupação habitual ou ramo de actividade da mãe",
                        "value": null
                    },
                    {
                        "name": "CO  - Peso do feto/bebé ao nascer (em gramas)",
                        "value": null
                    },
                    {
                        "name": "CO  - Tipo de gravidez",
                        "value": null
                    },
                    {
                        "name": "CO  - Tipo de parto",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Básica da morte",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Directa da morte",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Intermédia da morte",
                        "value": null
                    },
                    {
                        "name": "PIG - Cidade - residência habitual",
                        "value": null
                    },
                    {
                        "name": "CO - Contacto do sector do Trabalho",
                        "value": null
                    },
                    {
                        "name": "PIG - Célula - residência habitual",
                        "value": null
                    },
                    {
                        "name": "CO - Código da US",
                        "value": null
                    },
                    {
                        "name": "CO - Data do Óbito ou aparececimento do cadáver",
                        "value": null
                    },
                    {
                        "name": "CO - Distrito - residência habitual",
                        "value": null
                    },
                    {
                        "name": "CO - Fonte da informação do Óbito",
                        "value": null
                    },
                    {
                        "name": "CO - Morte por acidente de trabalho",
                        "value": null
                    },
                    {
                        "name": "CO - Médico que assinou atendeu ao falecido",
                        "value": null
                    },
                    {
                        "name": "CO - Método de confirmação do diagnóstico (Autópsia)",
                        "value": null
                    },
                    {
                        "name": "CO - Nome do Médico",
                        "value": null
                    },
                    {
                        "name": "PIG - Número de casa/CEP- residência habitual",
                        "value": null
                    },
                    {
                        "name": "PIG - Pais - residência habitual",
                        "value": null
                    },
                    {
                        "name": "PIG - Quarteirão - residência habitual",
                        "value": null
                    },
                    {
                        "name": "CO - Tipo de morte não natural",
                        "value": null
                    },
                    {
                        "name": "CO -Bairro - residência habitual",
                        "value": null
                    },
                    {
                        "name": "CO - Escolaridade",
                        "value": null
                    },
                    {
                        "name": "CO - Estado Civil",
                        "value": null
                    },
                    {
                        "name": "CO - Nacionalidade",
                        "value": null
                    },
                    {
                        "name": "CO - Naturalidade",
                        "value": null
                    },
                    {
                        "name": "CO - Tipo de Óbito",
                        "value": null
                    },
                    {
                        "name": "CO - Nome da mãe",
                        "value": null
                    },
                    {
                        "name": "CO - Nome do Pai",
                        "value": null
                    },
                    {
                        "name": "CO - Ocupacao",
                        "value": null
                    },
                    {
                        "name": "CO - Raca",
                        "value": null
                    },
                    {
                        "name": "PIG - Transferido para",
                        "value": null
                    },
                    {
                        "name": "PIG - Data de Nascimento Desconhecida",
                        "value": null
                    },
                    {
                        "name": "CO - Autopsia realizada",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Básica - anos",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Directa -  anos",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Intermedia - anos",
                        "value": null
                    },
                    {
                        "name": "PIG - Pais - residência",
                        "value": null
                    },
                    {
                        "name": "PIG - Horas de Hospitalizacao",
                        "value": null
                    },
                    {
                        "name": "PIG - Registo Clinico de Admissao - Departamento",
                        "value": null
                    },
                    {
                        "name": "PIG -  Serviços - Clinica Especial",
                        "value": null
                    },
                    {
                        "name": "PIG - Serviços - Ginecologia",
                        "value": null
                    },
                    {
                        "name": "PIG - Serviços - Medicinas",
                        "value": null
                    },
                    {
                        "name": "PIG - Serviços - Obstetrícia",
                        "value": null
                    },
                    {
                        "name": "PIG - Serviços - Ortopedia",
                        "value": null
                    },
                    {
                        "name": "PIG - Serviços - Pediatria",
                        "value": null
                    },
                    {
                        "name": "PIG - Serviços - SUR",
                        "value": null
                    },
                    {
                        "name": "PIG - Serviços - Cirurgia",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Básica -  dias",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Básica - horas",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Básica - meses",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Básica - minutos",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Directa - dias",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Directa - horas",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Directa - meses",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Directa - minutos",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Intermedia - dias",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Intermedia - horas",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Intermedia - meses",
                        "value": null
                    },
                    {
                        "name": "CO - Tempo Causa Intermedia - minutos",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - Medicinas",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - Obstetrícia",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - Ortopedia",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - Pediatria",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - SUR",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - Ginecologia",
                        "value": null
                    },
                    {
                        "name": "PIG - Posto administrativo - residência habitual",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - Clinica Especial",
                        "value": null
                    },
                    {
                        "name": "CO - Serviços - Cirurgia",
                        "value": null
                    },
                    {
                        "name": "PIG - Idade - Unidade",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico principal Alternativo - Codigo",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico principal Alternativo - Descricao",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico Secundario 1 Alternativo - Descricao",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico Secudnario 2 Alternativo - Descricao",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico Secudnario 3 Alternativo - Descricao",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico Secundario 1 Alternativo - Cod",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico Secundario 2 Alternativo",
                        "value": null
                    },
                    {
                        "name": "PIG - Diagnostico Secundario 3 Alternativo",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Basica Alternativa",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Basica Alternativa - Descricao",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Directa Alternativa",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Directa Alternativa - Descricao",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Intermedia Alternativa",
                        "value": null
                    },
                    {
                        "name": "CO - Causa Intermedia Alternativa - Descricao",
                        "value": null
                    },
                    {
                        "name": "CO  - Endereço da ocorrência - País - moz_outro",
                        "value": null
                    },
                    {
                        "name": "PIG - Enfermaria",
                        "value": null
                    }';



    $json .= "]";




      }
      else
      {
//Do Nothing aqui
      }
      $i++; // Increase by one so that the else statement is ran until the end of the data.
  }

  $json.="}]";
  $json .= "}"; // Finally, close the json with the last square bracket.

  echo $json;
  $conn->close();

  ?>