<?php
	if(isset($_FILES['arquivo']))
	{
		$mensagem = "";
		// Pasta onde o arquivo vai ser salvo
		$_UP['pasta'] = '../uploads/';
		// Tamanho máximo do arquivo (em Bytes)
		$_UP['tamanho'] = 1024 * 1024 * 50; // 2Mb
		// Array com as extensões permitidas
		$_UP['extensoes'] = array('xls', 'xlsx');
		// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
		$_UP['renomeia'] = true;
		// Array com os tipos de erros de upload do PHP
		$_UP['erros'][0] = 'Não houve erro';
		$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
		$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
		$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
		$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
		// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
		if ($_FILES['arquivo']['error'] != 0)
		{
		  die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['arquivo']['error']]);
		  $mensagem .= "Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['arquivo']['error']];
		  exit; // Para a execução do script
		}
		// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
		// Faz a verificação da extensão do arquivo
		// Faz a verificação do tamanho do arquivo
		if ($_UP['tamanho'] < $_FILES['arquivo']['size'])
		{
		  $mensagem .= "O arquivo enviado é muito grande, envie arquivos de até 50Mb.";
		  exit;
		}
		// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
		// Primeiro verifica se deve trocar o nome do arquivo
		if ($_UP['renomeia'] == true)
		{
			// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
			$dataUnique = date('YmdHis');
			$arquivo_final = $dataUnique."_".semAcento($_FILES['arquivo']['name']);
		}
		else
		{
			// Mantém o nome original do arquivo
			$nome_final = $_FILES['arquivo']['name'];
		}  
		// Depois verifica se é possível mover o arquivo para a pasta escolhida
		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $arquivo_final))
		{
			// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
			$mensagem .=  "Upload efetuado com sucesso!<br />";
			$mensagem .= '<a href="' . $_UP['pasta'] . $arquivo_final . '">Clique aqui para acessar o arquivo</a>';
			require_once("../include/phpexcel/Classes/PHPExcel.php");
			$inputFileName = $_UP['pasta'] . $arquivo_final;	
			//  Read your Excel workbook
			try
			{
				$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			}
			catch(Exception $e)
			{
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			//Apagamos a tabela empenhado_raw
			$sql_limpa = "TRUNCATE TABLE empenhado_raw";
			if(mysqli_query($con,$sql_limpa))
			{
				$mensagem .= "<br />Tabela empenhado_raw limpa <br />";	
			}
			else
			{
				$mensagem .= "Erro ao limpar a tabela empenhado_raw <br />";	
			}		
			//  Loop through each row of the worksheet in turn
			for ($row = 1; $row <= $highestRow; $row++)
			{ 
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
					NULL,
					TRUE,
					FALSE);
				//  Insert row data array into your database of choice here
				if($row == 1)
				{
					// Dados da Tabela
					$mensagem .= "Arquivo Empenhado";
					$data = date("Y-m-d H:i:s");
					$mens_sof = "Arquivo Empenhado - ".$data;
					$sql_atualizacao = "INSERT INTO `atualizacao` (`id`, `data`, `texto`) VALUES (NULL, '$data', '$mens_sof')";
					$query_atualizacao = mysqli_query($con,$sql_atualizacao);
					if($query_atualizacao)
					{
						$mensagem .= " Atualização registrada.<br />";	
					}
				}	
				if($row > 2)
				{
					// Insere na tabela empenhado_raw
					$cod_idt_eph = $rowData[0][0];
					$dt_eph = $rowData[0][1];
					$cod_eph = $rowData[0][2];
					$ano_eph = $rowData[0][3];
					$cod_tip_eph_sof = $rowData[0][4];
					$cod_nro_pcss_sof = $rowData[0][5];
					$cod_tip_doc = $rowData[0][6];
					$cod_idt_modl_lici = $rowData[0][7];
					$txt_obs_eph = $rowData[0][8];
					$val_tot_eph = $rowData[0][9];
					$val_tot_canc_eph = $rowData[0][10];
					$val_tot_lqdc_eph = $rowData[0][11];
					$val_tot_pago_eph = $rowData[0][12];
					$val_tot_a_liq_eph = $rowData[0][13];
					$val_tot_a_pag_eph = $rowData[0][14];
					$cod_idt_crdr_sof = $rowData[0][15];
					$nom_rzao_soci_sof = $rowData[0][16];
					$cod_nat_crdr = $rowData[0][17];
					$cod_cpf_cnpj_sof = $rowData[0][18];
					$cod_idt_item_desp = $rowData[0][19];
					$cod_item_desp_sof = $rowData[0][20];
					$txt_item_desp = $rowData[0][21];
					$cod_idt_sub_elem = $rowData[0][22];
					$cod_sub_elem_desp = $rowData[0][23];
					$txt_sub_elem = $rowData[0][24];
					$cod_idt_cta_desp = $rowData[0][25];
					$ind_cta_sint_anlt = $rowData[0][26];
					$cod_catg_ecmc = $rowData[0][27];
					$cod_grup_desp = $rowData[0][28];
					$cod_modl_aplc = $rowData[0][29];
					$cod_elem_desp = $rowData[0][30];
					$cod_sub_elem_conta_desp = $rowData[0][31];
					$cod_idt_fcao_govr = $rowData[0][32];
					$cod_idt_sub_fcao = $rowData[0][33];
					$cod_idt_pgm_govr = $rowData[0][34];
					$cod_idt_proj_atvd = $rowData[0][35];
					$cod_org_emp_exect = $rowData[0][36];
					$txt_org_emp_exect = $rowData[0][37];
					$cod_unid_orcm_sof_exect = $rowData[0][38];
					$txt_unid_orcm_exect = $rowData[0][39];
					$txt_dotacao_fmt = $rowData[0][40];
					$cod_fcao_govr = $rowData[0][41];
					$txt_fcao_govr = $rowData[0][42];
					$cod_pgm_govr = $rowData[0][43];
					$txt_pgm_govr = $rowData[0][44];
					$cod_sub_fcao_govr = $rowData[0][45];
					$txt_sub_fcao_govr = $rowData[0][46];
					$cod_proj_atvd_sof_p = $rowData[0][47];
					$txt_proj_atvd_p = $rowData[0][48];
					$cod_modl_lici_sof = $rowData[0][49];
					$txt_modl_lici = $rowData[0][50];
					$cod_emp_pmsp = $rowData[0][51];
					$nom_emp_sof = $rowData[0][52];
					$cod_idt_font_rec = $rowData[0][53];
					$cod_idt_dota = $rowData[0][54];
					$cod_idt_cda_desp1 = $rowData[0][55];
					$cod_cta_desp = $rowData[0][56];
					$txt_cta_desp = $rowData[0][57];
					$cod_font_rec = $rowData[0][58];
					$txt_font_rec = $rowData[0][59];
					$sql_insere = "INSERT INTO `empenhado_raw`
						(`COD_IDT_EPH`, 
						`DT_EPH`, 
						`COD_EPH`, 
						`ANO_EPH`, 
						`COD_TIP_EPH_SOF`, 
						`COD_NRO_PCSS_SOF`, 
						`COD_TIP_DOC`, 
						`COD_IDT_MODL_LICI`, 
						`TXT_OBS_EPH`, 
						`VAL_TOT_EPH`, 
						`VAL_TOT_CANC_EPH`, 
						`VAL_TOT_LQDC_EPH`, 
						`VAL_TOT_PAGO_EPH`, 
						`VAL_TOT_A_LIQ_EPH`, 
						`VAL_TOT_A_PAG_EPH`, 
						`COD_IDT_CRDR_SOF`, 
						`NOM_RZAO_SOCI_SOF`, 
						`COD_NAT_CRDR`, 
						`COD_CPF_CNPJ_SOF`, 
						`COD_IDT_ITEM_DESP`, 
						`COD_ITEM_DESP_SOF`, 
						`TXT_ITEM_DESP`, 
						`COD_IDT_SUB_ELEM`, 
						`COD_SUB_ELEM_DESP`, 
						`TXT_SUB_ELEM`, 
						`COD_IDT_CTA_DESP`, 
						`IND_CTA_SINT_ANLT`, 
						`COD_CATG_ECMC`, 
						`COD_GRUP_DESP`, 
						`COD_MODL_APLC`, 
						`COD_ELEM_DESP`, 
						`COD_SUB_ELEM_CONTA_DESP`, 
						`COD_IDT_FCAO_GOVR`, 
						`COD_IDT_SUB_FCAO`, 
						`COD_IDT_PGM_GOVR`, 
						`COD_IDT_PROJ_ATVD`, 
						`COD_ORG_EMP_EXECT`, 
						`TXT_ORG_EMP_EXECT`, 
						`COD_UNID_ORCM_SOF_EXECT`, 
						`TXT_UNID_ORCM_EXECT`, 
						`TXT_DOTACAO_FMT`, 
						`COD_FCAO_GOVR`, 
						`TXT_FCAO_GOVR`, 
						`COD_PGM_GOVR`, 
						`TXT_PGM_GOVR`, 
						`COD_SUB_FCAO_GOVR`, 
						`TXT_SUB_FCAO_GOVR`, 
						`COD_PROJ_ATVD_SOF_P`, 
						`TXT_PROJ_ATVD_P`, 
						`COD_MODL_LICI_SOF`, 
						`TXT_MODL_LICI`, 
						`COD_EMP_PMSP`, 
						`NOM_EMP_SOF`, 
						`COD_IDT_FONT_REC`, 
						`COD_IDT_DOTA`, 
						`COD_IDT_CTA_DESP1`, 
						`COD_CTA_DESP`, 
						`TXT_CTA_DESP`, 
						`COD_FONT_REC`, 
						`TXT_FONT_REC`)
						VALUES 
						('$cod_idt_eph' , 
						'$dt_eph' , 
						'$cod_eph' , 
						'$ano_eph' , 
						'$cod_tip_eph_sof' , 
						'$cod_nro_pcss_sof' , 
						'$cod_tip_doc' , 
						'$cod_idt_modl_lici' , 
						'$txt_obs_eph' , 
						'$val_tot_eph' , 
						'$val_tot_canc_eph' , 
						'$val_tot_lqdc_eph' , 
						'$val_tot_pago_eph' , 
						'$val_tot_a_liq_eph' , 
						'$val_tot_a_pag_eph' ,
						'$cod_idt_crdr_sof' ,
						'$nom_rzao_soci_sof' ,
						'$cod_nat_crdr' ,
						'$cod_cpf_cnpj_sof' ,
						'$cod_idt_item_desp' ,
						'$cod_item_desp_sof' ,
						'$txt_item_desp' ,
						'$cod_idt_sub_elem' ,
						'$cod_sub_elem_desp' ,
						'$txt_sub_elem' ,
						'$cod_idt_cta_desp' ,
						'$ind_cta_sint_anlt' ,
						'$cod_catg_ecmc' ,
						'$cod_grup_desp' ,
						'$cod_modl_aplc' ,
						'$cod_elem_desp' ,
						'$cod_sub_elem_conta_desp' ,
						'$cod_idt_fcao_govr' ,
						'$cod_idt_sub_fcao' ,
						'$cod_idt_pgm_govr' ,
						'$cod_idt_proj_atvd' ,
						'$cod_org_emp_exect' ,
						'$txt_org_emp_exect' ,
						'$cod_unid_orcm_sof_exect' ,
						'$txt_unid_orcm_exect' ,
						'$txt_dotacao_fmt' ,
						'$cod_fcao_govr' ,
						'$txt_fcao_govr' ,
						'$cod_pgm_govr' ,
						'$txt_pgm_govr' ,
						'$cod_sub_fcao_govr' ,
						'$txt_sub_fcao_govr' ,
						'$cod_proj_atvd_sof_p' ,
						'$txt_proj_atvd_p' ,
						'$cod_modl_lici_sof' ,
						'$txt_modl_lici' ,
						'$cod_emp_pmsp' ,
						'$nom_emp_sof' ,
						'$cod_idt_font_rec' ,
						'$cod_idt_dota' ,
						'$cod_idt_cda_desp1' ,
						'$cod_cta_desp' ,
						'$txt_cta_desp' ,
						'$cod_font_rec' ,
						'$txt_font_rec') ";
					$query_insere = mysqli_query($con,$sql_insere);
				}
			}
			if($query_insere)
			{
				$mensagem .= "Arquivo inserido na tabela empenhado_raw. <br />";

				$sql_atualiza_idOrgao = "UPDATE orcamento_central, empenhado_raw 
					SET idOrgao = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,1,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idUnidade = "UPDATE orcamento_central, empenhado_raw
					SET idUnidade = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,4,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idFuncao = "UPDATE orcamento_central, empenhado_raw
					SET idFuncao = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,7,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idSubFuncao = "UPDATE orcamento_central, empenhado_raw
					SET idSubfuncao = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,10,3) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idPrograma = "UPDATE orcamento_central, empenhado_raw
					SET idPrograma = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,14,4) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_projetoAtividade = "UPDATE orcamento_central, empenhado_raw
					SET projetoAtividade = (SELECT DISTINCT COD_PROJ_ATVD_SOF_P 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idCategoriaEconomia = "UPDATE orcamento_central, empenhado_raw
					SET idCategoriaEconomica = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,25,1) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idGrupoDespesa = "UPDATE orcamento_central, empenhado_raw
					SET idGrupoDespesa = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,26,1) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idModalidadeAplicada = "UPDATE orcamento_central, empenhado_raw
					SET idModalidadeAplicada = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,27,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idElementoDespesa = "UPDATE orcamento_central, empenhado_raw
					SET idElementoDespesa = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,29,4) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idFonte = "UPDATE orcamento_central, empenhado_raw
					SET idFonte = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,34,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_empenhado = "UPDATE orcamento_central, empenhado_raw
					SET empenhado = (SELECT DISTINCT SUM(VAL_TOT_EPH)-SUM(VAL_TOT_CANC_EPH) 
					AS TOTAL_EMPENHADO 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";

				$sql_atualiza_idOrgao = "UPDATE orcamento_central, empenhado_raw 
					SET idOrgao = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,1,2) 
					FROM empenhado_raw
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idUnidade = "UPDATE orcamento_central, empenhado_raw 
					SET idUnidade = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,4,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idFuncao = "UPDATE orcamento_central, empenhado_raw 
					SET idFuncao = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,7,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idSubFuncao = "UPDATE orcamento_central, empenhado_raw 
					SET idSubfuncao = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,10,3) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idPrograma = "UPDATE orcamento_central, empenhado_raw 
					SET idPrograma = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,14,4) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idRamo = "UPDATE orcamento_central, empenhado_raw 
					SET idRamo = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,19,1) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idAcao = "UPDATE orcamento_central, empenhado_raw 
					SET idAcao = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,21,3) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_projetoAtividade = "UPDATE orcamento_central, empenhado_raw 
					SET projetoAtividade = (SELECT DISTINCT COD_PROJ_ATVD_SOF_P 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idCategoriaEconomia = "UPDATE orcamento_central, empenhado_raw 
					SET idCategoriaEconomica = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,25,1) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idGrupoDespesa = "UPDATE orcamento_central, empenhado_raw 
					SET idGrupoDespesa = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,26,1) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idModalidadeAplicada = "UPDATE orcamento_central, empenhado_raw 
					SET idModalidadeAplicada = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,27,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idElementoDespesa = "UPDATE orcamento_central, empenhado_raw 
					SET idElementoDespesa = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,29,4) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_idFonte = "UPDATE orcamento_central, empenhado_raw 
					SET idFonte = (SELECT DISTINCT SUBSTRING(TXT_DOTACAO_FMT,34,2) 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";
				$sql_atualiza_empenhado = "UPDATE orcamento_central, empenhado_raw 
					SET empenhado = (SELECT DISTINCT SUM(VAL_TOT_EPH)-SUM(VAL_TOT_CANC_EPH) AS TOTAL_EMPENHADO 
					FROM empenhado_raw 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT) 
					WHERE orcamento_central.dotacao = empenhado_raw.TXT_DOTACAO_FMT";

				$query_atualiza0 = mysqli_query($con,$sql_atualiza_idOrgao);
				$query_atualiza1 = mysqli_query($con,$sql_atualiza_idUnidade);
				$query_atualiza2 = mysqli_query($con,$sql_atualiza_idFuncao);
				$query_atualiza3 = mysqli_query($con,$sql_atualiza_idSubFuncao);
				$query_atualiza4 = mysqli_query($con,$sql_atualiza_idPrograma);
				$query_atualiza5 = mysqli_query($con,$sql_atualiza_idRamo);
				$query_atualiza6 = mysqli_query($con,$sql_atualiza_idAcao);		
				$query_atualiza7 = mysqli_query($con,$sql_atualiza_projetoAtividade);
				$query_atualiza8 = mysqli_query($con,$sql_atualiza_idCategoriaEconomia);
				$query_atualiza9 = mysqli_query($con,$sql_atualiza_idGrupoDespesa);
				$query_atualiza10 = mysqli_query($con,$sql_atualiza_idModalidadeAplicada);
				$query_atualiza11 = mysqli_query($con,$sql_atualiza_idElementoDespesa);
				$query_atualiza12 = mysqli_query($con,$sql_atualiza_idFonte);
				$query_atualiza13 = mysqli_query($con,$sql_atualiza_empenhado);
				
				/************ DESCRIÇÃO SIMPLIFICADA *****************/
				$sql_update_descSimplificada = "UPDATE orcamento_central, simplificada_orcamento
					SET idDescricaoSimplificada = (SELECT DISTINCT simplificada FROM simplificada_orcamento
					WHERE orcamento_central.projetoAtividade = simplificada_orcamento.projetoAtividade AND orcamento_central.idElementoDespesa = simplificada_orcamento.elemento LIMIT 0,1) 
					WHERE orcamento_central.projetoAtividade = simplificada_orcamento.projetoAtividade AND orcamento_central.idElementoDespesa = simplificada_orcamento.elemento";
				$query_update_descSimplificada	= mysqli_query($con,$sql_update_descSimplificada);
				/* DESCRIÇÃO SIMPLIFICADA - FIM */
				/* DESCRIÇÃO COMPLETA */
				$sql_update_descCompleta = "UPDATE orcamento_central, completa_orcamento
					SET idDescricaoCompleta = (SELECT DISTINCT completa FROM completa_orcamento
					WHERE orcamento_central.projetoAtividade = completa_orcamento.projetoAtividade AND orcamento_central.idElementoDespesa = completa_orcamento.elemento LIMIT 0,1) 
					WHERE orcamento_central.projetoAtividade = completa_orcamento.projetoAtividade AND orcamento_central.idElementoDespesa = completa_orcamento.elemento";
				$query_update_descCompleta	= mysqli_query($con,$sql_update_descCompleta);
				/* DESCRIÇÃO COMPLETA - FIM */
			}			
			else
			{
				$mensagem .= "Erro ao inserir. <br />";
			}
		}
		else
		{
			// Não foi possível fazer o upload, provavelmente a pasta está incorreta
			$mensagem =  "Não foi possível enviar o arquivo, tente novamente";
		}
	}
?>
<section id="contact" class="home-section bg-white">
  	<div class="container">
		<div class="form-group">
			<div class="sub-title">
				<h2>Integração SOF / SINCOR</h2><br/>
				<h2>EMPENHADOS</h2>
				<h4>Aqui você pode subir arquivos de empenhados</h4>
				<h3></h3>
			</div>       
		</div>
	  	<div class="row">
	  		<div class="col-md-offset-1 col-md-10">
		<?php
			if(isset($rowData))
			{
				if(isset($mensagem))
				{
					echo $mensagem;
				} 	
			}
			else
			{
		?>
				<form method="POST" action="?perfil=contabilidade&p=sof_empenhado" enctype="multipart/form-data">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Arquivo em EXCEL (Máximo 50MB)</strong><br/>
							<input type="file" class="form-control" name="arquivo" /	>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="enviado" />
							<input type="submit" value="Fazer upload" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form> 
		<?php
			}
		?>                  
	  		</div>	
	  	</div>
	</div>
</section> 