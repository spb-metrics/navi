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
 * short desc: Funções especificas de comunidade
 *
 * long desc:
 *
 * @package Kernel
 * @author DFL Consultoria Ltda/Fábio Moreira da Silva
 * @version 1.0
 * @abstract
 * @copyright NAVi - Escola de Administração/UFRGS 2007
 */
class Comunidade  {

  function relacionaComInstancia($codInstanciaGlobal,$codComunidadeTematica) {
    $sql = ' INSERT INTO instanciacomunidade (codInstanciaGlobal, codComunidadeTematica)'.
           ' VALUES ('.quote_smart($codInstanciaGlobal).','.quote_smart($codComunidadeTematica).');';
    mysql_query($sql);
  }

}