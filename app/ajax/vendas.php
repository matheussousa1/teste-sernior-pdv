<?php 
include_once('../model/vendas.php');
$con = condb();

//for handle post action and perform operations 
if(isset($_GET['acao']) && $_GET['acao'] != ''){
    switch ($_GET['acao']) {
        case 'buscar':
        	buscar($con, $_GET);
        break;
    }
}

function buscar($con, $dados){


    $dataInicial = $dados['dataInicial'];
    $dataFinal = $dados['dataFinal'];
    $tipoPagamento = $dados['tipoPagamento'];


    if ($dataFinal == "") {
        $dataFinal = date("Y-m-d");
    }

    $sql = "SELECT *, v.id, c.nome FROM vendas as v left join clientes as c on v.idCliente = c.id inner join produtosvendas as pv on v.id = pv.idVenda WHERE v.ativo = 1 and v.dataVenda BETWEEN '$dataInicial' and '$dataFinal'  ";


	if ($tipoPagamento != '') {
		$sql .= " and v.formaPagamento = $tipoPagamento ";
	}


	$sql .= ' group by v.id order by v.dataCadastro asc ';

	$sqlVendas = mysqli_query($con, $sql);

    $dados = array();

    while ($vendas = mysqli_fetch_object($sqlVendas)) {


    	$arrayProdutos = '';
		$sqlProdutos = mysqli_query($con, "SELECT p.nome,  pv.valor, pv.qnt  FROM produtosvendas as pv inner join produtos as p on pv.idProduto = p.id WHERE pv.idVenda = $vendas->id and pv.ativo = 1 ");
		$mts3Total = 0;
		while ($produtos = mysqli_fetch_object($sqlProdutos)) {
			$mts3Total = $mts3Total + $produtos->qnt;
			$valorTotalProduto = $produtos->valor * $produtos->qnt;
			$arrayProdutos .= '<div class="b-1 border-secondary mt-1"><b>Produto: '.$produtos->nome.' <br> Valor: R$ '.number_format($produtos->valor,2,",",".").' <br> QNT: '.$produtos->qnt.' <br> Total: R$ '.number_format($valorTotalProduto,2,",",".").'</b></div>';
		}

        switch ($vendas->formaPagamento) {
            case 'v':
                $tipoPagamento = "<label class='label label-primary'>Á Vista</label>";
            break;
            case 'c':
                $tipoPagamento = "<label class='label label-primary'>Cartão Crédito</label>";
            break;
            case 'd':
                $tipoPagamento = "<label class='label label-primary'>Cartão Debito</label>";
            break;
            case 'dp':
                $tipoPagamento = "<label class='label label-primary'>Deposito</label>";
            break;
            case 'ch':
                $tipoPagamento = "<label class='label label-primary'>Cheque</label>";
            break;
            case 't':
                $tipoPagamento = "<label class='label label-primary'>Transferencia</label>";
            break;
            case 'ge':
                $tipoPagamento = "<label class='label label-primary'>Gateway</label>";
            break;
            case 'bo':
                $tipoPagamento = "<label class='label label-primary'>Boleto</label>";
            break;
            case 'pix':
                $tipoPagamento = "<label class='label label-primary'>Pix</label>";
            break;
            case 'ccr':
                $tipoPagamento = "<label class='label label-primary'>Cartão Recorrência</label>";
            break;
            default:
                $tipoPagamento = "";
            break;
        }

        $sqlCliente = mysqli_query($con, "SELECT * FROM clientes where id = $vendas->idCliente");
		$resCliente = mysqli_fetch_object($sqlCliente);
		$nomeCliente = $resCliente->nome;
        
        //titulares 
        $dados[] = array(
            'id' => $vendas->id,
		    'datVenda' => date("d/m/Y", strtotime($vendas->dataVenda)),
		    'cliente' => $nomeCliente,
		    'tipoPagamento' => $tipoPagamento,
		    'produtos' => $arrayProdutos,
		    'valorTotal' => $vendas->valorTotal,
		    'qntTotal' => $mts3Total,
		    'ativo' => $vendas->ativo,
        );
 

   }


    echo json_encode($dados);

}


?>