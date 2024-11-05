# HIS  - Electronic Health Information System

 The HIS is an open source software designed for use in Helth Units of Developing Countries.

It was designed by a multidisciplinary and cultural team. Its code is currently maintained and updated by Mozambican IT Specialists based in Quelimane and all modules are developed and tested at Quelimane Central Hospital.

It manages detailed patient clinical data during their clinical consultations in emergency services, outpatient or inwards.

It was designed to replace current paper records. Medical details can be entered directly into the database as the same time that a patient is examined or later. Laboratory tests, prescriptions and treatments can be requested through the computer network and performed without the need for paper records. A single screen can display an overview of all patient clinical details when they return for a new visit or when they are admitted.

For software demo or installation demonstrations, contact the development team of computer technicians from the various provinces and the Ministry of Health of Mozambique

Copyright, INJE UNIVERSITY (CNL) 2015 

==Portuguese===

SISH - Sistema Electrónico para Gestão Hospitalar

O SISH é um software de código aberto desenvolvido para uso nas Unidades Hospitalares nos Países em fase de Desenvolvimento. 

Foi concebido por uma equipe multidisciplinar e cultural. O seu códico é actualmente mantido e actualmente por Especialistas Informáticos moçambicanos com sede em Quelimane e todos módulos são desenvolvidos e tesntados no Hospital Central de Quelimane.

Faz gestão detalhada de dados clínicos do pacientes durante as consultas clínicas nos serviços de urgências, Consultas externas ou nas enfermarias.  

Foi projetado para substituir os actuais registros em papel. Detalhes médicos podem ser inseridos diretamente no banco de dados à medida que o paciente é examinado ou num período posterior. Testes laboratoriais, prescrições e tratamentos podem ser solicitados através da rede de computadores e realizados sem a necessidade de registros em papel. Uma única tela pode exibir uma visão geral de todos os detalhes clínicos do paciente quando eles retornam para uma nova visita ou quando são admitidos. 

Para casos de demonstrações d software ou instalação, pode-se contactar a equipe de desenvolvimento constituída por técnicos de informática das diversas províncias e do Ministério de Saúde de Moçambique.


Installation of SIS-H Quick guide

Install LAMP on the PC under LINUX *
Clone this repository with all its files to /var/www
Give this directory read privileges: sudo chmod 755 /var/www -R
The repository comes with a demonstration license. If you want the name of your hospital/practice to appear on the reports, apply for a license from sish.misau.gov.mz. This license is free. Copy the license file that you obtain from hhims.org into this directory (/www/).
You have to enter your information into the file: /var/www/application/config/database.php
At the top of this file there are four define commands that you will need to correct (e-mail is only used for sending out notifications - not essential).
These are:

DATABASE CONNECTION INFORMATIONS

$db['default']['hostname'] = 'localhost:3306';
$db['default']['dbdriver'] = 'mysql';
ENTER THE USER NAME AND PASSWOR DETAILS HERE FOR MYSQL

$db['default']['username'] = '';
$db['default']['password'] = '';
$db['default']['database'] = '';




Copyright, INJE UNIVERSITY (CNL) 2015


