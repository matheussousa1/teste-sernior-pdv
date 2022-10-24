<?php
	include_once ("dbconnect.php");
	include_once ("funcoes/criptaSenha.php");
	
	class ClientesModel extends dbconnect{
		
		public $retorno;
		

		public function cadastrar($nome, $cpf, $email, $cep, $rua, $numero, $bairro, $cidade, $estado){

			$this->retorno = mysqli_query($this->con, "INSERT INTO `clientes`(`nome`, `cpf`, `cep`, `rua`, `numero`, `bairro`, `cidade`, `estado`, `email`, `dataCadastro`) VALUES ('$nome', '$cpf', '$cep', '$rua', '$numero', '$bairro', '$cidade', '$estado', '$email', NOW()) ") or die(mysqli_error($this->con));

			return $this->retorno;

		}

		public function buscar(){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM clientes ");

			return $this->retorno;

		}

		public function buscarDados($id){

			$this->retorno = mysqli_query($this->con, "SELECT * FROM clientes WHERE id = $id");

			return $this->retorno;

		}	

		public function editar($id, $nome, $cpf, $email, $cep, $rua, $numero, $bairro, $cidade, $estado){

			$this->retorno = mysqli_query($this->con, "UPDATE `clientes` SET `nome`='$nome',`cpf`='$cpf',`cep`='$cep',`rua`='$rua',`numero`='$numero',`bairro`='$bairro',`cidade`='$cidade',`estado`='$estado',`email`='$email' WHERE id = $id");

			return $this->retorno;

		}

		public function deletar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `clientes` SET `ativo`= 0 WHERE id = $id");

			return $this->retorno;

		}

		public function ativar($id){

			$this->retorno = mysqli_query($this->con, "UPDATE `clientes` SET `ativo`= 1 WHERE id = $id");

			return $this->retorno;

		}


		
	}

?>