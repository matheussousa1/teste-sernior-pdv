<?php
	include_once(MODEL.'clientes.php');
	
	class Clientes{
		
		public $view;
		public $nivel;
		public $resultado;
		
		public function gerenciar(){
			
			$this->view = "clientes/gerenciar";
			$this->nivel = 1;

			$model = new ClientesModel;

			// $model->listarEmpresas();
			// $this->resultado[] = $model->retorno;

		}
	}
?>