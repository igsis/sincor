<?php
	function habilitarErro()
	{
		@ini_set('display_errors', '1');
		error_reporting(E_ALL);
	}
		
	// Framework
	//autentica login e cria inicia uma session
	function autenticalogin($login, $senha)
	{	
		$sql = "SELECT * FROM usuario AS usr
		INNER JOIN perfil AS pap ON pap.id = usr.idPerfil
		WHERE usr.login = '$login' AND usr.publicado = '1' LIMIT 0,1";
		$con = bancoMysqli();
		$query = mysqli_query($con,$sql);
		//query que seleciona os campos que voltarão para na matriz
		if($query)
		{
			//verifica erro no banco de dados
			if(mysqli_num_rows($query) > 0)
			{
				// verifica se retorna usuário válido
				$user = mysqli_fetch_array($query);
				if($user['senha'] == md5($_POST['senha']))
				{
					// compara as senhas
					session_start();
					$_SESSION['login'] = $user['login'];
					$_SESSION['perfil'] = $user['idPerfil'];
					$_SESSION['nome'] = $user['nome'];
					$_SESSION['idUsuario'] = $user['id'];
					$log = "Fez login.";
					gravarLog($log);
					header("Location: visual/index.php");
				}
				else
				{
					echo "A senha está incorreta.";
				}
			}
			else
			{
				echo "O usuário não existe.";
			}
		}
		else
		{
			echo "Erro no banco de dados";
		}	
	}
	//saudacao inicial
	function saudacao()
	{ 
		$hora = date('H');
		if(($hora > 12) AND ($hora <= 18))
		{
			return "Boa tarde";	
		}
		else if(($hora > 18) AND ($hora <= 23))
		{
			return "Boa noite";	
		}
		else if(($hora >= 0) AND ($hora <= 4))
		{
			return "Boa noite";	
		}
		else if(($hora > 4) AND ($hora <=12))
		{
			return "Bom dia";
		}
	}
	// Formatação de datas, valores
	// Retira acentos das strings
	function semAcento($string)
	{
		$newstring = preg_replace("/[^a-zA-Z0-9_.]/", "", strtr($string, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
		return $newstring;
	}
	//retorna data d/m/y de mysql/date(a-m-d)
	function exibirDataBr($data)
	{
		$timestamp = strtotime($data); 
		return date('d/m/Y', $timestamp);	
	}
	// retorna datatime sem hora
	function retornaDataSemHora($data)
	{
		$semhora = substr($data, 0, 10);
		return $semhora;
	}	
	//retorna data d/m/y de mysql/datetime(a-m-d H:i:s)	
	function exibirDataHoraBr($data)
	{
		$timestamp = strtotime($data); 
		return date('d/m/y - H:i:s', $timestamp);	
	}
	//retorna hora H:i de um datetime
	function exibirHora($data)
	{
		$timestamp = strtotime($data); 
		return date('H:i', $timestamp);	
	}
	//retorna data mysql/date (a-m-d) de data/br (d/m/a)
	function exibirDataMysql($data)
	{ 
		list ($dia, $mes, $ano) = explode ('/', $data);
		$data_mysql = $ano.'-'.$mes.'-'.$dia;
		return $data_mysql;
	}
	//retorna o endereço da página atual
	function urlAtual()
	{
		$dominio= $_SERVER['HTTP_HOST'];
		$url = "http://" . $dominio. $_SERVER['REQUEST_URI'];
		return $url;
	}
	//retorna valor xxx,xx para xxx.xx
	function dinheiroDeBr($valor)
	{
		$valor = str_ireplace(".","",$valor);
		$valor = str_ireplace(",",".",$valor);
		return $valor;
	}
	//retorna valor xxx.xx para xxx,xx
	function dinheiroParaBr($valor)
	{ 
		$valor = number_format($valor, 2, ',', '.');
		return $valor;
	}
	//use em problemas de codificacao utf-8
	function _utf8_decode($string)
	{
		$tmp = $string;
		$count = 0;
		while (mb_detect_encoding($tmp)=="UTF-8")
		{
			$tmp = utf8_decode($tmp);
			$count++;
		}
		for ($i = 0; $i < $count-1 ; $i++)
		{
			$string = utf8_decode($string);
		}
		return $string;
	}
	//retorna o dia da semana segundo um date(a-m-d)
	function diasemana($data)
	{
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, -3);
		$dia =  substr("$data", 8, 9);
		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
		switch($diasemana)
		{
			case"0": $diasemana = "Domingo";       break;
			case"1": $diasemana = "Segunda-Feira"; break;
			case"2": $diasemana = "Terça-Feira";   break;
			case"3": $diasemana = "Quarta-Feira";  break;
			case"4": $diasemana = "Quinta-Feira";  break;
			case"5": $diasemana = "Sexta-Feira";   break;
			case"6": $diasemana = "Sábado";        break;
		}
		return "$diasemana";
	}
	
	//soma(+) ou substrai(-) dias de um date(a-m-d)
	function somarDatas($data,$dias)
	{
		$data_final = date('Y-m-d', strtotime("$dias days",strtotime($data)));	
		return $data_final;
	}
	
	//retorna a diferença de dias entre duas datas
	function diferencaDatas($data_inicial,$data_final)
	{
		// Define os valores a serem usados
		// Usa a função strtotime() e pega o timestamp das duas datas:
		$time_inicial = strtotime($data_inicial);
		$time_final = strtotime($data_final);
		// Calcula a diferença de segundos entre as duas datas:
		$diferenca = $time_final - $time_inicial; // 19522800 segundos
		// Calcula a diferença de dias
		$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
		return $dias;
	}
					
	function gravarLog($log)
	{
		//grava na tabela log os inserts e updates
		$logTratado = addslashes($log);
		$idLogin = $_SESSION['idUsuario'];
		$ip = $_SERVER["REMOTE_ADDR"];
		$data = date('Y-m-d H:i:s');
		$sql = "INSERT INTO `log` (`id`, `idUsuario`, `ip`, `data`, `descricao`) 
			VALUES (NULL, '$idLogin', '$ip', '$data', '$logTratado')";
		$mysqli = bancoMysqli();
		$mysqli->query($sql);
	}
	
	function geraOpcao($tabela,$select)
	{
		//gera os options de um select
		$sql = "SELECT * FROM $tabela ORDER BY 2";
		
		$con = bancoMysqli();
		$query = mysqli_query($con,$sql);
		while($option = mysqli_fetch_row($query))
		{
			if($option[0] == $select)
			{
				echo "<option value='".$option[0]."' selected >".$option[1]."</option>";	
			}
			else
			{
				echo "<option value='".$option[0]."'>".$option[1]."</option>";	
			}
		}
	}
	
	function geraCombobox($tabela,$campo,$order,$select)
	{
		//gera os options de um select
		$sql = "SELECT * FROM $tabela ORDER BY $order";
		
		$con = bancoMysqli();
		$query = mysqli_query($con,$sql);
		while($option = mysqli_fetch_row($query))
		{
			if($option[0] == $select)
			{
				echo "<option value='".$option[0]."' selected >".$option[$campo]."</option>";	
			}
			else
			{
				echo "<option value='".$option[0]."'>".$option[$campo]."</option>";	
			}
		}
	}
	
	function recuperaModulo($pag)
	{
		$sql = "SELECT * FROM modulo WHERE pagina = '$pag'";
		$con = bancoMysqli();
		$query = mysqli_query($con,$sql);
		$modulo = mysqli_fetch_array($query);
		return $modulo;
	}
	
	function retornaModulos($perfil)
	{
		// recupera quais módulos o usuário tem acesso
		$sql = "SELECT * FROM perfil WHERE id = $perfil"; 
		$con = bancoMysqli();
		$query = mysqli_query($con,$sql);
		$campoFetch = mysqli_fetch_array($query);
		$nome = "";
		while($fieldinfo = mysqli_fetch_field($query))
		{
			if(($campoFetch[$fieldinfo->name] == 1) AND ($fieldinfo->name != 'id'))
			{
				$descricao = recuperaModulo($fieldinfo->name);
				$nome = $nome.";\n + ".$descricao['nome'];
			}
		}
		return substr($nome,1);
	}
	
	function listaModulos($perfil)
	{
		//gera as tds dos módulos a carregar
		// recupera quais módulos o usuário tem acesso
		$sql = "SELECT * FROM perfil WHERE id = $perfil"; 
		$con = bancoMysqli();
		$query = mysqli_query($con,$sql);
		$campoFetch = mysqli_fetch_array($query);
		while($fieldinfo = mysqli_fetch_field($query))
		{
			if(($campoFetch[$fieldinfo->name] == 1) AND ($fieldinfo->name != 'id'))
			{
				$descricao = recuperaModulo($fieldinfo->name);
				echo "<tr>";
				echo "<td class='list_description'><b>".$descricao['nome']."</b></td>";
				echo "<td class='list_description'>".$descricao['descricao']."</td>";
				echo "
					<td class='list_description'>
						<form method='POST' action='?perfil=$fieldinfo->name'>
							<input type ='submit' class='btn btn-theme btn-lg btn-block' value='carregar'></td></form>"	;
				echo "</tr>";
			}
		}
	}
	
	function listaModulosAlfa($perfil)
	{
		//gera as tds dos módulos a carregar
		$con = bancoMysqli();
		// recupera os módulos do sistema
		$sql_modulos = "SELECT pagina FROM modulo ORDER BY nome";
		$query_modulos = mysqli_query($con,$sql_modulos);
		while($modulos = mysqli_fetch_array($query_modulos))
		{
			$sql = "SELECT * FROM perfil WHERE id = $perfil"; 
			$query = mysqli_query($con,$sql);
			$campoFetch = mysqli_fetch_array($query);
			if(($campoFetch[$modulos['pagina']] == 1) AND ($campoFetch[$modulos['pagina']] != 'perfil.id'))
			{
				$descricao = recuperaModulo($modulos['pagina']);
				echo "<tr>";
				echo "<td class='list_description'><b>".$descricao['nome']."</b></td>";
				echo "<td class='list_description'>".$descricao['descricao']."</td>";
				echo "
					<td class='list_description'>
						<form method='POST' action='?perfil=".$modulos['pagina']."' >
							<input type ='submit' class='btn btn-theme btn-lg btn-block' value='carregar'></td></form>"	;
				echo "</tr>";
			}
		}	
	}
		
	function recuperaDados($tabela,$campo,$variavelCampo)
	{
		//retorna uma array com os dados de qualquer tabela. serve apenas para 1 registro.
		$con = bancoMysqli();
		$sql = "SELECT * FROM $tabela WHERE ".$campo." = '$variavelCampo' LIMIT 0,1";
		$query = mysqli_query($con,$sql);
		$campo = mysqli_fetch_array($query);
		return $campo;		
	}
	
	
	function verificaExiste($idTabela,$idCampo,$idDado,$st)
	{
		//retorna uma array com indice 'numero' de registros e 'dados' da tabela
		$con = bancoMysqli();
		if($st == 1)
		{
			// se for 1, é uma string
			$sql = "SELECT * FROM $idTabela WHERE $idCampo = '%$idDado%'";
		}
		else
		{
			$sql = "SELECT * FROM $idTabela WHERE $idCampo = '$idDado'";
		}
		$query = mysqli_query($con,$sql);
		$numero = mysqli_num_rows($query);
		$dados = mysqli_fetch_array($query);
		$campo['numero'] = $numero;
		$campo['dados'] = $dados;	
		return $campo;
	}
	
	function recuperaIdDado($tabela,$id)
	{ 
		$con = bancoMysqli();
		//recupera os nomes dos campos
		$sql = "SELECT * FROM $tabela";
		$query = mysqli_query($con,$sql);
		$campo01 = mysqli_field_name($query, 0);
		$campo02 = mysqli_field_name($query, 1);	
		$sql = "SELECT * FROM $tabela WHERE $campo01 = $id";
		$query = mysql_query($sql);
		$campo = mysql_fetch_array($query);
		return $campo[$campo02];
	}
		
	function checar($id)
	{
		//funcao para imprimir checked do checkbox
		if($id == 1)
		{
			echo "checked";	
		}	
	}
	
	function valorPorExtenso($valor=0)
	{
		//retorna um valor por extenso
		$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
		$z=0;
		$valor = number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];
		$rt = "";
		// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;) 
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++)
		{
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
		}
		return($rt ? $rt : "zero");
	}
	
	function analisaArray($array)
	{
		//imprime o conteúdo de uma array
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
	
	function recuperaUltimo($tabela)
	{
		$con = bancoMysqli();
		$sql = "SELECT * FROM $tabela ORDER BY 1 DESC LIMIT 0,1";
		$query =  mysqli_query($con,$sql);
		$campo = mysqli_fetch_array($query);
		return $campo[0];	
	}
		
	function retornaMes($mes)
	{
		switch($mes)
		{
			case "01":
				return "Janeiro";
			break;
			case "02":
				return "Fevereiro";
			break;
			case "03":
				return "Março";
			break;
			case "04":
				return "Abril";
			break;
			case "05":
				return "Maio";
			break;
			case "06":
				return "Junho";
			break;
			case "07":
				return "Julho";
			break;
			case "08":
				return "Agosto";
			break;
			case "09":
				return "Setembro";
			break;
			case "10":
				return "Outubro";
			break;
			case "11":
				return "Novembro";
			break;
			case "12":
				return "Dezembro";
			break;
		}
	}
	
	function retornaMesExtenso($data)
	{
		$meses = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
		$data = explode("-", $dataMysql);
		$mes = $data[1];
		return $meses[($mes) - 1];
	}
	
	//retorna o dia da semana segundo um date(a-m-d)
	function diaSemanaBase($data)
	{ 
		$ano =  substr("$data", 0, 4);
		$mes =  substr("$data", 5, -3);
		$dia =  substr("$data", 8, 9);
		$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
		switch($diasemana)
		{
			case"0":
				$diasemana = "domingo";
			break;
			case"1":
				$diasemana = "segunda";
			break;
			case"2":
				$diasemana = "terca";
			break;
			case"3":
				$diasemana = "quarta";
			break;
			case"4":
				$diasemana = "quinta";
			break;
			case"5":
				$diasemana = "sexta";
			break;
			case"6":
				$diasemana = "sabado";
			break;
		}
		return "$diasemana";
	}
	
	function soNumero($str)
	{
		return preg_replace("/[^0-9]/", "", $str);
	}
?>