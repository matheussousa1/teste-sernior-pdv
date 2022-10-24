<?php
	include_once ("dbconnect.php");
	include_once ("funcoes/criptaSenha.php");
	
	class PDVModel extends dbconnect{
		
		public $retorno;
		

		public function buscarProduto($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM produtos WHERE id = $id") or die(mysqli_error($this->con));

			return $this->retorno;

		}

		
	}

?>