<?php
	include_once(MODEL.'vendas.php');
	
	class Vendas{
		
		public $view;
		public $nivel;
		public $resultado;
		
		public function gerenciar(){
			
			$this->view = "vendas/gerenciar";
			$this->nivel = 1;

		}
	}
?>