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


/**
 * Arquivo com diversas funcoes para para inscricao
 */ 

/**
 * Retorna as instancias que possuem inscricoes abertas
 */ 
function getInscricoesAbertas($timestampHoje,$codInstanciaGlobal='') {
  $strSQL  = "Select N.nome,N.codNivel,CI.* from nivel N ". 
             "INNER JOIN instanciaglobal IG ON (N.codNivel=IG.codNivel) ".
             "INNER JOIN configuracaoinscricao CI  ON (IG.codInstanciaGlobal=CI.codInstanciaGlobal) ".        
             "where N.aceitaInscricao=1 and CI.inicio<=".$timestampHoje." and CI.fim>=".$timestampHoje;
  
  //pega a inscriþÒo de uma instancia em particular
  if (!empty($codInstanciaGlobal)) { 
    $strSQL .= " and CI.codInstanciaglobal=".quote_smart($codInstanciaGlobal); 
  }
  
  else{
     $strSQL .= " and CI.inscricaoPublica=1" ; 
     }
  
  //le as instancias com inscricoes abertas
  $inscricoes = new RDCLQuery($strSQL);
  
  //agora le a instancia nivel, a partir da instancia global
  //para obter o nome da instancia  
  foreach($inscricoes->records as $key=>$insc) {
    $instGlob = new InstanciaGlobal($insc->codInstanciaGlobal);
    $nivel = new Nivel($insc->codNivel);
    $instNivel = new InstanciaNivel($nivel,$instGlob->codInstanciaNivel);
    $inscricoes->records[$key]->descInstancia = $instNivel->nome;
  }
  
  return $inscricoes;
}

/**
 * Constroi o formulario de configuracao da inscricao
 */ 
function constroiFormConfigInscricao($codInstanciaGlobal) {
  global $url;
  
  $configInsc = leConfigInscricoes($codInstanciaGlobal);
  
  if (empty($configInsc)) {
  	$inicioDia = $inicioMes = $inicioAno = $fimDia = $fimMes = $fimAno = "";
  	$maxInscritos = $cabecalho = "";  
    $inscricaoPublica = ''; 
    $inscricaoAutomatica = '';
  }
  else {
    list($inicioDia,$inicioMes,$inicioAno) = explode("/", date("d/m/Y", $configInsc->inicio));
    list($fimDia,$fimMes,$fimAno) = explode("/", date("d/m/Y", $configInsc->fim));
    $maxInscritos = $configInsc->maximoInscritos;
    $cabecalho = $configInsc->cabecalho;
    $inscricaoPublica = $configInsc->inscricaoPublica; 
    $inscricaoAutomatica = $configInsc->inscricaoAutomatica; 
  }
   
  $html = '<form action="'.$_SERVER['PHP_SELF'].'?acao=A_gravaConfigInscricao" method="POST">';
  
  //data de inicio das inscricoes
  $html.= '<table>';
  $html.= '<tr><td>In&iacute;cio do Per&iacute;odo de Inscri&ccedil;&otilde;es</td>';
  $html.= '<td>';
  $html.= '<input type="text" size="2" maxlength="2" name="frm_inicio_day" value="'.$inicioDia.'"> / ';
  $html.= '<input type="text" size="2" maxlength="2" name="frm_inicio_month" value="'.$inicioMes.'"> / ';
  $html.= '<input type="text" size="4" maxlength="4" name="frm_inicio_year" value="'.$inicioAno.'">';
  $html.= '</td></tr>';

  //data de fim das inscricoes
  $html.= '<tr><td>Fim do Per&iacute;odo de Inscri&ccedil;&otilde;es</td>';
  $html.= '<td>';
  $html.= '<input type="text" size="2" maxlength="2" name="frm_fim_day" value="'.$fimDia.'"> / ';
  $html.= '<input type="text" size="2" maxlength="2" name="frm_fim_month" value="'.$fimMes.'"> / ';
  $html.= '<input type="text" size="4" maxlength="4" name="frm_fim_year" value="'.$fimAno.'">';
  $html.= '</td></tr>';

  $html.= '<tr><td>N&uacute;mero m&aacute;ximo de inscritos</td>'.
          '<td><input type="text" size="4" name="frm_maxInscritos" value="'.$maxInscritos.'"></td></tr>';
  //==========================================================================================================================================
  // torna a inscricao publica para alunos 
  $html.= '<tr><td>Exibir na p&aacute;gina de inscri&ccedil;&otilde;es abertas ao p&uacute;blico</td>'. 
          '<td align="left"><input type="checkbox" name="frm_inscPub"  value="1" border="0" ';
  if ($inscricaoPublica) { $html.= ' checked'; }          
  $html.= '><br></td></tr>';
  //==========================================================================================================================================
   // torna a inscricao livre para alunos 
  $html.= '<tr><td>Permitir inscri&ccedil;&otilde;es autom&aacute;ticas (sem libera&ccedil;&atilde;o)</td>'. 
          '<td align="left"><input type="checkbox" name="frm_inscLivre"  value="1" border="0" ';
  if ($inscricaoAutomatica) { $html.= 'checked'; }          
  $html.= '><br></td></tr>';
  //=================================================================================================================
 
  $html.= '<tr><td colspan="2"><br>Cabe&ccedil;alho de apresenta&ccedil;&atilde;o das inscri&ccedil;&otilde;es<br>'.
          '<textarea name="frm_cabecalho" rows="10" cols="60">'.$cabecalho.'</textarea></td></tr>';  
                              
  $html.= '<tr><td colspan="2" align="center"><input type="submit" class="okButton" value="Salvar configura&ccedil;&otilde;es">&nbsp;&nbsp;'.
          '<input type="button" value="Cancelar" class="cancelButton" onclick="window.location.href = \'recursos_fixos.php\';"></td><br></tr>';
 
  $html.= '</table>';                     
  $html.= '<div><p>Copie o link abaixo para disponibilizar esta inscri&ccedil;&atilde;o em um  e-mail, not&iacute;cias do NAVi, etc.</p>';
       
  $html.= '<font color="red">'.$url.'/inscricao?codInstanciaGlobal='.$_SESSION['codInstanciaGlobal'].'</font></div>'; 
  
  $html.= '</form>';
  
  return $html;
}

