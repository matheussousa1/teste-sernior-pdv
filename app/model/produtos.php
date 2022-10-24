<?php
	include_once ("dbconnect.php");
	include_once ("funcoes/criptaSenha.php");
	
	class ProdutosModel extends dbconnect{
		
		public $retorno;
		

		public function cadastrar($nome, $quantidade, $preco){

			$this->retorno = mysqli_query($this->con, "INSERT INTO `produtos`(`nome`, `quantidade`, `preco`, `dataCadastro`) VALUES ('$nome', '$quantidade', '$preco', NOW()) ") or die(mysqli_error($this->con));

			return $this->retorno;

		}

		public function buscar(){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM produtos ");

			return $this->retorno;

		}

		public function buscarDados($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM produtos WHERE id = $id");

			return $this->retorno;

		}	

		public function editar($id, $nome, $quantidade, $preco){

			$this->retorno = mysqli_query($this->con, "UPDATE `produtos` SET `nome`='$nome',`quantidade`='$quantidade',`preco`='$preco' WHERE id = $id");

			return $this->retorno;

		}

		public function deletar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `produtos` SET `ativo`= 0 WHERE id = $id");

			return $this->retorno;

		}

		public function ativar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `produtos` SET `ativo`= 1 WHERE id = $id");

			return $this->retorno;

		}


		
	}

?>