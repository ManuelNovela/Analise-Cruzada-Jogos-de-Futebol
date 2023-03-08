<?php


/*****
 * 
 * 
 *   GRUPO HOLD
 * 
 */
  
function DB_CREATE_GRUPO_HOLD($numero_cliente, $id_usuario){
    global $conn;

    $sql = $conn->query("INSERT INTO `grupo_hold`(`numero_cliente`, `id_usuario`) VALUES (".secure($numero_cliente).", ".secure($id_usuario, 'int').");");
    return true;
}

function DB_GET_GRUPO_HOLD($numero_cliente, $id_usuario)
{
    global $conn;
    $sql = $conn->query("SELECT `id` FROM `grupo_hold` WHERE `is_active` = 1 AND `numero_cliente`= " . secure($numero_cliente) . "AND `id_usuario` = " . secure($id_usuario, 'int') . ";");
    while ($data = $sql->fetch_assoc()) {
        return true;
    }
    return false;
}

function DB_STOP_GRUPO_HOLD($numero_cliente, $id_usuario)
{
    global $conn;
    $sql = $conn->query("UPDATE `grupo_hold` SET `status`='0' WHERE `numero_cliente`= " . secure($numero_cliente) . "AND `id_usuario` = " . secure($id_usuario, 'int') . ";");
    return false;
}


/****
 *
 *
 * grupos
 *
 */

function DB_GRUPO_CREATE($usuario_id, $serial){
    global $conn;
    $sql = $conn->query("INSERT INTO `grupo`(`usuario_id`, `serial`) VALUES (".secure($usuario_id, 'int')." , ". secure($serial)." );");
    return true;
}

function DB_GRUPO_READ($usuario_id){
    global $conn;
    $sql = $conn->query("SELECT * FROM `grupo` WHERE `active` = 1 AND `membros` < 256 AND `usuario_id` = ".secure($usuario_id, 'int')." ORDER BY `id` DESC LIMIT 1;");
    while ($data = $sql->fetch_assoc()){
        return $data;
    }
    return false;
}

function DB_GRUPO_ADD_MEMBRO($usuario_id, $serial){
    global $conn;
    $sql = $conn->query("UPDATE `grupo` SET `membros`= (`membros`+ 1) WHERE `usuario_id`= ".secure($usuario_id, 'int')." AND `serial`= ". secure($serial)." ;");
    return true;
}
function DB_GRUPO_REMOVE_MEMBRO($usuario_id, $serial){
    global $conn;
    $sql = $conn->query("UPDATE `grupo` SET `membros`= (`membros` - 1) WHERE `usuario_id`= ".secure($usuario_id, 'int')." AND `serial`= ". secure($serial)." ;");
    return true;
}

