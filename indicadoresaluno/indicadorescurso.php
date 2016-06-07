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
?>


/*CAMPOS:Disciplina Aluno Chat Fórum Fórum Técnico Café Virtual Último Acesso Número de Acessos*/ 
CREATE TEMPORARY TABLE IndicadoresCurso (
       nomeDisciplina varchar(100),
       nomeTurma varchar(100),
       nomeAluno varchar(100),
       codDisciplina int(11) NOT NULL,
       codTurma int(11) NOT NULL,
       codPessoa int(11) NOT NULL,
       chat int(11) NOT NULL,
       forum int(11) NOT NULL,
       forumTecnico int(11) NOT NULL,
       cafeVirtual int(11) NOT NULL,
       ultimoAcesso varchar(15) ,
       numeroAcessosTotal int(11) NOT NULL,
       PRIMARY KEY (codDisciplina,codTurma,codPessoa)
);
/* INSERE OS ALUNOS DO CURSO */
INSERT INTO IndicadoresCurso (nomeDisciplina,nomeTurma,nomeAluno,codDisciplina,codTurma,codPessoa)
Select D.DESC_DIS,T.NOME_TURMA,P.NOME_PESSOA,D.COD_DIS,T.COD_TURMA,P.COD_PESSOA from 
disciplina D 
INNER JOIN turma T ON (D.COD_DIS=T.COD_DIS)
INNER JOIN aluno_turma AT ON (T.COD_TURMA=AT.COD_TURMA)
INNER JOIN aluno A ON (AT.COD_AL=A.COD_AL)
INNER JOIN pessoa P ON (A.COD_PESSOA=P.COD_PESSOA)
Where D.codCurso=4;


/*  Mensagens do fórum */
CREATE TEMPORARY TABLE Forum (
       codDisciplina int(11) NOT NULL,
       codTurma int(11) NOT NULL,
       codPessoa int(11) NOT NULL,
       forum int(11) NOT NULL,
       PRIMARY KEY (codDisciplina,codTurma,codPessoa)
);

INSERT INTO Forum (codDisciplina,codTurma,codPessoa,forum)
SELECT T.COD_DIS, T.COD_TURMA, M.COD_PESSOA, count( * ) AS numMsg
FROM forum_mensagem M
INNER JOIN forum_sala S ON ( M.COD_SALA = S.COD_SALA ) 
INNER JOIN instanciaglobal I ON ( S.COD_INSTANCIA_GLOBAL = I.codInstanciaGlobal ) 
INNER JOIN turma T ON ( I.codNivel =4
AND I.codInstanciaNivel = T.COD_TURMA ) 
INNER JOIN disciplina D ON ( T.COD_DIS=D.COD_DIS) 
Where D.codCurso=4
GROUP BY T.COD_TURMA, M.COD_PESSOA; 

Update Forum F, IndicadoresCurso I SET
I.forum=F.forum
Where
F.codDisciplina=I.codDisciplina AND
F.codTurma=I.codTurma AND
F.codPessoa=I.codPessoa;

/*  Mensagens do fórum técnico*/
CREATE TEMPORARY TABLE ForumTecnico (
       codDisciplina int(11) NOT NULL,
       codTurma int(11) NOT NULL,
       codPessoa int(11) NOT NULL,
       forumTecnico int(11) NOT NULL,
       PRIMARY KEY (codDisciplina,codTurma,codPessoa)
);
INSERT INTO ForumTecnico(codDisciplina,codTurma,codPessoa,forumTecnico)
SELECT T.COD_DIS, T.COD_TURMA, M.COD_PESSOA, count( * ) AS numMsg
FROM forum_mensagem_suportetecnico M
INNER JOIN forum_sala_suportetecnico S ON ( M.COD_SALA = S.COD_SALA ) 
INNER JOIN instanciaglobal I ON ( S.COD_INSTANCIA_GLOBAL = I.codInstanciaGlobal ) 
INNER JOIN turma T ON ( I.codNivel =4
AND I.codInstanciaNivel = T.COD_TURMA ) 
INNER JOIN disciplina D ON ( T.COD_DIS=D.COD_DIS) 
Where D.codCurso=4
GROUP BY T.COD_TURMA, M.COD_PESSOA; 

Update ForumTecnico F, IndicadoresCurso I SET
I.forumTecnico=F.forumTecnico
Where
F.codDisciplina=I.codDisciplina AND
F.codTurma=I.codTurma AND
F.codPessoa=I.codPessoa;


