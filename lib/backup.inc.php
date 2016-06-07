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

  
Class Backup {
  var $sqlBackup;
  
  function Backup() {
  
  }
  
  //funcao que monta as sqls de backup
  function executaBackup($arrayBackup, $tabelasBackup) {
    //note($arrayBackup);
    //note($tabelasBackup);
    
    $i = 0;
    $sqlBackup = '';
    foreach($arrayBackup as $sqls) {

      $j = 0;
      foreach($sqls as $sql) {
        $string = substr($sql,0,6);
        //echo $string; exit;
        if ($string == "delete" || $string == "DELETE") { //se a sql for delete, nao executa e insere direto no arquivo 
          $sqlBackup.= $sql." \n";
        }
        else {
          $result = mysql_query($sql);
          if (mysql_error()) {
            $sqlBackup = 'error';
          }
          $tabela = $tabelasBackup[$i][$j];
           while($obj = mysql_fetch_object($result)) {
            $campos = "";
            $valores = "";
          
            foreach($obj as $field=>$value) {
              $campos.= $field.",";
              $valores.= quote_smart($value).",";
            }
            
            //tira a virgula q ta sobrando
            $campos = trim($campos,",");
            $valores = trim($valores,",");
            $sqlBackup.= "INSERT INTO ".$tabela." (".$campos.") VALUES (".$valores."); \n";
            //echo $sqlBackup.'<br><br>';
          }
          $j++;
        }
      }
      $i++;
    } 
    
    //note($sqlBackup);
    
    return $sqlBackup;
  }   

  
  // monta as sqls para cada ferramenta
  function doBackup($codInstanciaGlobal='',$arr=''){

    $sqlBackup = array();
    $tabelas = array();    

//------------------------------------------------------------------------------------------------------------------------
//LEMBRETES
    
    $sqlBackup['Lembretes'][] = "delete N.*, NI.* FROM noticia as N, noticia_instancia AS NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Lembretes'][] = "SELECT N.* FROM noticia as N, noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Lembretes'][] = "SELECT NI.* FROM noticia as N, noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Lembretes'][] = "noticia";
    $tabelas['Lembretes'][] = "noticia_instancia";
      
//------------------------------------------------------------------------------------------------------------------------
//VÍDEO-AULAS
    
    $sqlBackup['Vídeo-aulas'][] = "delete v.* from video as v, video_instancia as vi where v.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Vídeo-aulas'][] = "delete vaa.* from video_aula_agenda as vaa, video_instancia as vi where vaa.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Vídeo-aulas'][] = "delete vi.* from video_instancia as vi where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Vídeo-aulas'][] = "select * from video_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Vídeo-aulas'][] = "select v.* from video as v, video_instancia as vi where v.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Vídeo-aulas'][] = "select vaa.* from video_aula_agenda as vaa, video_instancia as vi where vaa.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Vídeo-aulas'][] = "video_instancia";
    $tabelas['Vídeo-aulas'][] = "video";
    $tabelas['Vídeo-aulas'][] = "video_aula_agenda";
 
//------------------------------------------------------------------------------------------------------------------------
//CONTEUDOS
    $sqlBackup['Conteúdos'][] = "DELETE aai.* FROM arquivo_aluno_instancia AS AAI, arquivo AS A, arquivo_instancia AS AI WHERE AAI.COD_ARQUIVO = A.COD_ARQUIVO AND A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Conteúdos'][] = "DELETE A.*, AI.* FROM arquivo AS A, arquivo_instancia AS AI WHERE A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Conteúdos'][] = "select A.* from arquivo AS A, arquivo_instancia AS AI WHERE A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";  
    $sqlBackup['Conteúdos'][] = "select AAI.* from arquivo_aluno_instancia AS AAI, arquivo_instancia AS AI WHERE AAI.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Conteúdos'][] = "select * from arquivo_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."'";
    $tabelas['Conteúdos'][] = "arquivo";
    $tabelas['Conteúdos'][] = "arquivo_aluno_instancia";
    $tabelas['Conteúdos'][] = "arquivo_instancia";

