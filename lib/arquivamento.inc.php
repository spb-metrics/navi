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

define("PREFIXO_ARQ","arq");

Class ArquivaRecurso {
  var $sqlContagem;
  var $sqlArquiva;
   
  function executaArraySql($arrArquiva) {
    foreach($arrArquiva as $item) {
      if (is_array($item)) {
        foreach($item as $i){ 
          mysql_query($i); 
          if (mysql_errno()) {
        	  echo 'Erro na consulta '.$i.': '.mysql_error()."<br>";
          }       
        }
      }
      else {
        mysql_query($item);
         if (mysql_errno()) {
        	 echo 'Erro na consulta '.$item.': '.mysql_error()."<br>";
         }       
      }
    }    
        
    return mysql_errno(); 
  }

  function arquiva($codInstanciaGlobal='',$arr=''){

    $sqlArquiva = array();
//------------------------------------------------------------------------------------------------------------------------
//LEMBRETES
    $sqlArquiva['Lembretes'][] = "DELETE from ".PREFIXO_ARQ.".noticia as N, ".PREFIXO_ARQ.".noticia_instancia as NI using ".PREFIXO_ARQ.".noticia as N, ".PREFIXO_ARQ.".noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA  AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";  

    $sqlArquiva['Lembretes'][] = "INSERT INTO ".PREFIXO_ARQ.".noticia (SELECT N.* FROM noticia as N, noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    $sqlArquiva['Lembretes'][] = "INSERT INTO ".PREFIXO_ARQ.".noticia_instancia (SELECT NI.* FROM noticia as N, noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    $sqlArquiva['Lembretes'][] = "DELETE from noticia as N, noticia_instancia as NI using noticia as N, noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA  AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";  
//------------------------------------------------------------------------------------------------------------------------
//VÍDEO-AULAS
    $sqlArquiva['Vídeo-aulas'][] = "delete from ".PREFIXO_ARQ.".video as v using ".PREFIXO_ARQ.".video as v, ".PREFIXO_ARQ.".video_instancia as vi where v.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Vídeo-aulas'][] = "delete from ".PREFIXO_ARQ.".video_aula_agenda as vaa using ".PREFIXO_ARQ.".video_aula_agenda as vaa, ".PREFIXO_ARQ.".video_instancia as vi where vaa.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Vídeo-aulas'][] = "delete from ".PREFIXO_ARQ.".video_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Vídeo-aulas'][] = "insert into ".PREFIXO_ARQ.".video_instancia (select * from video_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";
    $sqlArquiva['Vídeo-aulas'][] = "insert into ".PREFIXO_ARQ.".video (select v.* from video as v, video_instancia as vi where v.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";
    $sqlArquiva['Vídeo-aulas'][] = "insert into ".PREFIXO_ARQ.".video_aula_agenda (select vaa.* from video_aula_agenda as vaa, video_instancia as vi where vaa.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    $sqlArquiva['Vídeo-aulas'][] = "delete from video as v using video as v,video_instancia as vi where v.COD_VIDEO = vI.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Vídeo-aulas'][] = "delete from video_aula_agenda as vaa using video_aula_agenda as vaa,video_instancia as vi where vaa.COD_VIDEO = vI.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Vídeo-aulas'][] = "delete from video_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//CONTEUDOS
    $sqlArquiva['Conteúdos'][] = "DELETE FROM ".PREFIXO_ARQ.".arquivo A, ".PREFIXO_ARQ.".arquivo_instancia AI, ".PREFIXO_ARQ.".arquivo_aluno_instancia AAI USING ".PREFIXO_ARQ.".arquivo A, ".PREFIXO_ARQ.".arquivo_instancia AI, ".PREFIXO_ARQ.".arquivo_aluno_instancia AAI WHERE AAI.COD_ARQUIVO = A.COD_ARQUIVO AND A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."'";  

    $sqlArquiva['Conteúdos'][] = "insert into ".PREFIXO_ARQ.".arquivo (select A.* from arquivo A, arquivo_instancia AI WHERE A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";  
    $sqlArquiva['Conteúdos'][] = "insert into ".PREFIXO_ARQ.".arquivo_aluno_instancia (select AAI.* from arquivo_aluno_instancia AAI, arquivo_instancia AI WHERE AAI.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";
    $sqlArquiva['Conteúdos'][] = "insert into ".PREFIXO_ARQ.".arquivo_instancia (select * from arquivo_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    //$sqlArquiva['Conteúdos'][] = "DELETE FROM arquivo A, arquivo_instancia AI,arquivo_aluno_instancia AAI 
    //USING arquivo A, arquivo_instancia AI,arquivo_aluno_instancia AAI WHERE AAI.COD_ARQUIVO = A.COD_ARQUIVO AND A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."'";  
    $sqlArquiva['Conteúdos'][] = "DELETE FROM arquivo_aluno_instancia AAI 
                                  USING arquivo A, arquivo_instancia AI,arquivo_aluno_instancia AAI WHERE AAI.COD_ARQUIVO = A.COD_ARQUIVO AND A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."'";
    $sqlArquiva['Conteúdos'][] = "DELETE FROM arquivo A, arquivo_instancia AI 
                                  USING arquivo A, arquivo_instancia AI WHERE A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."'";

//------------------------------------------------------------------------------------------------------------------------
//FORUM
    $sqlArquiva['Fórum'][] = "delete from ".PREFIXO_ARQ.".forum_mensagem as fm using ".PREFIXO_ARQ.".forum_mensagem as fm, ".PREFIXO_ARQ.".forum_sala as fs where fm.COD_SALA = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Fórum'][] = "delete from ".PREFIXO_ARQ.".forum_msg_novas as fmn using ".PREFIXO_ARQ.".forum_msg_novas as fmn, ".PREFIXO_ARQ.".forum_sala as fs where fmn.codSala = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Fórum'][] = "delete from ".PREFIXO_ARQ.".forum_sala as fs where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Fórum'][] = "insert into ".PREFIXO_ARQ.".forum_mensagem (select fm.* from forum_mensagem as fm, forum_sala as fs where fm.COD_SALA = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Fórum'][] = "insert into ".PREFIXO_ARQ.".forum_msg_novas (select fmn.* from forum_msg_novas as fmn, forum_sala as fs where fmn.codSala = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Fórum'][] = "insert into ".PREFIXO_ARQ.".forum_sala (select * from forum_sala where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlArquiva['Fórum'][] = "delete from forum_mensagem as fm using forum_mensagem as fm,forum_sala as fs where fm.COD_SALA = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Fórum'][] = "delete from forum_msg_novas as fmn using forum_msg_novas as fmn,forum_sala as fs where fmn.codSala = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Fórum'][] = "delete from forum_sala where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//ACERVO
    $sqlArquiva['Acervo'][] = "DELETE FROM ".PREFIXO_ARQ.".biblioteca AS b, ".PREFIXO_ARQ.".arquivo AS a USING ".PREFIXO_ARQ.".biblioteca AS b, ".PREFIXO_ARQ.".arquivo AS a WHERE b.COD_ARQUIVO = a.COD_ARQUIVO AND b.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Acervo'][] = "insert into ".PREFIXO_ARQ.".biblioteca (select * from biblioteca WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Acervo'][] = "insert into ".PREFIXO_ARQ.".arquivo (select a.* from arquivo as a, biblioteca as b WHERE a.COD_ARQUIVO = b.COD_ARQUIVO and b.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Acervo'][] = "DELETE FROM biblioteca AS b, arquivo AS a USING biblioteca AS b, arquivo AS a WHERE b.COD_ARQUIVO = a.COD_ARQUIVO AND b.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//ENQUETE
    $sqlArquiva['Enquete'][] = "DELETE FROM ".PREFIXO_ARQ.".enquete as e, ".PREFIXO_ARQ.".enquete_aluno_votou as eav, ".PREFIXO_ARQ.".enquete_instancia as ei, ".PREFIXO_ARQ.".enquete_resposta as er USING ".PREFIXO_ARQ.".enquete as e, ".PREFIXO_ARQ.".enquete_aluno_votou as eav, ".PREFIXO_ARQ.".enquete_instancia as ei, 