function DB_GRUPOS_READ($usuario_id){
    global $conn;
    $i = 0;
    $sql = $conn->query("SELECT * FROM `grupo` WHERE `active` = 1 AND `usuario_id` = ".secure($usuario_id, 'int')." ;");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['usuario_id'] = $data['usuario_id'];
        $dado[$i]['serial'] = $data['serial'];
        $dado[$i]['membros'] = $data['membros'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}

//Suporte


function DB_SUPORTE_CREATE($numero_cliente, $id_usuario){
    global $conn;

    $resposta = ["/suporte"];
    $respostas_json = json_encode($resposta);

    $sql = $conn->query("INSERT INTO `suporte`(`numero_cliente`, `id_usuario`, `respostas`) VALUES ( ".secure($numero_cliente).", ".secure($id_usuario, 'int').", ".secure($respostas_json,'utf8').");");
    return true;
}

function DB_SUPORTE_ON_CARE($numero_cliente, $id_usuario)
{
    global $conn;
    $sql = $conn->query("SELECT `id` FROM `suporte` WHERE `is_active` = 1 AND `numero_cliente`= " . secure($numero_cliente) . "AND `id_usuario` = " . secure($id_usuario, 'int') . ";");
    while ($data = $sql->fetch_assoc()) {
        return true;
    }
    return false;
}

function DB_SUPORTE_READ($numero_cliente, $id_usuario){
    global $conn;
    $sql = $conn->query("SELECT * FROM `suporte` WHERE `numero_cliente`= " . secure($numero_cliente) . "AND `id_usuario` = " . secure($id_usuario, 'int') . " ORDER BY `suporte`.`id` DESC LIMIT 1;");
    while ($data = $sql->fetch_assoc()){
        return $data;
    }
    return false;
}

function DB_SUPORTE_ADD_REPLY($numero_cliente, $id_usuario, $nova_reposta){
    global $conn;

    //load ultimas respostas
    $dados = DB_SUPORTE_READ($numero_cliente, $id_usuario);

    $respostas = json_decode($dados['respostas'], true);

    /// echo $dados['respostas'];
    // echo "<br>";
    // var_dump($respostas);
    // echo "<br>";
    //add new resposta
    array_push( $respostas, $nova_reposta);
    $respostas_json = json_encode($respostas);

    //var_dump($respostas_json);

    $sql = $conn->query("UPDATE `suporte` SET `respostas`=".secure($respostas_json,'utf8').", `pergunta_atual`=".secure(sizeof($respostas))." WHERE `id` = ".secure($dados['id'], 'int').";");
    return true;
}

function DB_SUPORTE_STATUS($id, $status){
    global $conn;
    $sql = $conn->query("UPDATE `suporte` SET `status`=".secure($status,'int')."  WHERE `id` = ".secure($id, 'int').";");
    return true;
}

function DB_SUPORTE_CLOSE_REPLY($id){
    global $conn;

    $sql = $conn->query("UPDATE `suporte` SET `is_active`=0  WHERE `id` = ".secure($id, 'int').";");
    return true;
}

function DB_SUPORTE_SHOW($id){
    global $conn;
    //$perguntas = DB_SUPORTE_READ_PERGUNTAS_LIST($id_usuario);
    $sql = $conn->query("SELECT * FROM `suporte` WHERE `id` = ".secure($id, 'int').";");
    while ($data = $sql->fetch_assoc()){
        return $data;
    }

    //$dados[$i] = false;
    //        $respostas = json_decode($data['respostas'], true);
    //        for ($j = 0; $j < sizeof($perguntas); $j++){
    //            $dados[$i][$j]['pergunta'] = $perguntas[$j];
    //            $dados[$i][$j]['respostas'] = $respostas[$j];
    //        }


    return false;
}

function DB_GET_SUPORTE($id_usuario){
    global $conn;
    $i = 0;
    $sql = $conn->query("SELECT * FROM `suporte` WHERE `is_active`=0 AND `id_usuario` = ".secure($id_usuario, 'int').";");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['numero_cliente'] = $data['numero_cliente'];
        $dado[$i]['respostas'] = $data['respostas'];
        $dado[$i]['pergunta_atual'] = $data['pergunta_atual'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}
/** perguntas **/


function DB_SUPORTE_READ_PERGUNTAS($id_usuario){
    global $conn;
    $sql = $conn->query("SELECT * FROM `suporte_perguntas` WHERE `usuario_id` = " . secure($id_usuario, 'int') . " ;");
    while ($data = $sql->fetch_assoc()){
        return $data;
    }
    return false;
}

function DB_SUPORTE_READ_PERGUNTAS_LIST($id_usuario){
    global $conn;
    $dados = false;
    $sql = $conn->query("SELECT * FROM `suporte_perguntas` WHERE `usuario_id` = " . secure($id_usuario, 'int') . " ;");
    while ($data = $sql->fetch_assoc()){
        $perguntas = json_decode($data['perguntas'], true);
        for ($i = 0; $i < sizeof($perguntas); $i++){
            $dados[$i]['id'] = $i+1;
            $dados[$i]['descricao'] = $perguntas[$i];
        }
        return $dados;
    }

    return false;
}



function DB_SUPORTE_ADD_REPLY_PERGUNTAS($id_usuario, $nova_pergunta){
    global $conn;

    //load ultimas perguntas
    $dados = DB_SUPORTE_READ_PERGUNTAS($id_usuario);
    if($dados) {
        //update perguntas deste usuario
        $perguntas = json_decode($dados['perguntas'], true);
        array_push($perguntas, $nova_pergunta);
        $perguntas_json = json_encode($perguntas);

        $sql = $conn->query("UPDATE `suporte_perguntas` SET `perguntas`=" . secure($perguntas_json, 'utf8') . " WHERE `id` = " . secure($dados['id'], 'int') . ";");
        return true;
    }else{
        //criar perguntas deste novo usuario suporte
        $perguntas = [$nova_pergunta];
        $perguntas_json = json_encode($perguntas);

        $sql = $conn->query("INSERT INTO `suporte_perguntas`(`usuario_id`, `perguntas`) VALUES ( ".secure($id_usuario, 'int').", ".secure($perguntas_json,'utf8').");");
        return true;

    }
}


function DB_SUPORTE_REPLACE_REPLY_PERGUNTAS($id_usuario, $nova_pergunta, $id){
    global $conn;


    echo "$id_usuario -  $nova_pergunta - $id";

    //load ultimas perguntas
    $dados = DB_SUPORTE_READ_PERGUNTAS($id_usuario);
    $perguntas = json_decode($dados['perguntas']);

    $perguntas[$id - 1] = $nova_pergunta;
    //update perguntas deste usuario

    $perguntas_json = json_encode($perguntas);

    $sql = $conn->query("UPDATE `suporte_perguntas` SET `perguntas`=" . secure($perguntas_json, 'utf8') . " WHERE `id` = " . secure($dados['id'], 'int') . ";");
    return true;

}


function DB_SUPORTE_DELETE_REPLY_PERGUNTAS($id_usuario, $id){
    global $conn;

    //load ultimas perguntas
    $dados = DB_SUPORTE_READ_PERGUNTAS($id_usuario);
    $perguntas = json_decode($dados['perguntas']);

    unset($perguntas[$id - 1]);
    //update perguntas deste usuario

    $perguntas = array_filter($perguntas);

    $perguntas_json = json_encode($perguntas);
    str_replace(',""', '',$perguntas_json);
    echo $perguntas_json;


    $sql = $conn->query("UPDATE `suporte_perguntas` SET `perguntas`=" . secure($perguntas_json, 'utf8') . " WHERE `id` = " . secure($dados['id'], 'int') . ";");
    return true;

}
/***
    FIM SUPORTE
 ***/



function DB_UPDATE_USER($dados){
    global $conn;
    if(isset($dados['servidor_ip']) && isset($dados['instancia'])){
        $sql = $conn->query(sprintf("UPDATE `usuario` SET `instancia`= %s,`servidor_ip`=%s  WHERE `id` = %s", secure($dados['instancia']),  secure($dados['servidor_ip']),secure($dados['id'], 'int') )) or _error("SQL_ERROR_THROWEN");
    }

    if(isset($dados['foto'])){
        if($dados['foto'] != ''){
                $sql = $conn->query(sprintf("UPDATE `usuario` SET `foto`= %s  WHERE `id` = %s", secure($dados['foto']), secure($dados['id'], 'int') )) or _error("SQL_ERROR_THROWEN");
        }
    }

    $sql = $conn->query(sprintf("UPDATE `usuario` SET `email`=%s,`password`=%s,`nome`=%s,`whatsapp`=%s WHERE `id` = %s",   secure($dados['email']),secure($dados['password']),secure($dados['nome']),secure($dados['whatsapp']),secure($dados['id'], 'int') )) or _error("SQL_ERROR_THROWEN");


    return true;
}

function DB_DELETE_USER($id){
    global $conn;
        $sql = $conn->query("DELETE FROM `auto_resposta` WHERE `campanha_id` in (SELECT `id` FROM `campanha` WHERE `usuario_id` = ".$id." );");
        $sql = $conn->query("DELETE FROM `campanha` WHERE `usuario_id` = ".$id." ;");
        $sql = $conn->query("DELETE FROM `grupo` WHERE `usuario_id` =".$id." ;");
        $sql = $conn->query("DELETE FROM `suporte` WHERE `id_usuario` = ".$id." ;");
        $sql = $conn->query("DELETE FROM `suporte_perguntas` WHERE `usuario_id` = ".$id." ;");
        $sql = $conn->query("DELETE FROM `usuario` WHERE `id`=".$id." ;");
    return true;
}

function DB_GET_NOW(){
    global $conn;
    $sql = $conn->query("SELECT NOW() as agora");
    while ($data = $sql->fetch_assoc()){
        return $data['agora'];
    }
    return '2022-02-13 06:30:33';
}

function DB_setUltimo($id){
    global $conn;
    $sql = $conn->query("UPDATE usuario SET ultimo = NOW()  WHERE id=".$id.";");
    return true;
}

function DB_AGENDA_ENVIADO($id){
    global $conn;
/*
    $conn->begin_transaction();
    try {
        $sql = $conn->query("LOCK TABLES mensagens_agendadas WRITE;");
      */
        $sql = $conn->query("UPDATE `mensagens_agendadas` SET `estado`= 1 WHERE id=".$id.";");
/*
	$sql = $conn->query("UNLOCK TABLES;");
        $conn->commit();
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();

        throw $exception;
    }

*/
    return true;
}

function DB_AGENDA_DETETAR($id){
    global $conn;
        $sql =$conn->query("DELETE FROM `mensagens_agendadas` WHERE `estado` = 1");
    return true;
}


function DB_AGENDA_GRAVAR($to_id,$mensagem, $from, $time){
    global $conn;

    $sql = $conn->query("INSERT INTO `mensagens_agendadas`( `to_user_id`, `mensagem`, `from_number`, `estado`, `time`) VALUES ( ".secure($to_id,'int')."  ,  ".secure($mensagem,'utf8')."   ,  ".secure($from)." , 0 , ".secure($time, 'int')."  );");
    //$sql = $conn->query("UPDATE `mensagens_agendadas` SET `estado`= 1 WHERE `mensagem` IN (SELECT `resposta` FROM `campanha` WHERE 1);");


    //$sql = $conn->query("UPDATE `mensagens_agendadas` SET `estado`=1 WHERE `time`= 0 ;");
    //$sql = $conn->query("INSERT INTO `mensagens_agendadas`( `to_user_id`, `mensagem`, `from_number`, `estado`, `time`) VALUES ( ".secure($to_id,'int')."  ,  ".secure($mensagem)."   ,  ".secure($from)." , 0 , ".secure($time, 'int')."  );");


    return true;
}

function DB_getUltimo($id){
    global $conn, $system;
    $sql = $conn->query("SELECT ultimo FROM usuario WHERE id=".$id.";");
    while ($data = $sql->fetch_assoc()){
        return $data['ultimo'];
    }
    return '2022-02-13 06:30:33';
}

function DB_AGENDAS_isPendente($agenda_id){
    global $conn;
    $sql = $conn->query("SELECT * FROM `mensagens_agendadas` WHERE `estado` = 0 AND `id`=".$agenda_id.";");
    while ($data = $sql->fetch_assoc()){
        return true;
    }
    return false;
}

function DB_GET_AGENDAS($id){
    global $conn;
    $i = 0;
    $sql = $conn->query("SELECT * FROM `mensagens_agendadas` WHERE `estado` = 0 AND `to_user_id`=".$id.";");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['to_user_id'] = $data['to_user_id'];
        $dado[$i]['mensagem'] = $data['mensagem'];
        $dado[$i]['from_number'] = $data['from_number'];
        $dado[$i]['estado'] = $data['estado'];
        $i+=1;
    }
    if($i === 0 ){
        return null;
    }
    return $dado;
}

function DB_GET_AUTO_MENSSAGE($id, $mensagem){
    global $conn;
    $i = 0;
    $sql = $conn->query("SELECT * FROM campanha INNER JOIN auto_resposta ON campanha_id = campanha.id  WHERE (campanha.status = 1 AND (campanha.inicio < NOW() AND campanha.fim > NOW() )  ) AND campanha.usuario_id = ".$id." AND  '".$mensagem."' = palavra_chave ");// (AND ( '".$mensagem."' LIKE CONCAT('%',palavra_chave) or AND '".$mensagem."' LIKE CONCAT(palavra_chave,'%') ) ");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['campanha_id']= $data['campanha_id'];
        $dado[$i]['resposta']= $data['resposta'];
        $dado[$i]['link']= $data['link'];
        $dado[$i]['arquivo']= $data['arquivo'];
        $dado[$i]['arquivo_nome'] =  $data['arquivo_nome'];

        $i+=1;
    }
    if($i === 0 ){
        return false;
    }
    return $dado;
}