/*  Mensagens do Café Virtual*/
CREATE TEMPORARY TABLE CafeVirtual (
       codDisciplina int(11) NOT NULL,
       codTurma int(11) NOT NULL,
       codPessoa int(11) NOT NULL,
       cafeVirtual int(11) NOT NULL,
       PRIMARY KEY (codDisciplina,codTurma,codPessoa)
);
INSERT INTO CafeVirtual (codDisciplina,codTurma,codPessoa,cafeVirtual)
SELECT T.COD_DIS, T.COD_TURMA, M.COD_PESSOA, count( * ) AS numMsg
FROM forum_mensagem_cafevirtual M
INNER JOIN forum_sala_cafevirtual S ON ( M.COD_SALA = S.COD_SALA ) 
INNER JOIN instanciaglobal I ON ( S.COD_INSTANCIA_GLOBAL = I.codInstanciaGlobal ) 
INNER JOIN turma T ON ( I.codNivel =4
AND I.codInstanciaNivel = T.COD_TURMA ) 
INNER JOIN disciplina D ON ( T.COD_DIS=D.COD_DIS) 
Where D.codCurso=4
GROUP BY T.COD_TURMA, M.COD_PESSOA; 

Update CafeVirtual F, IndicadoresCurso I SET
I.cafeVirtual=F.cafeVirtual
Where
F.codDisciplina=I.codDisciplina AND
F.codTurma=I.codTurma AND
F.codPessoa=I.codPessoa;

/* Mensagens do Chat  */
CREATE TEMPORARY TABLE Chat (
     codDisciplina int(11) NOT NULL,
     codTurma int(11) NOT NULL,
     codPessoa int(11) NOT NULL,
     chat int(11) NOT NULL,
     PRIMARY KEY (codDisciplina,codTurma,codPessoa)
);

<?
mysql_connect("localhost","navi","N@v13Ea#05");  //a partir do berlineta
//mysql_connect("192.168.101.1", "eavirtualuser","bq5g2@j8$");  //a partir do monareta
mysql_select_db("navi");

/* Descobrir as instancias globais das turmas para fazer o join com cada uma */
$sql = "SELECT D.COD_DIS,T.COD_TURMA,I.codInstanciaGlobal
FROM disciplina D
INNER JOIN turma T ON ( D.COD_DIS = T.COD_DIS ) 
INNER JOIN instanciaglobal I ON ( I.codNivel =4
AND I.codInstanciaNivel = T.COD_TURMA ) 
WHERE D.codCurso =4;";
//mysql_connect("",)
$result = mysql_query($sql);

$sqlChat='';
while ($linha = mysql_fetch_assoc($result) ) {
  $sqlChat.= " SELECT ".$linha['COD_DIS'].",".$linha['COD_TURMA'].",".
  "COD_PESSOA, count( * ) AS numMsg
  FROM chat_mensagem_".$linha['codInstanciaGlobal']." C
  WHERE NOME_ENVIA != 'NAVi'
  GROUP BY COD_SALA, COD_PESSOA
  UNION ";
}
$sqlChat = rtrim($sqlChat, "UNION ");
$sqlChat.=";";

$sqlChat="INSERT INTO Chat (codDisciplina,codTurma,codPessoa,chat) ".$sqlChat;
echo $sqlChat;

?>
Update Chat C, IndicadoresCurso I SET
I.chat=C.chat
Where
C.codDisciplina=I.codDisciplina AND
C.codTurma=I.codTurma AND
C.codPessoa=I.codPessoa;



/* Número total de acessos de cada pessoa e o ultimo acesso */
CREATE TEMPORARY TABLE AcessosPessoa (
       codPessoa int(11) NOT NULL,
       ultimoAcesso varchar(15),
       numeroAcessosTotal int(11) NOT NULL,
       PRIMARY KEY (codPessoa)
);

INSERT INTO AcessosPessoa (codPessoa,ultimoAcesso,numeroAcessosTotal) 
SELECT A.codPessoa,P.ultimoAcesso,count( * ) AS numeroAcessos 
FROM 
acessopessoa A
INNER JOIN IndicadoresCurso I ON (A.codPessoa=I.codPessoa)
INNER JOIN pessoa P ON (A.codPessoa=P.COD_PESSOA)
GROUP BY A.codPessoa;

Update AcessosPessoa A, IndicadoresCurso I SET
I.ultimoAcesso=A.ultimoAcesso,
I.numeroAcessosTotal=A.numeroAcessosTotal
Where
A.codPessoa=I.codPessoa;

/* Mostra os Indicadores do Curso */
Select * from IndicadoresCurso;



<!--
/* Descobrir as instancias globais das turmas  */
SELECT I.codInstanciaGlobal
FROM disciplina D
INNER JOIN turma T ON ( D.COD_DIS = T.COD_DIS ) 
INNER JOIN instanciaglobal I ON ( I.codNivel =4
AND I.codInstanciaNivel = T.COD_TURMA ) 
WHERE D.codCurso =4;

/* Mensagens do Chat 
SELECT COD_SALA, COD_PESSOA, count( * ) AS numMsg
FROM chat_mensagem_127
WHERE NOME_ENVIA != 'NAVi'
GROUP BY COD_SALA, COD_PESSOA
UNION SELECT COD_SALA, COD_PESSOA, count( * ) AS numMsg
FROM chat_mensagem_128
WHERE NOME_ENVIA != 'NAVi'
GROUP BY COD_SALA, COD_PESSOA
*/
-->
