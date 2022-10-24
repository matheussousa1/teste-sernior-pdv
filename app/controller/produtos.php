<?php
	include_once(MODEL.'produtos.php');
	
	class Produtos{
		
		public $view;
		public $nivel;
		public $resultado;
		
		public function gerenciar(){
			
			$this->view = "produtos/gerenciar";
			$this->nivel = 1;

			$model = new ProdutosModel;

		}
	}
?>