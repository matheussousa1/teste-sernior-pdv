<?php 
include_once('../model/clientes.php');
$con = condb();

//for handle post action and perform operations 
if(isset($_GET['acao']) && $_GET['acao'] != ''){
    switch ($_GET['acao']) {
        case 'cadastrar'://for like any post
            cadastrar($con, $_GET);
        break;
        case 'buscar':
        	buscar($con, $_GET);
        break;
        case 'buscarDados':
        	buscarDados($con, $_GET);
        break;
        case 'editar':
        	editar($con, $_GET);
        break;
        case 'deletar':
        	deletar($con, $_GET);
        break;
        case 'ativar':
        	ativar($con, $_GET);
        break;
    }
}

function cadastrar($con, $dados){

	$model = new ClientesModel;

	$nome = $dados['nome'];
	$cpf = $dados['cpf'];
	$email = $dados['email'];
	$cep = $dados['cep'];
	$rua = $dados['rua'];
	$numero = $dados['numero'];
	$bairro = $dados['bairro'];
	$cidade = $dados['cidade'];
	$estado = $dados['estado'];

	$model->cadastrar($nome, $cpf, $email, $cep, $rua, $numero, $bairro, $cidade, $estado);

	$res = array();
	if($model->retorno){
        $res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}



function buscar($con){

	$model = new ClientesModel;

	$model->buscar();

	$data = array();
	while($res = mysqli_fetch_object($model->retorno)) {

		if($res->ativo == 1){
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-danger btndel " data-toggle="tooltip" data-placement="top" title="Inativar"><i class="fas fa-trash-alt"></i></button>';
		}else{
			$status = '<button type="button" id_user="'.$res->id.'" nome_user="'.$res->nome.'" class="btn  btn-success btnativar " data-toggle="tooltip" data-placement="top" title="Ativar"><i class="fas fa-check"></i></button>';
		}

		$button = '<button type="button" id_user="'.$res->id.'" class="btn  btn-info btnedit mr-2" data-toggle="tooltip" data-placement="top" title="Alterar Dados"><i class="fas fa-edit"></i></button>';

		$button .= $status;

		$data['data'][] = array(
			'id' => $res->id,
			'nome' => $res->nome,
			'email' => $res->email,
			'cpf' => $res->cpf,
			'endereco' => $res->rua.', '.$res->numero.', '.$res->bairro.', '.$res->cidade.', '.$res->estado.', '.$res->cep,
			'dataCadastro' => date("d/m/Y H:i:s", strtotime($res->dataCadastro)),
			'ativo' => $res->ativo,
			'button' => $button,
		);
	}
	echo json_encode($data);
}

function buscarDados($con, $dados){

	$id = $dados['id'];

	$model = new ClientesModel;

	$model->buscarDados($id);

	$array = array();
	while($res = mysqli_fetch_object($model->retorno)){
		$array['id']= $res->id;
		$array['nome']= $res->nome;
		$array['cpf']= $res->cpf;
		$array['cep']= $res->cep;
		$array['rua']= $res->rua;
		$array['numero']= $res->numero;
		$array['bairro']= $res->bairro;
		$array['cidade']= $res->cidade;
		$array['estado']= $res->estado;
		$array['email']= $res->email;

	}
	echo json_encode($array);
}

function editar($con, $dados){

	$model = new ClientesModel;

	$id = $dados['idObj'];
	$nome = $dados['nome'];
	$cpf = $dados['cpf'];
	$email = $dados['email'];
	$cep = $dados['cep'];
	$rua = $dados['rua'];
	$numero = $dados['numero'];
	$bairro = $dados['bairro'];
	$cidade = $dados['cidade'];
	$estado = $dados['estado'];

	$model->editar($id, $nome, $cpf, $email, $cep, $rua, $numero, $bairro, $cidade, $estado);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

function deletar($con, $dados){

	$model = new ClientesModel;

	$id = $dados['id'];

	$model->deletar($id);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}

function ativar($con, $dados){

	$model = new ClientesModel;

	$id = $dados['id'];

	$model->ativar($id);

	$res = array();
	if($model->retorno){
		$res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);
}


?>