//------------------------------------------------------------------------------------------------------------------------
//FORUM
    $sqlBackup['Fórum'][] = "delete from forum_mensagem as fm using forum_mensagem as fm,forum_sala as fs where fm.COD_SALA = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Fórum'][] = "delete from forum_msg_novas as fmn using forum_msg_novas as fmn,forum_sala as fs where fmn.codSala = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Fórum'][] = "delete from forum_sala where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Fórum'][] = "insert into ".PREFIXO_ARQ.".forum_mensagem (select fm.* from forum_mensagem as fm, forum_sala as fs where fm.COD_SALA = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlBackup['Fórum'][] = "insert into ".PREFIXO_ARQ.".forum_msg_novas (select fmn.* from forum_msg_novas as fmn, forum_sala as fs where fmn.codSala = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlBackup['Fórum'][] = "insert into ".PREFIXO_ARQ.".forum_sala (select * from forum_sala where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $tabelas['Fórum'][] = "forum_mensagem";
    $tabelas['Fórum'][] = "forum_msg_novas";
    $tabelas['Fórum'][] = "forum_sala";
//------------------------------------------------------------------------------------------------------------------------
//ACERVO
    
    $sqlBackup['Acervo'][] = "DELETE b.*, a.* FROM biblioteca AS b, arquivo AS a WHERE b.COD_ARQUIVO = a.COD_ARQUIVO AND b.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Acervo'][] = "select * from biblioteca WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Acervo'][] = "select a.* from arquivo as a, biblioteca as b WHERE a.COD_ARQUIVO = b.COD_ARQUIVO and b.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Acervo'][] = "biblioteca";
    $tabelas['Acervo'][] = "arquivo";
//------------------------------------------------------------------------------------------------------------------------
//ENQUETE
    $sqlBackup['Enquete'][] = "DELETE eav.* FROM enquete_aluno_votou as eav, enquete as e, enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_ENQUETE_INSTANCIA = eav.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Enquete'][] = "DELETE er.* FROM enquete_resposta as er, enquete as e, enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_ENQUETE = er.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Enquete'][] = "DELETE e.*, ei.* FROM enquete as e, enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Enquete'][] = "select e.* from enquete as e, enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Enquete'][] = "select eav.* from enquete_aluno_votou as eav, enquete_instancia as ei WHERE eav.COD_ENQUETE_INSTANCIA = ei.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Enquete'][] = "select * from enquete_instancia WHERE COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Enquete'][] = "select er.* from enquete_instancia as ei, enquete_resposta as er WHERE er.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Enquete'][] = "enquete";
    $tabelas['Enquete'][] = "enquete_aluno_votou";
    $tabelas['Enquete'][] = "enquete_instancia";
    $tabelas['Enquete'][] = "enquete_resposta";
    
//------------------------------------------------------------------------------------------------------------------------
//AULA INTERATIVA
    $sqlBackup['Aula Interativa'][] = "delete * from chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Aula Interativa'][] = "delete * from chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Aula Interativa'][] = "select * from chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Aula Interativa'][] = "select * from chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Aula Interativa'][] = "chatconf";
    $tabelas['Aula Interativa'][] = "chat_camera";
//------------------------------------------------------------------------------------------------------------------------
//EXERCICIO ON LINE
    $sqlBackup['Exercício on line'][] = "DELETE e.* FROM exercicio as e, exercicioinstancia as ei WHERE e.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Exercício on line'][] = "DELETE eq.* FROM exercicioquestao eq, exercicioinstancia as ei WHERE eq.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Exercício on line'][] = "DELETE ei.* FROM exercicioinstancia as ei WHERE codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Exercício on line'][] = "DELETE ei.* FROM exercicio_instancia as ei WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Exercício on line'][] = "select * from exercicio_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Exercício on line'][] = "select e.* from exercicio as e,  exercicioinstancia as ei WHERE e.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Exercício on line'][] = "select eq.* FROM exercicioquestao as eq, exercicioinstancia as ei WHERE eq.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Exercício on line'][] = "select * from exercicioinstancia WHERE codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $tabelas['Exercício on line'][] = "exercicio_instancia";
    $tabelas['Exercício on line'][] = "exercicio";
    $tabelas['Exercício on line'][] = "exercicioquestao";
    $tabelas['Exercício on line'][] = "exercicioinstancia";
   