/**
 * Le a configuracao de inscricoes de determinada instancia
 */ 
function leConfigInscricoes($codInstanciaGlobal) {
  $sql = "SELECT codInstanciaGlobal, inicio, fim, maximoInscritos, cabecalho, inscricaoPublica, inscricaoAutomatica, usarBoletoBanrisul ".
         "FROM configuracaoinscricao WHERE codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
  $result = mysql_query($sql);
  if (!mysql_errno() && mysql_num_rows($result) > 0)
    return mysql_fetch_object($result);
  else
    return 0;
}

/**
 * Insere uma configuracao de inscricoes
 */ 
function insereConfigInscricoes($codInstanciaGlobal, $timestampInicio, $timestampFim,
                                $maxInscritos, $cabecalho, $inscricaoPublica,$inscricaoAutomatica) {
  $sql = "INSERT INTO configuracaoinscricao(codInstanciaGlobal,inicio,fim,maximoInscritos, cabecalho, inscricaoPublica,inscricaoAutomatica) ".
         "VALUES (".quote_smart($codInstanciaGlobal).",".quote_smart($timestampInicio).",".
                  quote_smart($timestampFim).",".quote_smart($maxInscritos).",".quote_smart($cabecalho).",".quote_smart($inscricaoPublica).",".quote_smart($inscricaoAutomatica).")";
  mysql_query($sql);
  //echo $sql; echo mysql_error(); die();
  return (mysql_errno() == 0);  
}

/**
 * Atualiza uma configuracao de inscricoes
 */
  
function atualizaConfigInscricoes($codInstanciaGlobal, $timestampInicio, $timestampFim,
                                  $maxInscritos, $cabecalho, $inscricaoPublica, $inscricaoAutomatica) {
  $sql = "UPDATE configuracaoinscricao SET inicio=".quote_smart($timestampInicio).", fim=".quote_smart($timestampFim).",".
         "maximoInscritos=".quote_smart($maxInscritos).", cabecalho=".quote_smart($cabecalho).",".
         "inscricaoPublica=".quote_smart($inscricaoPublica).",".
         "inscricaoAutomatica=".quote_smart($inscricaoAutomatica).
         " WHERE codInstanciaGlobal=".quote_smart($codInstanciaGlobal);
  mysql_query($sql);
  //echo $sql; echo mysql_error(); die();
   
  return (mysql_errno() == 0);  
}

/**
 * Salva a configurcao de inscricoes, chamada insere ou atualiza apropriadamente
 */ 
