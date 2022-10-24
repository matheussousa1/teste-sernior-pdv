<?php 
include_once('../model/pdv.php');
$con = condb();

//for handle post action and perform operations 
if(isset($_GET['acao']) && $_GET['acao'] != ''){
    switch ($_GET['acao']) {
        case 'buscarProduto'://for like any post
            buscarProduto($con, $_GET);
        break;
        case 'salvarFicha'://for like any post
            salvarFicha($con, $_GET);
        break;
    }
}

function buscarProduto($con, $dados){

	$id = $dados['id'];

	$model = new PDVModel;

	$model->buscarProduto($id);

	
	$array = array();
	while($data = mysqli_fetch_object($model->retorno)){

		$array['id']= $data->id;
		$array['nome'] = $data->nome;
		$array['valor'] = $data->preco;
	
	}
	echo json_encode($array);
}


function salvarFicha($con, $dados){

	session_start();

	$idRef = $_SESSION['idSession'];
	$cliente = $dados['cliente'];
	$pagamento = $dados['pagamento'];


	//salvar venda
	$sqlVenda = mysqli_query($con, "INSERT INTO `vendas`(`idCliente`, `formaPagamento`,  `dataVenda`, `dataCadastro`, `idRef`) VALUES ('$cliente', '$pagamento', NOW(), NOW(), $idRef)");
	
	//recuperar o id da venda
	$recuperarId = mysqli_insert_id($con);

	// produtos venda
	$qnt = $dados['qnt'];
	$preco = $dados['preco'];
	$total = $dados['total'];
	$idProduto = $dados['idProduto'];

	$somaTotal = 0;
	$cont = count($idProduto);
	for ($i=0; $i < $cont; $i++) { 

		$somaTotal += $total[$i];

		$sqlProduto = mysqli_query($con, "INSERT INTO `produtosvendas`(`IdVenda`, `idProduto`, `valor`, `qnt`) VALUES ('$recuperarId', '$idProduto[$i]', '$preco[$i]', '$qnt[$i]')");
	}

	// salvar total da venda
	$sqlSalvarTotal = mysqli_query($con, "UPDATE `vendas` SET `valorTotal`= '$somaTotal' WHERE id = $recuperarId");


	$res = array();
	if($sqlSalvarTotal){
        $res['id'] = $recuperarId;
        $res['status'] = 200;
    }else{
        $res['status'] = 511;
    }

    echo json_encode($res);

}

?>