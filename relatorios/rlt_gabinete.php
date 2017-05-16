<?php

// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


// Instanciamos a classe
$objPHPExcel = new PHPExcel();


// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
$objPHPExcel->getProperties()->setCreator("SINCOR");
$objPHPExcel->getProperties()->setLastModifiedBy("SINCOR");
$objPHPExcel->getProperties()->setTitle("Relatório");
$objPHPExcel->getProperties()->setSubject("Relatório");
$objPHPExcel->getProperties()->setDescription("Gerado automaticamente a partir do SINCOR");
$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
$objPHPExcel->getProperties()->setCategory("Teste");


// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);


// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Orgão')
			->setCellValue('A1', 'Unidade')
            ->setCellValue('C1', "Nome Simplificado")
            ->setCellValue("D1", "Saldo Orçado")
            ->setCellValue("E1", "Saldo Congelado")
			->setCellValue("F1", "Saldo Descongelado");
		
		
//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray
(
	array
	(
		'fill' => array
		(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'E0EEEE')
		),
	)
);


//Consulta
$con = bancoMysqli();
//Foto Aprovada
$sql_busca = "SELECT `id`, `dotacao`, `idOrgao`, `idUnidade`, `idFuncao`, `idSubfuncao`, `idPrograma`, `idElementoDespesa`, `idAcao`, `idCategoriaEconomica`, `idGrupoDespesa`, `idModalidadeAplicada`, `idElementoDespesa`, `idFonte`, `idDescricaoSimplificada`, `idDescricaoCompleta`, `projetoAtividade`,  SUM(saldoOrcado) AS saldoOrcado, `creditoTramitacao`, SUM(totalCongelado) AS totalCongelado, saldoDotacao, `saldoReservas`, `empenhado` FROM orcamento_central WHERE id != '' GROUP BY idOrgao, idUnidade, idDescricaoSimplificada ORDER BY idOrgao, idUnidade";
$query_busca = mysqli_query($con,$sql_busca);
$i = 2;
while($orcamento = mysqli_fetch_array($query_busca))
{ 
	$orgao = recuperaDados("orgao","id",$orcamento['idOrgao']);
	$unidade = recuperaDados("unidade","id",$orcamento['idUnidade']);	
	$projeto = recuperaDados("projeto_atividade","id",$orcamento['projetoAtividade']);
	$descricaoS = recuperaDados("descricao_simplificada","id",$orcamento['idDescricaoSimplificada']);
	$a = "A".$i;
	$b = "B".$i;
	$c = "C".$i;
	$d = "D".$i;
	$e = "E".$i;
	$f = "F".$i;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($a, $orgao['descricao'])
				->setCellValue($b, $unidade['descricao'])
				->setCellValue($c, $descricaoS['descricaoSimplificada'])
				->setCellValue($d, $orcamento['saldoOrcado'])
				->setCellValue($e, $orcamento['totalCongelado'])
				->setCellValue($f, $orcamento['saldoOrcado']-$orcamento['totalCongelado']);
	$i++;
}

// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle(date("Y-m-d"));

foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) 
{
    $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
} 


$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
    ob_start();
	
$nome_arquivo = date("Y-m-d")."_sincor_relatorio.xls";



// Cabeçalho do arquivo para ele baixar(Excel2007)
header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nome_arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necessário
header('Cache-Control: max-age=1');

// Acessamos o 'Writer' para poder salvar o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
$objWriter->save('php://output'); 

exit;

?>