function salvaConfigInscricoes($codInstanciaGlobal, $timestampInicio, $timestampFim,$maxInscritos, $cabecalho,$inscricaoPublica,$inscricaoAutomatica)
 {
  $configInsc = leConfigInscricoes($codInstanciaGlobal);
  
  if (empty($configInsc)){
    //nao tem config ainda, faz INSERT
      return insereConfigInscricoes($codInstanciaGlobal, $timestampInicio, $timestampFim,
                              $maxInscritos, $cabecalho, $inscricaoPublica, $inscricaoAutomatica);
  }
  else
    //ja tem config, faz UPDATE
    return atualizaConfigInscricoes($codInstanciaGlobal, $timestampInicio, $timestampFim,
                                    $maxInscritos, $cabecalho, $inscricaoPublica,$inscricaoAutomatica);    
  }

/**
 * Retorna as inscricoes pendentes
 * Retorna inscricoes tanto de alunos como professores 
 */ 

function getInscricoesPendentes($codInstanciaGlobal) {
  //seta o segundo parÈmetro para pegar as ainda nao aceitas pelo administrador 
  return getInscricoesAceitas($codInstanciaGlobal,0);  
}

/**
 * Retorna as inscricoes aceitas
 * Retorna inscricoes tanto de alunos como professores
 * 
 * aceita=0 retorna as ainda nao aceitas   
 */ 
 
function getInscricoesAceitas($codInstanciaGlobal,$aceita=1) {
  $sqlAluno = "SELECT p.*,a.COD_AL,".$aceita." as aceita,ia.momentoInscricao,ia.usuarioConfirmou, ".
         "e.COD_ENDERECO, e.COD_TIPO_END, e.DESC_END, e.BAIRRO_END, e.CIDADE_END, e.UF_END, e.PAIS_END, e.CEP_END, e.EMPRESA_END, e.SETOR_END, e.CARGO_END, ".
         "f.COD_FONE, f.COD_TIPO_FONE, f.COD_INTERNAC_FONE, f.COD_AREA_FONE, f.NRO_FONE, f.RAMAL_FONE ,ts.DESC_SEXO ".	
         "FROM ". 
	       " inscricoesaluno as ia ".                 
	       " LEFT OUTER JOIN aluno as a ON (a.COD_AL = ia.COD_AL) ".
         " LEFT OUTER JOIN pessoa as p ON (p.COD_PESSOA = a.COD_PESSOA) ".         
         " LEFT OUTER JOIN endereco as e ON (p.COD_PESSOA = e.COD_PESSOA and e.COD_TIPO_END=1) ".
         " LEFT OUTER JOIN fone as f ON (p.COD_PESSOA = f.COD_PESSOA and f.COD_TIPO_FONE=1) ".
         " LEFT OUTER JOIN tipo_sexo as ts ON (p.COD_SEXO = ts.COD_SEXO) ".

         " WHERE ".
          
         "(ia.codInstanciaGlobal=".quote_smart($codInstanciaGlobal)."  AND ia.aceita = ".$aceita.") ".
         "ORDER BY ia.momentoInscricao";

  $sqlProf = "SELECT p.COD_PESSOA, p.NOME_PESSOA, p.USER_PESSOA, p.ativa, ip.COD_PROF,p.EMAIL_PESSOA,".
         $aceita." as aceita,ip.momentoInscricao,ip.usuarioConfirmou ".	
         "FROM ". 
	       "inscricoesprofessor as ip ".
	       "INNER JOIN professor as prof ON (prof.COD_PROF = ip.COD_PROF) ".
         "INNER JOIN pessoa as p ON (p.COD_PESSOA = prof.COD_PESSOA) ".         
         "WHERE ". 
         "(ip.codInstanciaGlobal=".quote_smart($codInstanciaGlobal)."  AND ip.aceita = ".$aceita.")";

  $alunos= new RDCLQuery($sqlAluno);
  $professores= new RDCLQuery($sqlProf);
  
  $alunos->records = array_merge_recursive($professores->records,$alunos->records);
  
  
  return $alunos;
}


/**
 * Mostra uma lista com as inscricoes pendentes
 */ 