//------------------------------------------------------------------------------------------------------------------------
//AVALIACAO
    $sqlBackup['Avaliação'][] = "delete from avaliacao as a using avaliacao as a, avaliacao_instancia as ai where a.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "delete from avaliacao_aluno as aa using avaliacao_aluno as aa, avaliacao_instancia as ai
where aa.COD_AVALIACAO_TURMA = ai.COD_AVALIACAO_TURMA AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "delete from avaliacao_instancia_2 where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "delete from avaliacao_pergunta as ap, avaliacao_resposta as ar using avaliacao_pergunta as ap, avaliacao_resposta as ar, avaliacao_instancia as ai where ar.COD_PERGUNTA = ap.COD_PERGUNTA AND ap.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "delete from pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "delete from pesquisaavaliacaopreenchida where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "delete from avaliacao_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Avaliação'][] = "select a.* from avaliacao as a,  avaliacao_instancia as ai where a.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "select aa.* from avaliacao_aluno as aa,  avaliacao_instancia as ai where aa.COD_AVALIACAO_TURMA = ai.COD_AVALIACAO_TURMA AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "select * from avaliacao_instancia_2 where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "select ap.* from avaliacao_pergunta as ap, avaliacao_resposta as ar, avaliacao_instancia as ai where ar.COD_PERGUNTA = ap.COD_PERGUNTA AND ap.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "select * from pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "select * from pesquisaavaliacaopreenchida where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlBackup['Avaliação'][] = "select * from avaliacao_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $tabelas['Avaliação'][] = "avaliacao";
    $tabelas['Avaliação'][] = "avaliacao_aluno";
    $tabelas['Avaliação'][] = "avaliacao_instancia_2";
    $tabelas['Avaliação'][] = "avaliacao_pergunta";
    $tabelas['Avaliação'][] = "pesquisaavaliacaoinstanciapeloaluno";
    $tabelas['Avaliação'][] = "pesquisaavaliacaopreenchida";
    $tabelas['Avaliação'][] = "avaliacao_instancia"; 
     
//------------------------------------------------------------------------------------------------------------------------
//NOTICIAS
    
    $sqlBackup['Notícias'][] = "delete N.*, NI.* FROM noticia as N, noticia_instancia AS NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA != '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Notícias'][]="select n.* from noticia AS N, noticia_instancia AS NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA != '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Notícias'][]="select * from noticia_instancia WHERE NRO_COLUNA_NOTICIA != 1 AND COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Notícias'][] = "noticia";
    $tabelas['Notícias'][] = "noticia_instancia";

//------------------------------------------------------------------------------------------------------------------------
//FORUM TECNICO
    $sqlBackup['Fórum Técnico'][] ="DELETE FROM forum_mensagem_suportetecnico as fms, forum_sala_suportetecnico as fss 
USING forum_mensagem_suportetecnico as fms, forum_sala_suportetecnico as fss WHERE fms.COD_SALA = fss.COD_SALA AND fss.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Fórum Técnico'][] ="select fms.* from forum_mensagem_suportetecnico, forum_sala_suportetecnico as fss WHERE fms.COD_SALA = fss.COD_SALA AND fss.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Fórum Técnico'][] ="select * from forum_sala_suportetecnico WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Fórum Técnico'][] = "forum_mensagem_suportetecnico";
    $tabelas['Fórum Técnico'][] = "forum_sala_suportetecnico";
    
//------------------------------------------------------------------------------------------------------------------------
//chatcafe_mensagem ??
    $sqlBackup['ChatCafé Virtual'][] ="DELETE FROM chatcafe_acesso as cca,chatcafe_acesso_ as cca_, forum_mensagem_cafevirtual as fmc, chatcafe_mensagem as cm, forum_sala_cafevirtual as fsc USING chatcafe_acesso as cca,
