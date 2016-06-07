<?php
/* {{{ NAVi - Ambiente Interativo de Aprendizagem
Direitos Autorais Reservados (c), 2004. Equipe de desenvolvimento em: <http://navi.ea.ufrgs.br> 

    Em caso de dúvidas e/ou sugestões, contate: <navi@ufrgs.br> ou escreva para CPD-UFRGS: Rua Ramiro Barcelos, 2574, portão K. Porto Alegre - RS. CEP: 90035-003

Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo sob os termos da Licença Pública Geral GNU conforme publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (a seu critério) qualquer versão posterior.

    Este programa é distribuído na expectativa de que seja útil, porém, SEM NENHUMA GARANTIA;
    nem mesmo a garantia implícita de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.
    Consulte a Licença Pública Geral do GNU para mais detalhes.
    

    Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto com este programa;
    se não, escreva para a Free Software Foundation, Inc., 
    no endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
    
}}} */

// retorna todos os itens de menu disponiveis nessa instalacao do navi
function getItensMenu() {
	$strSQL=" SELECT * FROM menu ORDER BY M.ordem";
	 
    return mysql_query($strSQL);	
}

function getItensMenuAtivos($codInstanciaGlobal)
{
	$strSQL=" SELECT * FROM menu M".
			" INNER JOIN menuinstancia MI on (M.codMenu = MI.codMenu)" .
			" WHERE MI.codInstanciaGlobal=".$codInstanciaGlobal." ORDER BY MI.ordemInstancia,M.ordem";
//	 echo $strSQL;	
	  return mysql_query($strSQL);	

}

function getItensMenuInativos($codInstanciaGlobal)
{
	/*$strSQL=" SELECT * FROM menu M".
			" WHERE M.codMenu NOT IN ( SELECT codMenu FROM menuinstancia MI WHERE MI.codInstanciaGlobal=".$codInstanciaGlobal.")   ORDER BY M.ordem ";*/

$strSQL =" SELECT M.codMenu, M.nomeMenu, M.urlMenu, M.descricaoMenu, M.imagem , M.ordem  FROM menu M".
		  "	LEFT OUTER JOIN menuinstancia MI ON (M.codMenu=MI.codMenu AND MI.codInstanciaGlobal=".$codInstanciaGlobal.")".
		  "	WHERE MI.codMenu IS NULL ORDER BY M.ordem" ;
  return mysql_query($strSQL);	

}

function editaMenuInstancia($itensMenuInativos, $itensMenuInstancia, $codInstanciaGlobal, $ordemMenus="")
{	$i=0;
	while($itensMenuInativos[$i])//retira os intes do menu que não devem estar na turna
	{
		$strSQL= "DELETE FROM menuinstancia WHERE codMenu=".$itensMenuInativos[$i]." AND codInstanciaGlobal=".$codInstanciaGlobal ;
		
		mysql_query($strSQL);
		$i ++;
	}
	


$menuAtivo=getItensMenuAtivos($codInstanciaGlobal);
$update = mysql_fetch_array($menuAtivo);
if(empty($update))//se não tiver nenhum itemMenu na turma insere todos
{

		$j=0;
		while($itensMenuInstancia[$j])
		{
			
			$strSQL= "INSERT INTO menuinstancia (codMenu ,codInstanciaGlobal ,ordemInstancia ) ".
					 " VALUES ('".$itensMenuInstancia[$j]."','".$codInstanciaGlobal."','".$j."')";
				mysql_query($strSQL);
			$j ++;
		}
}else{ //se tiver itenMenu na turma compara com array dos novos menus da turma e faz update desses 
	while($update)
	{	
		$j=0;
		while($itensMenuInstancia[$j])
		{
			if($update["codMenu"]==$itensMenuInstancia[$j])
			{
				$strSQL= "UPDATE menuinstancia SET ordemInstancia=".$j." WHERE codMenu=".$itensMenuInstancia[$j]." AND codInstanciaGlobal=".$codInstanciaGlobal;
			
				mysql_query($strSQL);
			}else
				{
				//erro de logica, compara um 1:n vai acabar duplicando sem necessidade, o efeito final é como se estivesse feito tudo certo porem faz consultas desnecessarias ao banco;rever otimização
				$strSQL= "INSERT INTO menuinstancia (codMenu ,codInstanciaGlobal ,ordemInstancia ) ".
					 " VALUES ('".$itensMenuInstancia[$j]."','".$codInstanciaGlobal."','".$j."')";
			
				mysql_query($strSQL);
			}

			
			$j ++;
		}
		$update = mysql_fetch_array($menuAtivo);
	}
}
	return true;
}