".PREFIXO_ARQ.".enquete_resposta as er WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_ENQUETE = er.COD_ENQUETE AND ei.COD_ENQUETE_INSTANCIA = eav.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Enquete'][] = "insert into ".PREFIXO_ARQ.".enquete (select e.* from enquete as e, enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Enquete'][] = "insert into ".PREFIXO_ARQ.".enquete_aluno_votou (select eav.* from enquete_aluno_votou as eav, enquete_instancia as ei WHERE eav.COD_ENQUETE_INSTANCIA = ei.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Enquete'][] = "insert into ".PREFIXO_ARQ.".enquete_instancia (select * from enquete_instancia WHERE COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Enquete'][] = "insert into ".PREFIXO_ARQ.".enquete_resposta (select er.* from enquete_instancia as ei, enquete_resposta as er WHERE er.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";

    //$sqlArquiva['Enquete'][] = "DELETE FROM enquete as e, enquete_aluno_votou as eav, enquete_instancia as ei, enquete_resposta as er USING enquete as e, enquete_aluno_votou as eav, enquete_instancia as ei, 
//enquete_resposta as er WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_ENQUETE = er.COD_ENQUETE AND ei.COD_ENQUETE_INSTANCIA = eav.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Enquete'][] = "DELETE FROM enquete_aluno_votou as eav USING enquete as e, enquete_aluno_votou as eav, enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_ENQUETE_INSTANCIA = eav.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Enquete'][] = "DELETE FROM enquete_resposta as er USING enquete as e, enquete_instancia as ei, enquete_resposta as er WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_ENQUETE = er.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Enquete'][] = "DELETE FROM enquete as e, enquete_instancia as ei USING enquete as e, enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";

    
//------------------------------------------------------------------------------------------------------------------------
//AULA INTERATIVA
    $sqlArquiva['Aula Interativa'][] = "delete from ".PREFIXO_ARQ.".chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Aula Interativa'][] = "delete from ".PREFIXO_ARQ.".chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Aula Interativa'][] = "insert into ".PREFIXO_ARQ.".chatconf (select * from chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Aula Interativa'][] = "insert into ".PREFIXO_ARQ.".chat_camera (select * from chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";

    $sqlArquiva['Aula Interativa'][] = "delete from chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Aula Interativa'][] = "delete from chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
//chat_acesso  ??  COD_SALA
//chat_mensagem  ??  COD_SALA  
//------------------------------------------------------------------------------------------------------------------------
//EXERCICIO ON LINE
    $sqlArquiva['Exercício on line'][] = "DELETE FROM ".PREFIXO_ARQ.".exercicio_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Exercício on line'][] = "DELETE ".PREFIXO_ARQ.".exercicio.* FROM ".PREFIXO_ARQ.".exercicio, ".PREFIXO_ARQ.".exercicioinstancia WHERE exercicio.codExercicio = exercicioinstancia.codExercicio AND exercicioinstancia.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Exercício on line'][] = "DELETE ".PREFIXO_ARQ.".exercicioquestao.* FROM ".PREFIXO_ARQ.".exercicioquestao, ".PREFIXO_ARQ.".exercicioinstancia WHERE ".PREFIXO_ARQ.".exercicioquestao.codExercicio = ".PREFIXO_ARQ.".exercicioinstancia.codExercicio AND ".PREFIXO_ARQ.".exercicioinstancia.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Exercício on line'][] = "DELETE FROM ".PREFIXO_ARQ.".exercicioinstancia WHERE codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 

    $sqlArquiva['Exercício on line'][] = "insert into ".PREFIXO_ARQ.".exercicio_instancia (select * from exercicio_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Exercício on line'][] = "insert into ".PREFIXO_ARQ.".exercicio (select e.* from exercicio as e,  exercicioinstancia as ei WHERE e.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Exercício on line'][] = "insert into ".PREFIXO_ARQ.".exercicioquestao (select eq.* FROM exercicioquestao as eq, exercicioinstancia as ei WHERE eq.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Exercício on line'][] = "insert into ".PREFIXO_ARQ.".exercicioinstancia (select * from exercicioinstancia WHERE codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 

    $sqlArquiva['Exercício on line'][] = "DELETE ei.* FROM exercicio_instancia as ei WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Exercício on line'][] = "DELETE e.* FROM exercicio as e, exercicioinstancia as ei WHERE e.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Exercício on line'][] = "DELETE eq.* FROM exercicioquestao as eq, exercicioinstancia as ei WHERE eq.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Exercício on line'][] = "DELETE ei.* FROM exercicioinstancia as ei WHERE codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