chatcafe_acesso_ as cca_, forum_mensagem_cafevirtual as fmc, chatcafe_mensagem as cm, forum_sala_cafevirtual as fsc
WHERE cca_.COD_SALA = cca.COD_SALA AND cca.COD_SALA = fmc.COD_SALA AND fmc.COD_MENSAGEM = cm.codMensagem AND fmc.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlBackup['ChatCafé Virtual'][] ="select cca.* from chatcafe_acesso_ as cca, forum_sala_cafevirtual as fsc WHERE cca.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['ChatCafé Virtual'][] ="select fmc.* from forum_mensagem_cafevirtual as fmc, forum_sala_cafevirtual as fsc WHERE fmc.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['ChatCafé Virtual'][] ="select cm.* from chatcafe_mensagem as cm, forum_mensagem_cafevirtual as fmc, forum_sala_cafevirtual as fsc WHERE fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."' and fsc.COD_SALA = fmc.COD_SALA and fmc.COD_MENSAGEM = cm.codMensagem;";
    $sqlBackup['ChatCafé Virtual'][] ="select * from forum_sala_cafevirtual WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $tabelas['ChatCafé Virtual'][] = "chatcafe_acesso";
    $tabelas['ChatCafé Virtual'][] = "forum_mensagem_cafevirtual";
    $tabelas['ChatCafé Virtual'][] = "chatcafe_mensagem";
    $tabelas['ChatCafé Virtual'][] = "forum_sala_cafevirtual";
//------------------------------------------------------------------------------------------------------------------------
//AGENDA
    
    $sqlBackup['Agenda'][] ="DELETE FROM aula_agenda as aa, arquivo_aula_agenda as aaa, video_aula_agenda as vaa USING
aula_agenda as aa, arquivo_aula_agenda as aaa, video_aula_agenda as vaa WHERE aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = aaa.codAula AND aaa.codAula = vaa.codAula;";
    $sqlBackup['Agenda'][] ="select * from aula_agenda where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Agenda'][] ="select aaa.* from arquivo_aula_agenda as aaa, aula_agenda as aa where aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = aaa.codAula;";  
    $sqlBackup['Agenda'][] ="select vaa.* from video_aula_agenda as vaa, aula_agenda as aa where aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = vaa.codAula;";  
    $tabelas['Agenda'][] = "aula_agenda";
    $tabelas['Agenda'][] = "arquivo_aula_agenda";
    $tabelas['Agenda'][]  = "video_aula_agenda";
//------------------------------------------------------------------------------------------------------------------------
//SCORM    
    
    $sqlBackup['Scorm'][] ="DELETE FROM scorm as s, scormobject as so, scormobjectdata as sod, scormobjecttrack as sot USING scorm as s, scormobject as so, scormobjectdata as sod, scormobjecttrack as sot WHERE sot.codScorm = sod.codScorm AND sod.codScorm = so.codScorm AND so.codScorm = s.codScorm AND s.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Scorm'][] ="select * from scorm where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Scorm'][] ="select SO.* from scormobject SO, scorm S where SO.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Scorm'][] ="select SOD.* from scormobjectdata SOD, scorm S where SOD.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    $sqlBackup['Scorm'][] ="select SOT.* from scormobjecttrack SOT, scorm S where SOT.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    $tabelas['Scorm'][] = "scorm";
    $tabelas['Scorm'][] = "scormobject";
    $tabelas['Scorm'][] = "scormobjectdata";
    $tabelas['Scorm'][] = "scormobjecttrack";
    
//------------------------------------------------------------------------------------------------------------------------

    //varre os check-boxes
    for ($i=0; $i<count($arr);$i++){
      //verifica se existe chave no array de queries
      if (array_key_exists($arr[$i], $sqlBackup)) {
        
        $queryArray = $sqlBackup[$arr[$i]];
        $sqlescolhidas[]= $queryArray;
        $tabelasEscolhidas[] = $tabelas[$arr[$i]];        
      }
    }
        
   // note($sqlescolhidas);
    //note($tabelasEscolhidas);
    //exit;    
        
    $return = array();
    $return[] = $sqlescolhidas;
    $return[] = $tabelasEscolhidas;
    return $return;
  }

  function restore($caminhoArquivo) {
    $linhas = array();
    $linhas = file($caminhoArquivo);  
    foreach ($linhas as $linha) {
      $sql = '';
      if (!$_REQUEST['substituir']) { //nao deve executar os deletes  
        $string = substr($linha,0,6);
        if ($string != "delete" && $string != "DELETE") { //se a sql for delete, nao executa 
          $sql = $linha;
        }
      }
      else {
        $sql = $linha;
      }
      
      if (!empty($sql)) {
        $result = mysql_query($sql);
        if (mysql_errno()) {
          echo "Houve um erro [$sql]".mysql_error();;
        }
      }
    }
    //echo $sql;
    //exit;
  }
} 


?>