function mostraListaInscricoes($inscricoes,$titulo='') {
  global $url;

  if (!empty($inscricoes)) {
    
    echo '<style type="text/css">'.
         'TD { background-color:white;} '.
         '.titulo { font-size: 12px; font-weight: bold; } '.
         '</style>';
    
    echo '<div align="center">';
    
    echo '<span class="titulo">Lista de inscri&ccedil;&otilde;es '.$titulo.'</span><br>';
    echo count($inscricoes->records).' inscri&ccedil;&otilde;es.<br><br>';

    echo '<table cellspacing="1" bgcolor="black">';
   
    echo '<tr><td>Nome</td>
              <td>Usu&aacute;rio</td>
              <td>Data de Nascimento</td>
              <td>G&ecirc;nero</td>
              <td>Documento de Identifica&ccedil;&atilde;o</td>
              <td>E-mail</td>
              <td>CPF</td>
              <td>Endere&ccedil;o</td>
              <td>Bairro</td>
              <td>Cidade</td>
              <td>UF</td>
              <td>CEP</td>
              <td>C&oacute;digo de &Aacute;rea</td>
              <td>Telefone</td>   
              
              <td>Estado usu&aacute;rio</td>
              <td>Usu&aacute;rio confirmou</td><td>Tipo</td><td>Momento</td>
              <td>A&ccedil;&otilde;es</td></tr>';
     
    foreach($inscricoes->records as $insc) {
      echo '<tr>'.
           '<td>'.$insc->NOME_PESSOA.'&nbsp;</td>'.
           '<td>'.$insc->USER_PESSOA.'&nbsp;</td>'.
           '<td>'.$insc->DATA_NASC_PESSOA.'&nbsp;</td>'.
           '<td>'.$insc->COD_SEXO.'&nbsp;</td>'.
           '<td>'.$insc->DOC_ID_PESSOA.'&nbsp;</td>'.
           '<td>'.$insc->EMAIL_PESSOA.'&nbsp;</td>'.
           '<td>'.$insc->CPF_PESSOA.'&nbsp;</td>'.
           '<td>'.$insc->DESC_END.'&nbsp;</td>'.
           '<td>'.$insc->BAIRRO_END.'&nbsp;</td>'.
           '<td>'.$insc->CIDADE_END.'&nbsp;</td>'.
           '<td>'.$insc->UF_END.'&nbsp;</td>'.
           '<td>'.$insc->CEP_END.'&nbsp;</td>'.
           '<td>'.$insc->COD_AREA_FONE.'&nbsp;</td>'.
           '<td>'.$insc->NRO_FONE.'&nbsp;</td>';
              
      if ($insc->ativa)           
        echo '<td>Ativo</td>';
      else
        echo '<td>Inativo</td>';                                    

      if ($insc->usuarioConfirmou) {
        echo '<td>Sim</td>';
      }
      else {
      	echo '<td>N&atilde;o</td>';
      }

      if (empty($insc->COD_PROF)) {
        //eh aluno
        echo '<td>Aluno</td>';  
        $strUrl = "COD_AL=".$insc->COD_AL;
      }
      else {
        //eh professor
        echo '<td>Professor</td>';  
        $strUrl = "COD_PROF=".$insc->COD_PROF;
      }
      echo '<td>'.date('d/m/Y H:i',$insc->momentoInscricao).'</td>';
      $strUrl.= "&COD_PESSOA=".$insc->COD_PESSOA;
 
      //AŠºes, de acordo com o status do usuario
      $linkRejeitarDeletar = ' &nbsp;|&nbsp; <a href="'.$url.'/tools/inscricao.php?acao=A_rejeitaInscricaoExcluiUsuario&'.$strUrl.'">Rejeitar e excluir usu&aacute;rio</a>';
      $linkRejeitar = '&nbsp;|&nbsp; <a href="'.$url.'/tools/inscricao.php?acao=A_rejeitaInscricao&'.$strUrl.'">Rejeitar</a>';
      
      echo "<td>&nbsp;";
      if ($insc->aceita) {
        //echo '<a href="inscricao.php?acao=A_relacionarTurmaListaTurmas&'.$strUrl.'">Relacionar</a>';
      }
      else {
        //falta aceite da confirmacao por parte do administrador 
        echo '<a href="'.$url.'/tools/inscricao.php?acao=A_aceitaInscricao&'.$strUrl.'">Aceitar</a>';
        echo $linkRejeitar;
      }
      if (!$insc->usuarioConfirmou) { 
      	//falta a confirmacao do usuario, em geral nos casos em que utilizamos uma lista pr+-pronta
      	echo '<a href="'.$url.'/tools/inscricao.php?acao=A_enviar_mail_pedindo_confirmacao&'.$strUrl.'">Enviar email pedindo confirma&ccedil;&atilde;o</a>';
      }
      if (!$insc->ativa) { //se a inscriŠÊo + externa, a pessoa deve estar inativa, entao pode-se delet¯-la  
        echo $linkRejeitarDeletar; 
      }

      echo '</td></tr>';
    }
    
    echo '</table>';
        
    
    echo '</div>';
  }
  else {
  	echo '<div align="center">N&atilde;o existem inscri&ccedil;&otilde;es.</div>';
  }
  
  echo "<div align='center'><br><a href='recursos_fixos.php'>Voltar</a></div>";
}