//------------------------------------------------------------------------------------------------------------------------
//AVALIACAO
    $sqlArquiva['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao as a using ".PREFIXO_ARQ.".avaliacao as a, ".PREFIXO_ARQ.".avaliacao_instancia as ai where a.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_aluno as aa using ".PREFIXO_ARQ.".avaliacao_aluno as aa, ".PREFIXO_ARQ.".avaliacao_instancia as ai where aa.COD_AVALIACAO_TURMA = ai.COD_AVALIACAO_TURMA AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete ".PREFIXO_ARQ.".arquivo.* from ".PREFIXO_ARQ.".arquivo, ".PREFIXO_ARQ.".avaliacao_instancia_2 where ".PREFIXO_ARQ.".avaliacao_instancia_2.cod_arquivo = ".PREFIXO_ARQ.".arquivo.cod_arquivo AND ".PREFIXO_ARQ.".avaliacao_instancia_2.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_instancia_2 where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_pergunta as ap, ".PREFIXO_ARQ.".avaliacao_resposta as ar using ".PREFIXO_ARQ.".avaliacao_pergunta as ap, ".PREFIXO_ARQ.".avaliacao_resposta as ar, ".PREFIXO_ARQ.".avaliacao_instancia as ai where ar.COD_PERGUNTA = ap.COD_PERGUNTA AND ap.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from ".PREFIXO_ARQ.".pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from ".PREFIXO_ARQ.".pesquisaavaliacaopreenchida where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 

    $sqlArquiva['Avaliação'][] = "insert into ".PREFIXO_ARQ.".avaliacao (select a.* from avaliacao as a,  avaliacao_instancia as ai where a.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Avaliação'][] = "insert into ".PREFIXO_ARQ.".avaliacao_aluno (select aa.* from avaliacao_aluno as aa,  avaliacao_instancia as ai where aa.COD_AVALIACAO_TURMA = ai.COD_AVALIACAO_TURMA AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Avaliação'][] = "insert into ".PREFIXO_ARQ.".avaliacao_instancia_2 (select * from avaliacao_instancia_2 where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Avaliação'][] = "insert into ".PREFIXO_ARQ.".avaliacao_pergunta (select ap.* from avaliacao_pergunta as ap, avaliacao_resposta as ar, avaliacao_instancia as ai where ar.COD_PERGUNTA = ap.COD_PERGUNTA AND ap.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Avaliação'][] = "insert into ".PREFIXO_ARQ.".pesquisaavaliacaoinstanciapeloaluno (select * from pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Avaliação'][] = "insert into ".PREFIXO_ARQ.".pesquisaavaliacaopreenchida (select * from pesquisaavaliacaopreenchida where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlArquiva['Avaliação'][] = "insert into ".PREFIXO_ARQ.".avaliacao_instancia (select * from avaliacao_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 

    $sqlArquiva['Avaliação'][] = "delete from avaliacao as a using avaliacao as a, avaliacao_instancia as ai where a.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from avaliacao_aluno as aa using avaliacao_aluno as aa, avaliacao_instancia as ai
where aa.COD_AVALIACAO_TURMA = ai.COD_AVALIACAO_TURMA AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete arquivo.* from arquivo, avaliacao_instancia_2 where avaliacao_instancia_2.cod_arquivo = arquivo.cod_arquivo AND avaliacao_instancia_2.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Avaliação'][] = "delete from avaliacao_instancia_2 where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from avaliacao_pergunta as ap, avaliacao_resposta as ar using avaliacao_pergunta as ap, avaliacao_resposta as ar, avaliacao_instancia as ai where ar.COD_PERGUNTA = ap.COD_PERGUNTA AND ap.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from pesquisaavaliacaopreenchida where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlArquiva['Avaliação'][] = "delete from avaliacao_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
//------------------------------------------------------------------------------------------------------------------------
//NOTICIAS
    $sqlArquiva['Notícias'][] ="DELETE FROM  ".PREFIXO_ARQ.".noticia AS N, ".PREFIXO_ARQ.".noticia_instancia AS NI USING ".PREFIXO_ARQ.".noticia AS N, ".PREFIXO_ARQ.".noticia_instancia AS NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA != 1 AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Notícias'][]="insert into ".PREFIXO_ARQ.".noticia (select n.* from noticia AS N, noticia_instancia AS NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA != '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Notícias'][]="insert into ".PREFIXO_ARQ.".noticia_instancia (select * from noticia_instancia WHERE NRO_COLUNA_NOTICIA != 1 AND COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    //$sqlArquiva['Notícias'][] ="DELETE FROM noticia_principal AS NP, noticia_curso AS NC, noticia AS N, noticia_instancia AS NI USING noticia_principal AS NP, noticia_curso AS NC, noticia AS N, noticia_instancia AS NI WHERE NP.COD_NOTICIA = NC.COD_NOTICIA AND NC.COD_NOTICIA = N.COD_NOTICIA AND N.COD_NOTICIA = NI.COD_NOTICIA AND NP.NRO_COLUNA_NOTICIA != 1 AND NC.NRO_COLUNA_NOTICIA != 1 AND NI.NRO_COLUNA_NOTICIA != 1 AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlArquiva['Notícias'][] = "DELETE FROM noticia as n, noticia_instancia as ni USING noticia as n, noticia_instancia as ni WHERE n.COD_NOTICIA = ni.COD_NOTICIA AND ni.NRO_COLUNA_NOTICIA != 1 AND ni.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//FORUM TECNICO
    $sqlArquiva['Fórum Técnico'][] ="DELETE FROM ".PREFIXO_ARQ.".forum_mensagem_suportetecnico as fms, ".PREFIXO_ARQ.".forum_sala_suportetecnico as fss USING ".PREFIXO_ARQ.".forum_mensagem_suportetecnico as fms, ".PREFIXO_ARQ.".forum_sala_suportetecnico as fss WHERE fms.COD_SALA = fss.COD_SALA AND fss.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Fórum Técnico'][] ="insert into ".PREFIXO_ARQ.".forum_mensagem_suportetecnico (select fms.* from forum_mensagem_suportetecnico, forum_sala_suportetecnico as fss WHERE fms.COD_SALA = fss.COD_SALA AND fss.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Fórum Técnico'][] ="insert into ".PREFIXO_ARQ.".forum_sala_suportetecnico (select * from forum_sala_suportetecnico WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlArquiva['Fórum Técnico'][] ="DELETE FROM forum_mensagem_suportetecnico as fms, forum_sala_suportetecnico as fss 
USING forum_mensagem_suportetecnico as fms, forum_sala_suportetecnico as fss WHERE fms.COD_SALA = fss.COD_SALA AND fss.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//chatcafe_mensagem ??
    $sqlArquiva['ChatCafé Virtual'][] ="DELETE FROM ".PREFIXO_ARQ.".chatcafe_acesso as cca, ".PREFIXO_ARQ.".chatcafe_acesso_ as cca_, ".PREFIXO_ARQ.".forum_mensagem_cafevirtual as fmc, ".PREFIXO_ARQ.".chatcafe_mensagem as cm, ".PREFIXO_ARQ.".forum_sala_cafevirtual as fsc USING ".PREFIXO_ARQ.".chatcafe_acesso as cca, ".PREFIXO_ARQ.".chatcafe_acesso_ as cca_, ".PREFIXO_ARQ.".forum_mensagem_cafevirtual as fmc, ".PREFIXO_ARQ.".chatcafe_mensagem as cm, ".PREFIXO_ARQ.".forum_sala_cafevirtual as fsc WHERE cca_.COD_SALA = cca.COD_SALA AND cca.COD_SALA = fmc.COD_SALA AND fmc.COD_MENSAGEM = cm.codMensagem AND fmc.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['ChatCafé Virtual'][] ="insert into ".PREFIXO_ARQ.".chatcafe_acesso_ (select cca.* from chatcafe_acesso_ as cca, forum_sala_cafevirtual as fsc WHERE cca.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['ChatCafé Virtual'][] ="insert into ".PREFIXO_ARQ.".forum_mensagem_cafevirtual (select fmc.* from forum_mensagem_cafevirtual as fmc, forum_sala_cafevirtual as fsc WHERE fmc.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['ChatCafé Virtual'][] ="insert into ".PREFIXO_ARQ.".chatcafe_mensagem (select cm.* from chatcafe_mensagem as cm, forum_mensagem_cafevirtual as fmc, forum_sala_cafevirtual as fsc WHERE fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."' and fsc.COD_SALA = fmc.COD_SALA and fmc.COD_MENSAGEM = cm.codMensagem);";
    $sqlArquiva['ChatCafé Virtual'][] ="insert into ".PREFIXO_ARQ.".forum_sala_cafevirtual (select * from forum_sala_cafevirtual WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlArquiva['ChatCafé Virtual'][] ="DELETE FROM chatcafe_acesso as cca,chatcafe_acesso_ as cca_, forum_mensagem_cafevirtual as fmc, chatcafe_mensagem as cm, forum_sala_cafevirtual as fsc USING chatcafe_acesso as cca,
chatcafe_acesso_ as cca_, forum_mensagem_cafevirtual as fmc, chatcafe_mensagem as cm, forum_sala_cafevirtual as fsc
WHERE cca_.COD_SALA = cca.COD_SALA AND cca.COD_SALA = fmc.COD_SALA AND fmc.COD_MENSAGEM = cm.codMensagem AND fmc.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//AGENDA
    $sqlArquiva['Agenda'][] ="DELETE FROM ".PREFIXO_ARQ.".aula_agenda as aa, ".PREFIXO_ARQ.".arquivo_aula_agenda as aaa, ".PREFIXO_ARQ.".video_aula_agenda as vaa USING ".PREFIXO_ARQ.".aula_agenda as aa, ".PREFIXO_ARQ.".arquivo_aula_agenda as aaa, ".PREFIXO_ARQ.".video_aula_agenda as vaa WHERE aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = aaa.codAula AND aaa.codAula = vaa.codAula; ";

    $sqlArquiva['Agenda'][] ="insert into ".PREFIXO_ARQ.".aula_agenda (select * from aula_agenda where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";
    $sqlArquiva['Agenda'][] ="insert into ".PREFIXO_ARQ.".arquivo_aula_agenda (select aaa.* from arquivo_aula_agenda as aaa, aula_agenda as aa where aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = aaa.codAula);";  
    $sqlArquiva['Agenda'][] ="insert into ".PREFIXO_ARQ.".video_aula_agenda (select vaa.* from video_aula_agenda as vaa, aula_agenda as aa where aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = vaa.codAula);";  

    $sqlArquiva['Agenda'][] ="DELETE FROM aula_agenda as aa, arquivo_aula_agenda as aaa, video_aula_agenda as vaa USING
aula_agenda as aa, arquivo_aula_agenda as aaa, video_aula_agenda as vaa WHERE aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = aaa.codAula AND aaa.codAula = vaa.codAula;";
//------------------------------------------------------------------------------------------------------------------------
    $sqlArquiva['Scorm'][] ="DELETE FROM ".PREFIXO_ARQ.".scorm as s, ".PREFIXO_ARQ.".scormobject as so, ".PREFIXO_ARQ.".scormobjectdata as sod, ".PREFIXO_ARQ.".scormobjecttrack as sot USING ".PREFIXO_ARQ.".scorm as s, ".PREFIXO_ARQ.".scormobject as so, ".PREFIXO_ARQ.".scormobjectdata as sod, ".PREFIXO_ARQ.".scormobjecttrack as sot WHERE sot.codScorm = sod.codScorm AND sod.codScorm = so.codScorm AND so.codScorm = s.codScorm AND s.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Scorm'][] ="INSERT into ".PREFIXO_ARQ.".scorm (select * from scorm where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";

    $sqlArquiva['Scorm'][] ="INSERT into ".PREFIXO_ARQ.".scormobject (select SO.* from scormobject SO, scorm S where SO.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";

    $sqlArquiva['Scorm'][] ="INSERT into ".PREFIXO_ARQ.".scormobjectdata (select SOD.* from scormobjectdata SOD, scorm S where SOD.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";

    $sqlArquiva['Scorm'][] ="INSERT into ".PREFIXO_ARQ.".scormobjecttrack (select SOT.* from scormobjecttrack SOT, scorm S where SOT.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";

    //$sqlArquiva['Scorm'][] ="DELETE FROM scorm as s, scormobject as so, scormobjectdata as sod, scormobjecttrack as sot USING scorm as s, scormobject as so, scormobjectdata as sod, scormobjecttrack as sot WHERE sot.codScorm = sod.codScorm AND sod.codScorm = so.codScorm AND so.codScorm = s.codScorm AND s.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    
    $sqlArquiva['Scorm'][] = "DELETE scormobjectdata.* FROM scormobjectdata, scormobject, scorm WHERE scormobjectdata.codscorm = scormobject.codscorm AND scormobjectdata.codscormobj = scormobj.codscormobj and scorm.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    
    $sqlArquiva['Scorm'][] = "DELETE scormobjecttrack.* FROM scormobjecttrack, scormobject, scorm WHERE scormobjecttrack.codscorm = scormobject.codscorm AND scormobjecttrack.codscormobj = scormobj.codscormobj and scorm.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";

    $sqlArquiva['Scorm'][] = "DELETE scormobject.* FROM scorm, scormobject WHERE scorm.codscorm = scormobject.codscorm AND scorm.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    
    $sqlArquiva['Scorm'][] = "DELETE scorm.* FROM scorm WHERE scorm.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";

//------------------------------------------------------------------------------------------------------------------------

    for ($i=0; $i<count($arr);$i++){
      if (array_key_exists($arr[$i], $sqlArquiva)) {
        $sqlescolhidas[]= $sqlArquiva[$arr[$i]];
      }
    }
    return $sqlescolhidas;
  }
  
  /**
   * Gera as sql's para restaurar o ultimo arquivamento
   */       
  function restaura($codInstanciaGlobal='',$arr='') {
    $sqlRestore = array();
//------------------------------------------------------------------------------------------------------------------------
//LEMBRETES

    $sqlArquiva['Lembretes'][] = "INSERT INTO noticia (SELECT N.* FROM ".PREFIXO_ARQ.".noticia as N, ".PREFIXO_ARQ.".noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    $sqlRestore['Lembretes'][] = "INSERT INTO noticia_instancia (SELECT NI.* FROM ".PREFIXO_ARQ.".noticia as N, ".PREFIXO_ARQ.".noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    $sqlRestore['Lembretes'][] = "DELETE from ".PREFIXO_ARQ.".noticia as N, ".PREFIXO_ARQ.".noticia_instancia as NI using ".PREFIXO_ARQ.".noticia as N, ".PREFIXO_ARQ.".noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA  AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
//------------------------------------------------------------------------------------------------------------------------
//VÍDEO-AULAS

    $sqlRestore['Vídeo-aulas'][] = "insert into video_instancia (select * from ".PREFIXO_ARQ.".video_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";
    $sqlRestore['Vídeo-aulas'][] = "insert into video (select v.* from ".PREFIXO_ARQ.".video as v, ".PREFIXO_ARQ.".video_instancia as vi where v.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";
    $sqlRestore['Vídeo-aulas'][] = "insert into video_aula_agenda (select vaa.* from ".PREFIXO_ARQ.".video_aula_agenda as vaa, ".PREFIXO_ARQ.".video_instancia as vi where vaa.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    $sqlRestore['Vídeo-aulas'][] = "delete from ".PREFIXO_ARQ.".video as v using ".PREFIXO_ARQ.".video as v, ".PREFIXO_ARQ.".video_instancia as vi where v.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlRestore['Vídeo-aulas'][] = "delete from ".PREFIXO_ARQ.".video_aula_agenda as vaa using video_aula_agenda as vaa, ".PREFIXO_ARQ.".video_instancia as vi where vaa.COD_VIDEO = vi.COD_VIDEO AND vi.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlRestore['Vídeo-aulas'][] = "delete from ".PREFIXO_ARQ.".video_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//CONTEUDOS
    $sqlRestore['Conteúdos'][] = "insert into arquivo (select A.* from ".PREFIXO_ARQ.".arquivo A, ".PREFIXO_ARQ.".arquivo_instancia AI, WHERE A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";  
    $sqlRestore['Conteúdos'][] = "insert into arquivo_instancia (select * from ".PREFIXO_ARQ.".arquivo_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";
    $sqlRestore['Conteúdos'][] = "insert into arquivo_aluno_instancia (select AAI.* from ".PREFIXO_ARQ.".arquivo_aluno_instancia AAI, ".PREFIXO_ARQ.".arquivo_instancia AI WHERE AAI.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."')";

    $sqlRestore['Conteúdos'][] = "DELETE FROM ".PREFIXO_ARQ.".arquivo A, ".PREFIXO_ARQ.".arquivo_instancia AI, ".PREFIXO_ARQ.".arquivo_aluno_instancia AAI USING ".PREFIXO_ARQ.".arquivo A, ".PREFIXO_ARQ.".arquivo_instancia AI, ".PREFIXO_ARQ.".arquivo_aluno_instancia AAI WHERE AAI.COD_ARQUIVO = A.COD_ARQUIVO AND A.COD_ARQUIVO = AI.COD_ARQUIVO AND AI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."'";
//------------------------------------------------------------------------------------------------------------------------
//FORUM
    $sqlRestore['Fórum'][] = "insert into forum_mensagem (select fm.* from ".PREFIXO_ARQ.".forum_mensagem as fm, ".PREFIXO_ARQ.".forum_sala as fs where fm.COD_SALA = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Fórum'][] = "insert into forum_msg_novas (select fmn.* from ".PREFIXO_ARQ.".forum_msg_novas as fmn, ".PREFIXO_ARQ.".forum_sala as fs where fmn.codSala = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Fórum'][] = "insert into forum_sala (select * from ".PREFIXO_ARQ.".forum_sala where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['Fórum'][] = "delete from ".PREFIXO_ARQ.".forum_mensagem as fm using ".PREFIXO_ARQ.".forum_mensagem as fm, ".PREFIXO_ARQ.".forum_sala as fs where fm.COD_SALA = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlRestore['Fórum'][] = "delete from ".PREFIXO_ARQ.".forum_msg_novas as fmn using ".PREFIXO_ARQ.".forum_msg_novas as fmn, ".PREFIXO_ARQ.".forum_sala as fs where fmn.codSala = fs.COD_SALA AND fs.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
    $sqlRestore['Fórum'][] = "delete from ".PREFIXO_ARQ.".forum_sala as fs where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//ACERVO
    $sqlRestore['Acervo'][] = "insert into biblioteca (select * from ".PREFIXO_ARQ.".biblioteca WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['Acervo'][] = "DELETE FROM ".PREFIXO_ARQ.".biblioteca WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//ENQUETE
    $sqlRestore['Enquete'][] = "insert into enquete (select e.* from ".PREFIXO_ARQ.".enquete as e, ".PREFIXO_ARQ.".enquete_instancia as ei WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Enquete'][] = "insert into enquete_aluno_votou (select eav.* from ".PREFIXO_ARQ.".enquete_aluno_votou as eav, ".PREFIXO_ARQ.".enquete_instancia as ei WHERE eav.COD_ENQUETE_INSTANCIA = ei.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Enquete'][] = "insert into enquete_instancia (select * from ".PREFIXO_ARQ.".enquete_instancia WHERE COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Enquete'][] = "insert into enquete_resposta (select er.* from ".PREFIXO_ARQ.".enquete_instancia as ei, ".PREFIXO_ARQ.".enquete_resposta as er WHERE er.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['Enquete'][] = "DELETE FROM ".PREFIXO_ARQ.".enquete as e, ".PREFIXO_ARQ.".enquete_aluno_votou as eav, ".PREFIXO_ARQ.".enquete_instancia as ei, ".PREFIXO_ARQ.".enquete_resposta as er USING ".PREFIXO_ARQ.".enquete as e, ".PREFIXO_ARQ.".enquete_aluno_votou as eav, ".PREFIXO_ARQ.".enquete_instancia as ei, 
".PREFIXO_ARQ.".enquete_resposta as er WHERE e.COD_ENQUETE = ei.COD_ENQUETE AND ei.COD_ENQUETE = er.COD_ENQUETE AND ei.COD_ENQUETE_INSTANCIA = eav.COD_ENQUETE_INSTANCIA AND ei.COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//AULA INTERATIVA
    $sqlRestore['Aula Interativa'][] = "insert into chatconf (select * from ".PREFIXO_ARQ.".chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Aula Interativa'][] = "insert into chat_camera (select * from ".PREFIXO_ARQ.".chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['Aula Interativa'][] = "delete from ".PREFIXO_ARQ.".chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Aula Interativa'][] = "delete from ".PREFIXO_ARQ.".chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";

//chat_acesso  ??  COD_SALA
//chat_mensagem  ??  COD_SALA  
//------------------------------------------------------------------------------------------------------------------------
//EXERCICIO ON LINE
    $sqlRestore['Exercício on line'][] = "insert into exercicio_instancia (select * from ".PREFIXO_ARQ.".exercicio_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Exercício on line'][] = "insert into exercicio (select e.* from ".PREFIXO_ARQ.".exercicio as e, ".PREFIXO_ARQ.".exercicioinstancia as ei WHERE e.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Exercício on line'][] = "insert into exercicioquestao (select eq.* FROM ".PREFIXO_ARQ.".exercicioquestao as eq, ".PREFIXO_ARQ.".exercicioinstancia as ei WHERE eq.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Exercício on line'][] = "insert into exercicioinstancia (select * from ".PREFIXO_ARQ.".exercicioinstancia WHERE codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 

    $sqlRestore['Exercício on line'][] = "DELETE FROM ".PREFIXO_ARQ.".exercicio_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Exercício on line'][] = "DELETE FROM ".PREFIXO_ARQ.".exercicio as e, ".PREFIXO_ARQ.".exercicioinstancia as ei WHERE e.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Exercício on line'][] = "DELETE FROM ".PREFIXO_ARQ.".exercicioquestao eq, ".PREFIXO_ARQ.".exercicioinstancia as ei WHERE eq.codExercicio = ei.codExercicio AND ei.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Exercício on line'][] = "DELETE FROM ".PREFIXO_ARQ.".exercicioinstancia WHERE codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
//------------------------------------------------------------------------------------------------------------------------
//AVALIACAO
    $sqlRestore['Avaliação'][] = "insert into avaliacao (select a.* from ".PREFIXO_ARQ.".avaliacao as a,  ".PREFIXO_ARQ.".avaliacao_instancia as ai where a.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Avaliação'][] = "insert into avaliacao_aluno (select aa.* from ".PREFIXO_ARQ.".avaliacao_aluno as aa,  ".PREFIXO_ARQ.".avaliacao_instancia as ai where aa.COD_AVALIACAO_TURMA = ai.COD_AVALIACAO_TURMA AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Avaliação'][] = "insert into avaliacao_instancia_2 (select * from ".PREFIXO_ARQ.".avaliacao_instancia_2 where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Avaliação'][] = "insert into avaliacao_pergunta (select ap.* from ".PREFIXO_ARQ.".avaliacao_pergunta as ap, ".PREFIXO_ARQ.".avaliacao_resposta as ar, ".PREFIXO_ARQ.".avaliacao_instancia as ai where ar.COD_PERGUNTA = ap.COD_PERGUNTA AND ap.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Avaliação'][] = "insert into pesquisaavaliacaoinstanciapeloaluno (select * from ".PREFIXO_ARQ.".pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Avaliação'][] = "insert into pesquisaavaliacaopreenchida (select * from ".PREFIXO_ARQ.".pesquisaavaliacaopreenchida where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');"; 
    $sqlRestore['Avaliação'][] = "insert into avaliacao_instancia (select * from ".PREFIXO_ARQ.".avaliacao_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');"; 

    $sqlRestore['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao as a using ".PREFIXO_ARQ.".avaliacao as a, ".PREFIXO_ARQ.".avaliacao_instancia as ai where a.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_aluno as aa using ".PREFIXO_ARQ.".avaliacao_aluno as aa, ".PREFIXO_ARQ.".avaliacao_instancia as ai where aa.COD_AVALIACAO_TURMA = ai.COD_AVALIACAO_TURMA AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_instancia_2 where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_pergunta as ap, ".PREFIXO_ARQ.".avaliacao_resposta as ar using ".PREFIXO_ARQ.".avaliacao_pergunta as ap, ".PREFIXO_ARQ.".avaliacao_resposta as ar, ".PREFIXO_ARQ.".avaliacao_instancia as ai where ar.COD_PERGUNTA = ap.COD_PERGUNTA AND ap.COD_AVALIACAO = ai.COD_AVALIACAO AND ai.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Avaliação'][] = "delete from ".PREFIXO_ARQ.".pesquisaavaliacaoinstanciapeloaluno where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Avaliação'][] = "delete from ".PREFIXO_ARQ.".pesquisaavaliacaopreenchida where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';"; 
    $sqlRestore['Avaliação'][] = "delete from ".PREFIXO_ARQ.".avaliacao_instancia where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 
//------------------------------------------------------------------------------------------------------------------------
//NOTICIAS
    $sqlRestore['Notícias'][] = "insert into noticia_principal (select np.* from ".PREFIXO_ARQ.".noticia_principal AS NP, ".PREFIXO_ARQ.".noticia_instancia AS NI WHERE NP.COD_NOTICIA = NI.COD_NOTICIA AND NP.NRO_COLUNA_NOTICIA != '1' AND NI.NRO_COLUNA_NOTICIA != '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Notícias'][] = "insert into noticia_curso (select nc.* from ".PREFIXO_ARQ.".noticia_curso AS NC,  ".PREFIXO_ARQ.".noticia_instancia AS NI
WHERE NC.COD_NOTICIA = NI.COD_NOTICIA AND NC.NRO_COLUNA_NOTICIA != '1' AND NI.NRO_COLUNA_NOTICIA != '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Notícias'][] = "insert into noticia (select n.* from ".PREFIXO_ARQ.".noticia AS N, ".PREFIXO_ARQ.".noticia_instancia AS NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA != '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Notícias'][] = "insert into noticia_instancia (select * from ".PREFIXO_ARQ.".noticia_instancia WHERE NRO_COLUNA_NOTICIA != 1 AND COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['Notícias'][] = "DELETE FROM ".PREFIXO_ARQ.".noticia_principal AS NP, ".PREFIXO_ARQ.".noticia_curso AS NC, ".PREFIXO_ARQ.".noticia AS N, ".PREFIXO_ARQ.".noticia_instancia AS NI USING ".PREFIXO_ARQ.".noticia_principal AS NP, ".PREFIXO_ARQ.".noticia_curso AS NC, ".PREFIXO_ARQ.".noticia AS N, ".PREFIXO_ARQ.".noticia_instancia AS NI WHERE NP.COD_NOTICIA = NC.COD_NOTICIA AND NC.COD_NOTICIA = N.COD_NOTICIA AND N.COD_NOTICIA = NI.COD_NOTICIA AND NP.NRO_COLUNA_NOTICIA != 1 AND NC.NRO_COLUNA_NOTICIA != 1 AND NI.NRO_COLUNA_NOTICIA != 1 AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//FORUM TECNICO
    $sqlRestore['Fórum Técnico'][] ="insert into forum_mensagem_suportetecnico (select fms.* from ".PREFIXO_ARQ.".forum_mensagem_suportetecnico, ".PREFIXO_ARQ.".forum_sala_suportetecnico as fss WHERE fms.COD_SALA = fss.COD_SALA AND fss.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Fórum Técnico'][] ="insert into forum_sala_suportetecnico (select * from ".PREFIXO_ARQ.".forum_sala_suportetecnico WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['Fórum Técnico'][] ="DELETE FROM ".PREFIXO_ARQ.".forum_mensagem_suportetecnico as fms, ".PREFIXO_ARQ.".forum_sala_suportetecnico as fss USING ".PREFIXO_ARQ.".forum_mensagem_suportetecnico as fms, ".PREFIXO_ARQ.".forum_sala_suportetecnico as fss WHERE fms.COD_SALA = fss.COD_SALA AND fss.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//chatcafe_mensagem ??
    $sqlRestore['ChatCafé Virtual'][] ="insert into chatcafe_acesso_ (select cca.* from ".PREFIXO_ARQ.".chatcafe_acesso_ as cca, ".PREFIXO_ARQ.".forum_sala_cafevirtual as fsc WHERE cca.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['ChatCafé Virtual'][] ="insert into forum_mensagem_cafevirtual (select fmc.* from ".PREFIXO_ARQ.".forum_mensagem_cafevirtual as fmc, ".PREFIXO_ARQ.".forum_sala_cafevirtual as fsc WHERE fmc.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['ChatCafé Virtual'][] ="insert into chatcafe_mensagem (select cm.* from ".PREFIXO_ARQ.".chatcafe_mensagem as cm, ".PREFIXO_ARQ.".forum_mensagem_cafevirtual as fmc, ".PREFIXO_ARQ.".forum_sala_cafevirtual as fsc WHERE fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."' and fsc.COD_SALA = fmc.COD_SALA and fmc.COD_MENSAGEM = cm.codMensagem);";
    $sqlRestore['ChatCafé Virtual'][] ="insert into forum_sala_cafevirtual (select * from ".PREFIXO_ARQ.".forum_sala_cafevirtual WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['ChatCafé Virtual'][] ="DELETE FROM ".PREFIXO_ARQ.".chatcafe_acesso as cca, ".PREFIXO_ARQ.".chatcafe_acesso_ as cca_, ".PREFIXO_ARQ.".forum_mensagem_cafevirtual as fmc, ".PREFIXO_ARQ.".chatcafe_mensagem as cm, ".PREFIXO_ARQ.".forum_sala_cafevirtual as fsc USING ".PREFIXO_ARQ.".chatcafe_acesso as cca, ".PREFIXO_ARQ.".chatcafe_acesso_ as cca_, ".PREFIXO_ARQ.".forum_mensagem_cafevirtual as fmc, ".PREFIXO_ARQ.".chatcafe_mensagem as cm, ".PREFIXO_ARQ.".forum_sala_cafevirtual as fsc WHERE cca_.COD_SALA = cca.COD_SALA AND cca.COD_SALA = fmc.COD_SALA AND fmc.COD_MENSAGEM = cm.codMensagem AND fmc.COD_SALA = fsc.COD_SALA AND fsc.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";
//------------------------------------------------------------------------------------------------------------------------
//AGENDA
    $sqlRestore['Agenda'][] ="insert into aula_agenda (select * from ".PREFIXO_ARQ.".aula_agenda where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Agenda'][] ="insert into arquivo_aula_agenda (select aaa.* from ".PREFIXO_ARQ.".arquivo_aula_agenda as aaa, ".PREFIXO_ARQ.".aula_agenda as aa where aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = aaa.codAula);";  
    $sqlRestore['Agenda'][] ="insert into video_aula_agenda (select vaa.* from ".PREFIXO_ARQ.".video_aula_agenda as vaa, ".PREFIXO_ARQ.".aula_agenda as aa where aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = vaa.codAula);";  

    $sqlRestore['Agenda'][] ="DELETE FROM ".PREFIXO_ARQ.".aula_agenda as aa, ".PREFIXO_ARQ.".arquivo_aula_agenda as aaa, ".PREFIXO_ARQ.".video_aula_agenda as vaa USING ".PREFIXO_ARQ.".aula_agenda as aa, ".PREFIXO_ARQ.".arquivo_aula_agenda as aaa, ".PREFIXO_ARQ.".video_aula_agenda as vaa WHERE aa.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."' AND aa.codAula = aaa.codAula AND aaa.codAula = vaa.codAula; ";
//------------------------------------------------------------------------------------------------------------------------
    $sqlRestore['Scorm'][] ="INSERT into scorm (select * from ".PREFIXO_ARQ.".scorm where codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Scorm'][] ="INSERT into scormobject (select SO.* from ".PREFIXO_ARQ.".scormobject SO, ".PREFIXO_ARQ.".scorm S where SO.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Scorm'][] ="INSERT into scormobjectdata (select SOD.* from ".PREFIXO_ARQ.".scormobjectdata SOD, ".PREFIXO_ARQ.".scorm S where SOD.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";
    $sqlRestore['Scorm'][] ="INSERT into scormobjecttrack (select SOT.* from ".PREFIXO_ARQ.".scormobjecttrack SOT, ".PREFIXO_ARQ.".scorm S where SOT.codScorm = S.codScorm AND S.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."');";

    $sqlRestore['Scorm'][] ="DELETE FROM ".PREFIXO_ARQ.".scorm as s, ".PREFIXO_ARQ.".scormobject as so, ".PREFIXO_ARQ.".scormobjectdata as sod, ".PREFIXO_ARQ.".scormobjecttrack as sot USING ".PREFIXO_ARQ.".scorm as s, ".PREFIXO_ARQ.".scormobject as so, ".PREFIXO_ARQ.".scormobjectdata as sod, ".PREFIXO_ARQ.".scormobjecttrack as sot WHERE sot.codScorm = sod.codScorm AND sod.codScorm = so.codScorm AND so.codScorm = s.codScorm AND s.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";
    
    //retorna apenas as queries escolhidas
    for ($i=0; $i<count($arr);$i++){
      if (array_key_exists($arr[$i], $sqlRestore)) {
        $sqlescolhidas[]= $sqlRestore[$arr[$i]];
      }
    }
    return $sqlescolhidas;
  }

  function contaReg($codInstanciaGlobal="",$recurso=""){

    $sqlContaReg = array();

    $sqlContaReg['Lembretes'] = "SELECT COUNT(N.COD_NOTICIA) as linhas FROM noticia as N, noticia_instancia as NI WHERE N.COD_NOTICIA = NI.COD_NOTICIA AND NI.NRO_COLUNA_NOTICIA = '1' AND NI.COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";  

    $sqlContaReg['Vídeo-aulas'] = "SELECT COUNT(*) as linhas FROM video_instancia WHERE COD_INSTANCIA_GLOBAL='".quote_smart($codInstanciaGlobal)."';";

    $sqlContaReg['Conteúdos'] = "SELECT COUNT(*) as linhas FROM arquivo_instancia WHERE COD_INSTANCIA_GLOBAL  = '".quote_smart($codInstanciaGlobal)."'";  

    $sqlContaReg['Fórum'] = "SELECT COUNT(*) as linhas from forum_sala as fs where COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlContaReg['Acervo'] = "SELECT COUNT(*) as linhas FROM biblioteca WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlContaReg['Enquete'] = "SELECT count(COD_ENQUETE) AS linhas FROM enquete_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlContaReg['Aula Interativa'][] = "SELECT COUNT(*) as linhas FROM chatconf where codInstanciaGlobal ='".quote_smart($codInstanciaGlobal)."';"; 

    $sqlContaReg['Aula Interativa'][] = "SELECT COUNT(*) as linhas FROM chat_camera where COD_INSTANCIA_GLOBAL ='".quote_smart($codInstanciaGlobal)."';";
//chat_acesso  ??  COD_SALA
//chat_mensagem  ??  COD_SALA  

    $sqlContaReg['Exercício on line'] = "SELECT COUNT(*) as linhas FROM exercicio_instancia WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 

    $sqlContaReg['Avaliação'] = "SELECT COUNT(*) as linhas FROM avaliacao_instancia_2 WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';"; 

    $sqlContaReg['Notícias'] ="SELECT COUNT(*) as linhas FROM noticia_instancia WHERE NRO_COLUNA_NOTICIA != 1
AND COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlContaReg['Fórum Técnico'] ="SELECT COUNT(*) as linhas FROM forum_sala_suportetecnico WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

//chatcafe_mensagem ??
    $sqlContaReg['ChatCafé Virtual'] ="SELECT COUNT(*) as linhas FROM forum_sala_cafevirtual WHERE COD_INSTANCIA_GLOBAL = '".quote_smart($codInstanciaGlobal)."';";

    $sqlContaReg['Agenda'] ="SELECT COUNT(codAula) as linhas FROM aula_agenda WHERE aula_agenda.codInstanciaGlobal = '".quote_smart($codInstanciaGlobal)."';";

    $sqlContaReg['Scorm'] ="SELECT COUNT(*) as linhas FROM scorm WHERE codInstanciaGlobal='".quote_smart($codInstanciaGlobal)."';";

    return $sqlContaReg[$recurso];
  }

}
?>
