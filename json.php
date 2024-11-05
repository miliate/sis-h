<?php
//PDO is a extension which  defines a lightweight, consistent interface for accessing databases in PHP.
$db=new PDO('mysql:dbname=hhimsv2_1;host=localhost;','root','123');
//here prepare the query for analyzing, prepared statements use less resources and thus run faster
$row=$db->prepare('select * from his_sisma_ward WHERE DateOfBirth<>"0000-00-00" AND Active=1 ORDER BY DischargeDate DESC LIMIT 15');


function sexo ($data)
{$data=='M'?$data=1:$data=2;
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


$row->execute();//execute the query
$json_data=array();//create the array
foreach($row as $rec)//foreach loop
{
    $json_array['PIG_NID']=$rec['PID'];
    $json_array['PIG_Nome']=$rec['Firstname'];
    $json_array['PIG_Apelido']=$rec['Name'];
    $json_array['PIG_Sexo']=sexo($rec['Gender']);
    $json_array['PIG_DataNascimento']=$rec['DateOfBirth'];
    $json_array['PIG_Idade']=idade($rec['DateOfBirth']);
    $json_array['PIG_Idade_Unidade']="anos";
    $json_array['PIG_TipoDeDocumento']="BI";
    $json_array['PIG_NumeroDeIdentificacao']=$rec['BI_ID'];
    $json_array['PIG_Pais_residencia']=$rec['Who_Pais_residencia'];
    $json_array['PIG_Pais_residenciahabitual']=$rec['who_Pais_residenciahabitual'];
    $json_array['PIG_Provincia_residenciahabitual']=$rec['who_province_id'];
    $json_array['PIG_Cidade_residenciahabitual']=$rec['who_district_id'];
    $json_array['PIG_PostoAdministrativo_residenciahabitual']=$rec['who_district_id'];
    $json_array['PIG_Localidade']=$rec['who_localidade_id'];
    $json_array['CO -Bairro_residenciahabitual']=$rec['who_Bairro_residenciahabitual'];
    $json_array['PIG_Quarteirao_residenciahabitual']=$rec['who_Quarteirao_residenciahabitual'];
    $json_array['PIG_Calula_residenciahabitual']=$rec['who_Calula_residenciahabitual'];
    $json_array['PIG_NumeroDeCasa_CEP_residenciahabitual']=$rec['who_NumeroDeCasa_CEP_residenciahabitual'];
    $json_array['PIG_TipoDeAdmissao']=$rec['Motivo_consulta'];
    $json_array['PIG_OutroTipoDeAdmissao']=$rec['Motivo_consulta'];
    $json_array['PIG_AdmissaoCompulsiva']=$rec['Motivo_consulta'];
    $json_array['PIG_MotivoDeAdmissao']=$rec['Motivo_consulta'];
    $json_array['PIG_DiadeAdmissao']=substr($rec['AdmissionDate'],0,10);
    $json_array['PIG_HoradeAdmissao']=substr(substr($rec['AdmissionDate'],-8),0,2);
    $json_array['PIG_MinutosDeAdmissao']=substr(substr($rec['AdmissionDate'],-8),3,2);
    $json_array['PIG_RegistoClinicoDeAdmissao_Departamento']=$rec['Ward'];
    $json_array['PIG_Servicos_ClinicaEspecial']=$rec['Motivo_consulta'];
    $json_array['PIG_Servicos_Ortopedia']=$rec['Motivo_consulta'];
    $json_array['PIG_Servicos_Obstetricia']=$rec['Motivo_consulta'];
    $json_array['PIG_Servicos_Medicinas']=$rec['Motivo_consulta'];
    $json_array['PIG_Enfermaria']=$rec['Motivo_consulta'];
    $json_array['PIG_Servicos_Ginecologia']=$rec['Motivo_consulta'];
    $json_array['PIG_Servicos_Pediatria']=$rec['Motivo_consulta'];
    $json_array['PIG_Servicos_SUR']=$rec['Motivo_consulta'];
    $json_array['PIG_Servicos_Cirurgia']=$rec['Motivo_consulta'];
    $json_array['PIG_TipoDeAlta']=$rec['Motivo_consulta'];
    $json_array['PIG_TransferidoPara']=$rec['Motivo_consulta'];
    $json_array['PIG_ResultadoGlobal']=$rec['Motivo_consulta'];
    $json_array['PIG_HoradaAlta']=$rec['Motivo_consulta'];
    $json_array['PIG_MinutosDaAlta']=$rec['Motivo_consulta'];
    $json_array['eROH_DiasDeHospitalizacao']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoDeAltaprincipal']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoPrincipalAlternativo_Codigo']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoPrincipalAlternativo_Descricao']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnisticoDeAltasecundario2']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoSecundario1Alternativo_Cod']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoSecundario1Alternativo_Descricao']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoDeAltasecundario3']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoSecundario2Alternativo']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoSecudnario2Alternativo_Descricao']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoDeAltasecundario4']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoSecundario3Alternativo']=$rec['Motivo_consulta'];
    $json_array['PIG_DiagnosticoSecudnario3Alternativo_Descricao']=$rec['Motivo_consulta'];

    $json_array['CO_Nacionalidade']=$rec['Motivo_consulta'];
    $json_array['CO_Naturalidade']=$rec['Motivo_consulta'];
    $json_array['CO_NomeDoPai']=$rec['FatherName'];
    $json_array['CO_NomeDamae']=$rec['MotherName'];
    $json_array['CO_Raca']=$rec['Motivo_consulta'];
    $json_array['CO_EstadoCivil']=$rec['Personal_Civil_Status'];
    $json_array['CO_Escolaridade']=$rec['Motivo_consulta'];
    $json_array['CO_Ocupacao']=$rec['Profession'];
    $json_array['CO_LocalDaocorrencia']=$rec['PlaceOfDie'];
    $json_array['CO_LocalDaOcorrencia_CodigoDaUS']=$rec['Motivo_consulta'];
    $json_array['CO_LocalDaOcorrencia_Departamento']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_Medicinas']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_Obstetricia']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_Ortopedia']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_Pediatria']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_SUR']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_Ginecologia']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_ClinicaEspecial']=$rec['Motivo_consulta'];
    $json_array['CO_Servicos_Cirurgia']=$rec['Motivo_consulta'];
    $json_array['CO_EnderecoDaocorrencia,SeForadaUSOuDomicilio_Rua_Av']=$rec['Motivo_consulta'];
    $json_array['CO_EnderecoDaocorrencia,SeForadaUSOuDomicilio_Bairro']=$rec['Motivo_consulta'];
    $json_array['CO_EnderecoDaocorrencia_Pais_moz_outro']=$rec['Motivo_consulta'];
    $json_array['CO_EnderecoDaocorrencia,SeForadaUSOuDomicilio_Pais']=$rec['Motivo_consulta'];
    $json_array['CO_EnderecoDaocorrencia,SeForadaUSOuDomicilio_Provincia']=$rec['Motivo_consulta'];
    $json_array['CO_EnderecoDaocorrencia,SeForadaUSOuDomicilio_Distrito_Cidade']=$rec['Motivo_consulta'];
    $json_array['CO_IdadeDamae']=$rec['Motivo_consulta'];
    $json_array['CO_EscolaridadeDamae']=$rec['Motivo_consulta'];
    $json_array['CO_OcupacaoHabitualOu_ramoDeActividadeDamae']=$rec['Motivo_consulta'];
    $json_array['CO_NumeroDeFilhosNascidosVivos']=$rec['Motivo_consulta'];
    $json_array['CO_NumeroDeFilhosNascidosMortos']=$rec['Motivo_consulta'];
    $json_array['CO_DuracaoDagestacao']=$rec['Motivo_consulta'];
    $json_array['CO_TipoDeGravidez']=$rec['Motivo_consulta'];
    $json_array['CO_TipoDeParto']=$rec['Motivo_consulta'];
    $json_array['CO_PesoDoFeto_bebaAoNascer (emGramas)']=$rec['Motivo_consulta'];
    $json_array['CO_MorteDuranteGravidez,PartoOuAborto']=$rec['Motivo_consulta'];
    $json_array['CO_MorteOcorridaaposParto']=$rec['Motivo_consulta'];
    $json_array['CO_Autopsiarealizada']=$rec['Motivo_consulta'];
    $json_array['CO_MatodoDeConfirmacaoDoDiagnostico (Autopsia)']=$rec['Motivo_consulta'];
    $json_array['CO_CausaDirectadamorte']=$rec['DirectDiagnosis'];
    $json_array['CO_CausaDirectaAlternativa']=$rec['Motivo_consulta'];
    $json_array['CO_CausaDirectaAlternativa_Descricao']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaDirecta_anos']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaDirecta_meses']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaDirecta_dias']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaDirecta_horas']=$rec['Motivo_consulta'];
    $json_array['CO_CausaIntermadiadamorte']=$rec['MediumDiagnosis'];
    $json_array['CO_CausaIntermediaAlternativa']=$rec['Motivo_consulta'];
    $json_array['CO_CausaIntermediaAlternativa_Descricao']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaIntermedia_meses']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaIntermedia_dias']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaIntermedia_horas']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaIntermedia_anos']=$rec['Motivo_consulta'];
    $json_array['CO_CausaBasicadamorte']=$rec['BasicDiagnosis'];
    $json_array['CO_CausaBasicaAlternativa']=$rec['Motivo_consulta'];
    $json_array['CO_CausaBasicaAlternativa_Descricao']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaBasica_anos']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaBasica_meses']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaBasica_dias']=$rec['Motivo_consulta'];
    $json_array['CO_TempoCausaBasica_horas']=$rec['Motivo_consulta'];
    $json_array['CO_NomeDoMadico']=$rec['DiagnosisConfirmedBy'];
    $json_array['CO_MadicoQueAssinouAtendeuAoFalecido']=$rec['DiagnosisConfirmedBy'];
    $json_array['CO_ContactoDoSectorDoTrabalho']=$rec['Motivo_consulta'];
    $json_array['CO_DatadeCertificacao']=$rec['Motivo_consulta'];
    $json_array['CO_TipoDeMorteNaoNatural']=$rec['Motivo_consulta'];
    $json_array['CO_MortePorAcidenteDeTrabalho']=$rec['Motivo_consulta'];
    $json_array['CO_FonteDainformacaoDoobito']=$rec['Motivo_consulta'];





//here pushing the values in to an array
array_push($json_data,$json_array);
}
//built in PHP function to encode the data in to JSON format
header('Content-Type: application/json; charset=utf8');
echo json_encode($json_data,true);





 // Binds the last executed statement as a result.
exit;






?>