/**
 * Manda um email avisando que a inscricao foi aceita
 */ 
function mandaMailInscricaoAceita($nivel, $codInstanciaNivel, $pessoa) {
  global $url;
  $real_sender = '-f navi@ufrgs.br';
  $vocativo[0]="o(a)";
  $vocativo[1]="o";
  $vocativo[2]="a";
  
  echo "Enviando email de Inscricao Aceita";
  $instanciaNivel = new InstanciaNivel($nivel,$codInstanciaNivel);

  $texto  = "Prezad".$vocativo[$pessoa->COD_SEXO]." ".$pessoa->NOME_PESSOA.":";
    
  $texto .= "\n\n<br><br>A sua inscri&ccedil;&atilde;o no curso ".$instanciaNivel->nome." foi aceita.\n<br><br>\n\n".
           "<br>\nAcesse <a href='".$url."'>".$url."</a>, informando o usuario ".$pessoa->USER_PESSOA." e a senha informada na solicitacao de inscricao.". 
           "<br>\nLembre-se de atualizar sua foto e cadastrar seu perfil clicando em \"Apresentação - Atualizar Perfil e Atualizar Cadastro\", ao lado da mensagem \"Bem-vindo\"".
           "<br>\nDesejamos-lhe um excelente curso!".
           "\n\n<br><br>Cordialmente,".
           "\n\n<br><br>Equipe NAVi"; 
  
  //Define os header para poder utilizar HTML e o charset padrao brasil 
  $headers = "Content-type: text/html; charset=iso-8859-1;\r\n";
  $headers .= "From: <".ENDERECO_INSCRICOES.">\r\n".
              "Reply-to: <".ENDERECO_INSCRICOES.">";   
  
           
  //$sucesso = mail($pessoa->NOME_PESSOA. "<".$pessoa->EMAIL_PESSOA.">", 
  ini_set("SMTP",SERVIDOR_SMTP);
  $sucesso = mail("<".$pessoa->EMAIL_PESSOA.">",
                  'NAVi-UFRGS: Confirmação de Inscrição', 
                  $texto, $headers, $real_sender);
       
  if (!$sucesso) { echo " falha ao enviar email."; } else { echo " OK."; }     
} 

/**
 * Manda um email avisando que a inscricao foi rejeitada
 */ 
function mandaMailInscricaoRejeitada($nivel, $codInstanciaNivel, $pessoa) {
  $real_sender = '-f navi@ufrgs.br';
  echo "Enviando email de inscricao rejeitada: ";
  $instanciaNivel = new InstanciaNivel($nivel,$codInstanciaNivel);
  
  $texto = "A sua inscri&ccedil;&atilde;o no curso ".$instanciaNivel->nome." nao pode ser aceita.\n".
           "\n\n".
           "Equipe NAVi\n"; 
  //Define os header para poder utilizar HTML e o charset padrao brasil 
  $headers = "Content-type: text/html; charset=iso-8859-1\r\n";

  $headers .= "From: ".ENDERECO_INSCRICOES."\r\n".
             "Reply-to: ".ENDERECO_INSCRICOES."\r\n"; 
                  
//  $sucesso = mail($pessoa->NOME_PESSOA. "<".$pessoa->EMAIL_PESSOA.">", 
  ini_set("SMTP",SERVIDOR_SMTP);
  $sucesso = mail($pessoa->EMAIL_PESSOA,
                  'Inscri&ccedil;&atilde;o no curso '.$instanciaNivel->nome, 
                  $texto, $headers, $real_sender);
       
  if (!$sucesso) { echo "falha ao enviar email."; } else { echo " OK."; }
}

