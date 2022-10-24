<?php
	include_once ("dbconnect.php");
	include_once ("funcoes/criptaSenha.php");
	
	class FormaPagamentoModel extends dbconnect{
		
		public $retorno;
		

		public function cadastrar($nome, $parcelas){

			$this->retorno = mysqli_query($this->con, "INSERT INTO `formapagamento`(`nome`, `parcelas`, `dataCadastro`) VALUES ('$nome', '$parcelas', NOW())") or die(mysqli_error($this->con));

			return $this->retorno;

		}

		public function buscar(){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM formapagamento ");

			return $this->retorno;

		}

		public function buscarDados($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM formapagamento WHERE id = $id");

			return $this->retorno;

		}	

		public function editar($id, $nome, $parcelas){

			$this->retorno = mysqli_query($this->con, "UPDATE `formapagamento` SET `nome`='$nome',`parcelas`='$parcelas' WHERE id = $id");

			return $this->retorno;

		}

		public function deletar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `formapagamento` SET `ativo`= 0 WHERE id = $id");

			return $this->retorno;

		}

		public function ativar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `formapagamento` SET `ativo`= 1 WHERE id = $id");

			return $this->retorno;

		}


		
	}

?>