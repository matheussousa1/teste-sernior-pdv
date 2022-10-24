<?php
	include_once(MODEL.'formapagamento.php');
	
	class FormaPagamento{
		
		public $view;
		public $nivel;
		public $resultado;
		
		public function gerenciar(){
			
			$this->view = "formapagamento/gerenciar";
			$this->nivel = 1;

			$model = new FormaPagamentoModel;

		}
	}
?>