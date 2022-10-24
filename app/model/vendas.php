<?php
	include_once ("dbconnect.php");
	include_once ("funcoes/criptaSenha.php");
	
	class VendasModel extends dbconnect{
		
		public $retorno;
		

		public function buscar(){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM clientes ");

			return $this->retorno;

		}


	}

?>