function gravaMenuInstancia($codInstanciaGlobal,$codMenu){
  $strSQL = "select * from configuracaogeralinstancia 
             where codInstanciaGlobal= ".quote_smart($codInstanciaGlobal);

  $rs = mysql_query($strSQL);

  if(!mysql_fetch_array($rs)){
      $strSQL = "insert into configuracaogeralinstancia (codInstanciaGlobal,codMenuInicial )values('".quote_smart($codInstanciaGlobal)."','".$codMenu."')";  
    return mysql_query($strSQL);	
  }else{
     $strSQL = "update configuracaogeralinstancia set";
     $strSQL.= " codMenuInicial = ".$codMenu."";
     $strSQL.= " where codInstanciaGlobal = ".quote_smart($codInstanciaGlobal)."";
    //print_r($strSQL); die();
        return mysql_query($strSQL);	
   }
}

/*
function getNiveis($codNivel=''){
  $strSQL = "SELECT * from nivel";
  if ($codNivel){
    $strSQL .=" where codNivel = ".$codNivel;     
  }
 return mysql_query($strSQL);
}

function getInstancia($nomeFisicoPK,$nomeFisicoCampoNome,$nomeFisicoTabela){
  $strSQL = "SELECT ".$nomeFisicoPK." , ".$nomeFisicoCampoNome." from ".$nomeFisicoTabela;
//echo $strSQL;
 return mysql_query($strSQL);
}

function gravaInstanciaInicial($codPessoa,$userRole,$codNivel,$instancia){
if ($codNivel == 0){$codNivel=1;}
if ($instancia == 0){$instancia=1;}

  $strSQL = "select * from instanciainicial where codPessoa=".$codPessoa;
  $rs = mysql_query($strSQL);

  if(!mysql_fetch_array($rs)){
    $strSQL = "insert into instanciainicial values('".$codPessoa."','".$userRole."','".$codNivel."','".$instancia."')";
    return mysql_query($strSQL);	
  }else{
    $strSQL = "update instanciainicial
             set codNivel = ".$codNivel.",
                 codInstanciaNivel = ".$instancia."
             where codPessoa = ".$codPessoa;
    return mysql_query($strSQL);	
   }
}
*/
function getMenuInicial($codInstanciaGlobal){
  $strSQL = "select * from configuracaogeralinstancia 
             where codInstanciaGlobal= ".quote_smart($codInstanciaGlobal);
 return mysql_query($strSQL);

}

function gravaMenuFixo($codInstanciaGlobal,$request){
  $strSQL = "select * from configuracaogeralinstancia 
             where codInstanciaGlobal= ".quote_smart($codInstanciaGlobal);

  $rs = mysql_query($strSQL);

  if(!mysql_fetch_array($rs)){
      $strSQL = "insert into configuracaogeralinstancia (codInstanciaGlobal,correio,suporteTecnico,indicadores)values('".quote_smart($codInstanciaGlobal)."','".$request['correio']."','".$request['suporteTecnico']."','".$request['indicadores']."')";  
    return mysql_query($strSQL);	
  }else{
     $strSQL = "update configuracaogeralinstancia set";
     $strSQL.= " correio = '".$request['correio']."',";
     $strSQL.= " indicadores = '".$request['indicadores']."',";
     $strSQL.= " suporteTecnico = '".$request['suporteTecnico']."'";
     $strSQL.= " where codInstanciaGlobal = ".quote_smart($codInstanciaGlobal)."";
    //print_r($strSQL); die();
        return mysql_query($strSQL);	
   }
} 

function getInscricaoPainelControle($nivel){
global $urlImagem;

	//if ( Pessoa::podeAdministrar($_SESSION["userRole"],getNivelAtual(),$_SESSION['interage']) ) {  
	if ( $_SESSION['userRole']==ADMINISTRADOR_GERAL || $_SESSION['userRole']==ADM_NIVEL ) {  
 	  if ($nivel->aceitaInscricao) { 
        echo "<td class=\"branco\">".
             "<table>".
             "<tr><td><a href=\"./inscricao.php\">Configurar</a></td></tr>".		
             "<tr><td><a href=\"inscricao.php?acao=A_carregaListaUsuarios\">Carregar lista de usu&aacute;rios</a></td></tr>".
             "<tr><td> Listar <a href=\"inscricao.php?acao=A_inscricoesPendentes\">Pendentes</a></td></tr>". 
             "<tr><td>Listar <a href=\"inscricao.php?acao=A_inscricoesAceitas\">Aceitas</a></td></tr>".
             "</table></td></tr>";
      } 
      else { 
      echo "<td class=\"branco\">".$nivel->nome. " n&atilde;o aceita inscri&ccedil&otilde;es.</td></tr>";}
  }
  else {
    echo "<td class=\"branco\">Você não tem permissão para gerenciar inscrições em ".$nivel->nome. ".</td></tr>";
  }
}

?>