/**
 * Manda um email pedindo para o usuario confirmar a sua inscricao
 */ 
function mandaMailConfirmarInscricao($nivel, $codInstanciaNivel, $codPessoa, $codAl, $nomUser, $senha, $mail) {
  global $url;
  $real_sender = '-f navi@ufrgs.br';
  echo "Enviando email de confirmar inscricao: ";
  $instanciaNivel = new InstanciaNivel($nivel,$codInstanciaNivel);

  //link de confirmacao da inscricao  
  $linkConf = $url."/inscricao/index.php?acao=A_confirmar_inscricao&codInstanciaGlobal=".$instanciaNivel->codInstanciaGlobal."&COD_PESSOA=".$codPessoa."&COD_AL=".$codAl."&USER_PESSOA=".$nomUser."&senha=".md5($senha); 
  $linkConf.= "&hash=".md5(INSCRICOES_USUARIO_CONFIRMAR_HASH.$codPessoa.$nomUser.$instanciaNivel->codInstanciaGlobal);
  
  $texto = "Prezado(a), ";
  
  $texto .=  "\n\n<br><br>Voce foi inscrito no curso ".$instanciaNivel->nome.", da plataforma NAVi.\n<br>".
             "\nPara confirmar a inscrição, por favor clique no link abaixo, ou copie e cole ".
             "na barra de endere&ccedil;o de seu navegador:\n<br>".
             "<a href=\"".$linkConf."\" target='_blank'>".$linkConf."</a>". 
             "\n\n<br><br>".
             "Depois de ter confirmado sua inscrição, acesse a plataforma em <a href='".$url."' target='_blank'>".$url."</a>".
             "\n<br>Usu¯rio:".$nomUser.
             "\n<br>Senha:".$senha.
             "\n\n<br><br>".
             "Após entrar na plataforma, altere a senha clicando em 'Cadastro'.".
              "\n\n<br><br>".             
             "Equipe NAVi\n";
  //Define os header para poder utilizar HTML e o charset padrao brasil 
  $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
  $headers.= "From: ".ENDERECO_INSCRICOES."\r\n".
             "Reply-to: ".ENDERECO_INSCRICOES."\r\n"; 
                  
//  $sucesso = mail($pessoa->NOME_PESSOA. "<".$pessoa->EMAIL_PESSOA.">", 
  ini_set("SMTP",SERVIDOR_SMTP);
  $sucesso = mail($mail,
                  'Confirmacao de inscricao  ', 
                  $texto, $headers, $real_sender);
       
  if (!$sucesso) { echo "falha ao enviar email."; } else { echo " OK."; }
}


function linkBoletoBanrisul($codPessoa,$codInstanciaGlobal) {

  $pessoa=mysql_fetch_object(mysql_query('select NOME_PESSOA from pessoa Where COD_PESSOA='.quote_smart($codPessoa)));     
  $endereco=mysql_fetch_object(mysql_query('select * from endereco Where COD_TIPO_END=1 AND COD_PESSOA='.quote_smart($codPessoa)));     
  
  //le opções da cobranca
  $sql = 'select * from configuracaoboletobanrisul Where codInstanciaGlobal='.quote_smart($codInstanciaGlobal);

  $cobranca=mysql_query($sql);
  echo mysql_error();
  echo '<hr>';
  echo '<h3>Op&ccedil;&otilde;es de pagamento via bloqueto banc&aacute;rio</h3>';
  echo '<h4>Será aberta outra janela do site do banrisul para emiss&atilde;o do bloqueto</h4>';
  while ($c=mysql_fetch_object($cobranca)) {     
    echo '<a href="http://www.banrisul.com.br/bbl/link/ativa.asp?'.
    'CodCedente='.$c->codCedente.'&Valor='.$c->valor.'&SeuNumero='.$codPessoa.'_'.$codInstanciaGlobal.
    '&DiaVcto='.$c->diaVcto.'&MesVcto='.$c->mesVcto.'&AnoVcto='.$c->anoVcto.
    '&NomeSacado='.$pessoa->NOME_PESSOA.'&Endereco='.$endereco->DESC_END.
    '&Cidade='.$endereco->CIDADE_END.'&UF='.$endereco->UF_END.'&CEP='.$endereco->CEP_END.
    '&Observacoes='.$c->observacoes.'" target="_blank">'.$c->descricaoPagamento.'</a><br>';
  }
}


?>