function DB_SELECT(){
    global $conn;

    $i = 0;
    $sql = $conn->query(sprintf("SELECT * FROM `campanha` WHERE `campanha`.id NOT IN (SELECT campanha_id FROM auto_resposta ) AND usuario_id = %s", secure($_SESSION['user_id'], 'int') )) or _error("SQL_ERROR_THROWEN");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['titulo'] = $data['titulo'];
        $dado[$i]['resposta'] = $data['resposta'];
        $dado[$i]['link'] = $data['link'];
        $dado[$i]['arquivo'] = $data['arquivo'];
        $dado[$i]['inicio'] = $data['inicio'];
        $dado[$i]['status'] = $data['status'];
        $dado[$i]['impressoes'] = $data['impressoes'];
        $dado[$i]['usuario_id'] = $data['usuario_id'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}

function DB_SELECT_ID($id){
    global $conn;

    $sql = $conn->query(sprintf("SELECT * FROM `campanha` WHERE `campanha`.id NOT IN (SELECT campanha_id FROM auto_resposta ) AND usuario_id = %s AND id = %s ", secure($_SESSION['user_id'], 'int'), secure($id,'int') )) or _error("SQL_ERROR_THROWEN");
    while ($data = $sql->fetch_assoc()){
        $dado['id'] = $data['id'];
        $dado['titulo'] = $data['titulo'];
        $dado['resposta'] = $data['resposta'];
        $dado['link'] = $data['link'];
        $dado['arquivo'] = $data['arquivo'];
        $dado['arquivo_nome'] = $data['arquivo_nome'];
        $dado['inicio'] = $data['inicio'];
        $dado['status'] = $data['status'];
        $dado['impressoes'] = $data['impressoes'];
        $dado['usuario_id'] = $data['usuario_id'];
        $dado['delay'] = $data['delay'];
       return $dado;
    }
    return false;
}


//22/22/22
function aux_data($data){
    if($data == '2030-02-17 03:08:24' || $data =='2040-01-01 01:01:01'){
        return 'Permanente';
    }
    return $data;
}

function DB_SELECT_AUTOS(){
    global $conn;

    $i = 0;
    $sql = $conn->query(sprintf("SELECT *,palavra_chave , campanha_id FROM `campanha` INNER JOIN auto_resposta  ON `campanha_id` = `campanha`.id  AND usuario_id =  %s", secure($_SESSION['user_id'], 'int') )) or _error("SQL_ERROR_THROWEN");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['titulo'] = $data['titulo'];
        $dado[$i]['resposta'] = $data['resposta'];
        $dado[$i]['link'] = $data['link'];
        $dado[$i]['arquivo'] = $data['arquivo'];
        $dado[$i]['inicio'] = $data['inicio'];
//22/22/22
$dado[$i]['fim'] = aux_data($data['fim']);
        $dado[$i]['status'] = $data['status'];
        $dado[$i]['campanha_id'] = $data['campanha_id'];
        $dado[$i]['impressoes'] = $data['impressoes'];
        $dado[$i]['usuario_id'] = $data['usuario_id'];
        $dado[$i]['palavra_chave'] = $data['palavra_chave'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}

function DB_SELECT_AUTOS_ID($id){
    global $conn;

    $sql = $conn->query(sprintf("SELECT *,palavra_chave , campanha_id FROM `campanha` INNER JOIN auto_resposta  ON `campanha_id` = `campanha`.`id`  AND `usuario_id` = %s", secure($_SESSION['user_id'], 'int') )) or _error("SQL_ERROR_THROWEN");
    while ($data = $sql->fetch_assoc()){
        if($id == $data['id']){
        $dado['id'] = $data['id'];
        $dado['titulo'] = $data['titulo'];
        $dado['resposta'] = $data['resposta'];
        $dado['link'] = $data['link'];
        $dado['arquivo'] = $data['arquivo'];
        $dado['arquivo_nome'] = $data['arquivo_nome'];
        $dado['inicio'] = $data['inicio'];
        $dado['fim'] = $data['fim'];
        $dado['status'] = $data['status'];
        $dado['campanha_id'] = $data['campanha_id'];
        $dado['impressoes'] = $data['impressoes'];
        $dado['usuario_id'] = $data['usuario_id'];
        $dado['delay'] = $data['delay'];
        $dado['palavra_chave'] = $data['palavra_chave'];
        return $dado;
        }
    }
    return false;
}

function DB_GET_LISTA_TRANSMISSAO($id){
    global $conn;

    $i = 0;
    $sql = $conn->query(sprintf("SELECT * FROM `lista_trasmissao` WHERE usuario_id = %s", secure($id, 'int') )) or _error("SQL_ERROR_THROWEN");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['usuario_id'] = $data['usuario_id'];
        $dado[$i]['campanha_id'] = $data['campanha_id'];
        $dado[$i]['numero'] = $data['numero'];
        $dado[$i]['estado'] = $data['estado'];
        $dado[$i]['nome'] = $data['nome'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}

//so mostra os distintos
function DB_GET_LISTA_TRANSMISSAO_DIS($id){
    global $conn;

    $i = 0; 
    $sql = $conn->query("SELECT *,`lista_trasmissao`.id as listaid FROM `lista_trasmissao` INNER JOIN campanha ON campanha.id = lista_trasmissao.campanha_id WHERE lista_trasmissao.usuario_id = ".$id);//GROUP BY(`numero`)");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['listaid'];
        $dado[$i]['usuario_id'] = $data['usuario_id'];
        $dado[$i]['campanha_id'] = $data['campanha_id'];
        $dado[$i]['numero'] = $data['numero'];
        $dado[$i]['estado'] = $data['estado'];
        $dado[$i]['nome'] = $data['nome'];
        $dado[$i]['titulo'] = $data['titulo'];
        $dado[$i]['resposta'] = $data['resposta'];
        $dado[$i]['link'] = $data['link'];
        $dado[$i]['arquivo'] = $data['arquivo'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}

function DB_GET_USERS_CANTRASMIT(){
    global $conn;

    $i = 0;
    $sql = $conn->query("SELECT * FROM usuario WHERE limite_envio > 0");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['email'] = $data['email'];
        $dado[$i]['nome'] = $data['nome'];
        $dado[$i]['instancia'] = $data['instancia'];
        $dado[$i]['servidor_ip'] = $data['servidor_ip'];
        $dado[$i]['limite_envio'] = $data['limite_envio'];
        $dado[$i]['ultimo'] = $data['ultimo'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}

function DB_GET_USERS(){
    global $conn;
    $i = 0;
    $sql = $conn->query("SELECT * FROM usuario WHERE 1 ORDER BY nome ASC");
    while ($data = $sql->fetch_assoc()){
        $dado[$i]['id'] = $data['id'];
        $dado[$i]['email'] = $data['email'];
        $dado[$i]['nome'] = $data['nome'];
        $dado[$i]['instancia'] = $data['instancia'];
        $dado[$i]['servidor_ip'] = $data['servidor_ip'];
        $dado[$i]['limite_envio'] = $data['limite_envio'];
        $dado[$i]['ultimo'] = $data['ultimo'];
        $dado[$i]['foto'] = $data['foto'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}

function DB_GET_USERS_INSTANCIA($instancia){
    global $conn;
    $sql = $conn->query("SELECT * FROM usuario WHERE instancia='".$instancia."' ;");
    while ($data = $sql->fetch_assoc()){
        $dado['id'] = $data['id'];
        $dado['email'] = $data['email'];
        $dado['nome'] = $data['nome'];
        $dado['instancia'] = $data['instancia'];
        $dado['servidor_ip'] = $data['servidor_ip'];
        $dado['limite_envio'] = $data['limite_envio'];
        $dado['ultimo'] = $data['ultimo'];
        $dado['whatsapp'] = $data['whatsapp'];
        return $dado;
    }
    return false;
}


function DB_GET_USERS_ID($id){
    global $conn;
    $sql = $conn->query("SELECT * FROM usuario WHERE id=".$id." ;");
    while ($data = $sql->fetch_assoc()){
        $dado['id'] = $data['id'];
        $dado['email'] = $data['email'];
        $dado['nome'] = $data['nome'];
        $dado['instancia'] = $data['instancia'];
        $dado['servidor_ip'] = $data['servidor_ip'];
        $dado['limite_envio'] = $data['limite_envio'];
        $dado['ultimo'] = $data['ultimo'];
        $dado['whatsapp'] = $data['whatsapp'];
        return $dado;
    }
    return false;
}


function DB_GET_CAMPANHAS($id){
    global $conn;

    $i = 0;
    $sql = $conn->query(sprintf("SELECT campanha.*, lista_trasmissao.id as transmisao_id, lista_trasmissao.numero FROM lista_trasmissao INNER JOIN campanha ON lista_trasmissao.campanha_id = campanha.id  WHERE campanha.status = 1 AND campanha.inicio < NOW()  AND lista_trasmissao.estado = 0  AND lista_trasmissao.usuario_id =  %s ", secure($id, 'int') )) or _error("SQL_ERROR_THROWEN");
    while ($data = $sql->fetch_assoc()){

        $dado[$i]['id'] = $data['id'];
        $dado[$i]['titulo'] = $data['titulo'];
        $dado[$i]['resposta'] = $data['resposta'];
        $dado[$i]['link'] = $data['link'];
        $dado[$i]['arquivo'] = $data['arquivo'];
        $dado[$i]['inicio'] = $data['inicio'];
        $dado[$i]['status'] = $data['status'];
        $dado[$i]['impressoes'] = $data['impressoes'];
        $dado[$i]['usuario_id'] = $data['usuario_id'];
        $dado[$i]['transmisao_id'] = $data['transmisao_id'];
        $dado[$i]['numero'] = $data['numero'];
        $dado[$i]['delay'] = $data['delay'];
        $dado[$i]['arquivo_nome'] = $data['arquivo_nome'];
        $i+=1;
    }

    if($i === 0 ){
        return null;
    }
    return $dado;
}


function DB_GET_CAMPANHA($id){
    global $conn;
    $sql = $conn->query(sprintf("SELECT campanha.*, lista_trasmissao.id as transmisao_id, lista_trasmissao.numero FROM lista_trasmissao INNER JOIN campanha ON lista_trasmissao.campanha_id = campanha.id  WHERE campanha.status = 1 AND campanha.inicio < NOW()  AND lista_trasmissao.estado = 0  AND lista_trasmissao.usuario_id =  %s ", secure($id, 'int') )) or _error("SQL_ERROR_THROWEN");
    while ($data = $sql->fetch_assoc()){
        return $data;
    }
    return false;
}

function DB_UPDATE_CAMP($dados){
    global $conn;
  
  if(isset($dados['palavra_chave'])){
        $sql = $conn->query("UPDATE `auto_resposta` SET `palavra_chave`= '".$dados['palavra_chave']."' WHERE `campanha_id`= ".$dados['campanha_id']." ;");

    }
  

  

    $sql = $conn->query("UPDATE `campanha` SET `titulo`='".$dados['titulo']."' WHERE `id`=".$dados['campanha_id']." ;");
    $sql = $conn->query("UPDATE `campanha` SET `resposta`='".$dados['resposta']."' WHERE `id`=".$dados['campanha_id']." ;");
    $sql = $conn->query("UPDATE `campanha` SET `link`='".$dados['link']."' WHERE `id`=".$dados['campanha_id']." ;");
    $sql = $conn->query("UPDATE `campanha` SET `arquivo`='".$dados['arquivo']."' WHERE `id`=".$dados['campanha_id']." ;");
    $sql = $conn->query("UPDATE `campanha` SET `inicio`='".$dados['inicio']."' WHERE `id`=".$dados['campanha_id']." ;");
    $sql = $conn->query("UPDATE `campanha` SET `usuario_id`=".$_SESSION['user_id']." WHERE `id`=".$dados['campanha_id']." ;");
    $sql = $conn->query("UPDATE `campanha` SET `delay`=".$dados['delay']." WHERE `id`=".$dados['campanha_id']." ;");
    $sql = $conn->query("UPDATE `campanha` SET `arquivo_nome`='".$dados['arquivo_nome']."' WHERE `id`=".$dados['campanha_id']." ;");

    //22/22/22
    $sql = $conn->query("UPDATE `campanha` SET `fim`='".$dados['fim']."' WHERE `id`=".$dados['campanha_id']." ;");


    if(isset($dados['listadetransmisao'])){
        $sql = $conn->query("UPDATE `campanha` SET `lista_especial`='".$dados['listadetransmisao']."' WHERE `id`=".$dados['campanha_id']." ;");

        $camapanha_id = $dados['campanha_id'];
        $array=explode(",", $dados['listadetransmisao']);
        if(count($array)!=0) {
            foreach($array as $value) {
                if($value!="") {
                    $sql = $conn->query("INSERT INTO `lista_trasmissao` (`usuario_id`, `campanha_id`, `numero`, `estado`, `nome`) VALUES (" . $_SESSION['user_id'] . ", " .$camapanha_id. ", '" . $value . "', 0, '" . $value . "');");
                }
            }
        }


    }
    

    return true;
}

function DB_INSERT($dados){
    global $conn;

    if(isset($dados['listadetransmisao'])){
        $sql = $conn->query(sprintf("INSERT INTO `campanha`(`titulo`, `resposta`, `link`, `arquivo`, `inicio`, `status`, `impressoes`, `usuario_id`, `delay`,`arquivo_nome`,`lista_especial`) VALUES (%s, %s,%s, %s,%s,1, 0,%s,%s,%s,%s)", secure($dados['titulo'],'utf8') , secure($dados['resposta'],'utf8'), secure($dados['link']), secure($dados['arquivo']), secure($dados['inicio'],'datetime'), secure($_SESSION['user_id'], 'int') ,secure($dados['delay'], 'int'), secure($dados['arquivo_nome']), secure($dados['listadetransmisao']) )) or _error("SQL_ERROR_THROWEN");

      
        $camapanha_id = $conn->insert_id;
        $array=explode(",", $dados['listadetransmisao']);
        if(count($array)!=0) {
            foreach($array as $value) {
                if($value!="") {
                    $sql = $conn->query("INSERT INTO `lista_trasmissao` (`usuario_id`, `campanha_id`, `numero`, `estado`, `nome`) VALUES (" . $_SESSION['user_id'] . ", " .$camapanha_id. ", '" . $value . "', 0, '" . $value . "');");
                }
            }
        }


    }else{

    	//22/22/22
          $sql = $conn->query(sprintf("INSERT INTO `campanha`(`titulo`, `resposta`, `link`, `arquivo`, `inicio`,`fim` , `status`, `impressoes`, `usuario_id`, `delay`,`arquivo_nome`) VALUES (%s, %s,%s, %s,%s,%s, 1, 0,%s,%s,%s)", secure($dados['titulo'],'utf8') , secure($dados['resposta'],'utf8'), secure($dados['link']), secure($dados['arquivo']), secure($dados['inicio'],'datetime'), secure($dados['fim'],'datetime'), secure($_SESSION['user_id'], 'int') ,secure($dados['delay'], 'int'), secure($dados['arquivo_nome']) )) or _error("SQL_ERROR_THROWEN");
    
        //$sql = $conn->query(sprintf("INSERT INTO `campanha`(`titulo`, `resposta`, `link`, `arquivo`, `inicio`, `status`, `impressoes`, `usuario_id`, `delay`,`arquivo_nome`) VALUES (%s, %s,%s, %s,%s, 1, 0,%s,%s,%s)", secure($dados['titulo'],'utf8') , secure($dados['resposta'],'utf8'), secure($dados['link']), secure($dados['arquivo']), secure($dados['inicio'],'datetime'), secure($_SESSION['user_id'], 'int') ,secure($dados['delay'], 'int'), secure($dados['arquivo_nome']) )) or _error("SQL_ERROR_THROWEN");
    }

    if(isset($dados['palavra_chave'])){
        $sql = $conn->query(sprintf("INSERT INTO `auto_resposta`(`campanha_id`, `palavra_chave`) VALUES (%s ,%s)", secure($conn->insert_id, 'int'), secure($dados['palavra_chave'],'utf8') )) or _error("SQL_ERROR_THROWEN");
    }


    return true;
}

function DB_INSERT_TRANSMISAO($dados){
    global $conn;
    //$sql = $conn->query(sprintf("INSERT INTO `lista_trasmissao`(`usuario_id`, `campanha_id`, `numero`, `estado`,`nome`) SELECT %s,`id`, %s , 0 , %s FROM `campanha` WHERE  (`lista_especial` IS NULL) AND ( `campanha`.`id` NOT IN (SELECT auto_resposta.campanha_id FROM auto_resposta) ) AND     `usuario_id` = %s", secure($_SESSION['user_id'], 'int'), secure($dados['numero']), secure($dados['nome']),secure($_SESSION['user_id'], 'int') )) or _error("SQL_ERROR_THROWEN");

    return true;
}


function DB_PARAR_TRANSMISAO($user_id){
    global $conn;
    $sql = $conn->query(sprintf("UPDATE `lista_trasmissao` SET `estado`=1 WHERE `usuario_id` =  %s", secure($user_id, 'int') )) or _error("SQL_ERROR_THROWEN");
    return true;
}

function DB_DELETAR_CAMPANHA($id){
    global $conn;
    $sql = $conn->query(sprintf("DELETE FROM `campanha` WHERE `id` =  %s", secure($id, 'int') )) or _error("SQL_ERROR_THROWEN");
    return true;
}


function DB_DELETAR_AUTO_RESPOSTA($id,$camp_id){
    global $conn;
    $sql = $conn->query(sprintf("DELETE FROM `auto_resposta` WHERE `id` =  %s", secure($id, 'int') )) or _error("SQL_ERROR_THROWEN");
    $sql = $conn->query(sprintf("DELETE FROM `campanha` WHERE `id` =  %s", secure($camp_id, 'int') )) or _error("SQL_ERROR_THROWEN");
    return true;
}


function DB_UPDATE_LISTA_TRANSMISSAO($id){
    global $conn;  
    
    $sql = $conn->query(sprintf("SELECT `numero` FROM `lista_trasmissao` WHERE `id` = %s LIMIT 1 ;", secure($id, 'int')));
    $numero = "";
    while ($data = $sql->fetch_assoc()){
        $numero = $data['numero'];
    }
    $sql = $conn->query(sprintf("UPDATE `lista_trasmissao` SET `estado`= 1 WHERE `numero` = %s ",   secure($numero) )) or _error("SQL_ERROR_THROWEN");
    $sql =$conn->query("DELETE FROM `lista_trasmissao` WHERE `estado` = 1");
    return true;
}

/**GENERIC SECTION**/
function _error($erro){
    switch ($erro){
        case 404:{
            echo '404 <a href="error"> Meu Deus, o programador nao previu essa situacao e voce caiu no buraco negro! Por favor informe ao suporte</a>';
            exit;
        }break;
        default:{
            echo 'erro <a href="error"> Ocorreu um erro chato no banco de dados. Por favor,informe ao tecnico que ele nao previu essa situacao.</a>';
            exit;
        }break;

    }

}
function secure($value, $type = "", $quoted = true) {
    global $conn;
    if($value !== 'null') {
        // [1] Sanitize
        /* Convert all applicable characters to HTML entities */

        if($type != 'utf8'){
            $value = htmlentities($value, ENT_QUOTES, 'utf-8');
        }


        // [2] Safe SQL
        $value = $conn->real_escape_string($value);
        switch ($type) {
            case 'int':
                $value = ($quoted)? "'".intval($value)."'" : intval($value);
                break;
            case 'datetime':
                //$value = ($quoted)? "'".set_datetime($value)."'" : set_datetime($value);
                $value = (!is_empty($value))? "'".$value."'" : "''";
                break;
            case 'search':
                if($quoted) {
                    $value = (!is_empty($value))? "'%".$value."%'" : "''";
                } else {
                    $value = (!is_empty($value))? "'%%".$value."%%'" : "''";
                }
                break;
            default:
                $value = (!is_empty($value))? "'".$value."'" : "''";
                break;
        }
    }
    return $value;
}
function is_empty($value) {
    if(strlen(trim(preg_replace('/\xc2\xa0/',' ',$value))) == 0) {
        return true;
    } else {
        return false;
    }
}
function __($msg){
    echo $msg;
}


?>