<script type="text/javascript">
$(document).ready( function () {

  $("#pesquisar").click(function(event) {
            
            $('#total').html("0,00");
            var total  = 0;

            
            var dataInicial = $('#dataInicial').val();
            var dataFinal = $('#dataFinal').val();
            var tipoPagamento = $('#tipoPagamento').val();
            
            if(dataInicial == ''){
                swal({   
                    title: "Preencher a Data Inicial",  
                    type: "error",   
                    showConfirmButton: true,
                });
                $('#dataInicial').focus();
                return false;
            }

            // destruir a tabela antiga
            $('#tabela').DataTable().clear().destroy();

            $('#carregando').removeClass('hide');
            $.ajax({
                url: '<?php echo AJAX."vendas.php?acao=buscar";?>',
                type: 'GET',
                dataType: 'json',
                data: {dataInicial: dataInicial, dataFinal: dataFinal, tipoPagamento: tipoPagamento},
            })
            .done(function(dados) {
                $('#carregando').addClass('hide');
                if (dados.length) {

                    $.each(dados, function(conta, valor) {
                        total = parseFloat(total) + parseFloat(valor.valorLimpo);
                    });

                    $('#total').html(total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
 
                    $('#tabela').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "responsive": true,
                        "autoWidth": false,
                        "pageLength": 50,
                        "processing": true,
                        lengthMenu: [
                            [ 10, 25, 50, -1 ],
                            [ '10 linhas', '25 linhas', '50 linhas', 'Mostrar todos' ]
                        ],
                        dom: 'Bfrtip',
                        buttons: [
                           { extend: 'pageLength'}, 
                           {
                            extend: 'collection',
                            text: 'Exportar',
                            buttons: [
                                'copy',
                                'excel',
                                'csv',
                                'pdf',
                                'print'
                            ]
                            },
                            { extend: 'colvis', text: 'Colunas'}, 
                        ],
                        "language": {
                          "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
                        },
                        "data": dados,
                        "createdRow": function ( row, data, index ) {
                            if(data['ativo'] == 0){
                              $(row).addClass('table-danger');
                            }
                        },
                        "columns": [
                            { "data": "id" },
                            { "data": "cliente" },
                            { "data": "produtos" },
                            { "data": "tipoPagamento" },
                            { "data": "valorTotal" },
                            { "data": "datVenda" },
                        ]
                      });
                }
            });
            return false;
        });

});  
</script>


<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Relatorio de Vendas</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <h5>Relatorio de vendas</h5>
                    </div>
                    <div class="card-body">
                      <div class="row m-b-30">
                        <div class="col-sm-12 col-xl-2">
                            <label>Data Inicial</label>
                            <input class="form-control" type="date" name="dataInicial" id="dataInicial">
                        </div>
                        <div class="col-sm-12 col-xl-2 ">
                            <label>Data Final</label>
                            <input class="form-control" type="date" name="dataFinal" id="dataFinal">
                        </div>
                        <div class="col-sm-12 col-xl-2 ">
                            <label>Tipo Pagamento</label>
                            <select class="form-control select2-single" name="tipoPagamento" id="tipoPagamento">
                                <option value="">Todos</option>
                                <option value="v">Á Vista</option>
                                <option value="c">Cartão Crédito</option>
                                <option value="d">Cartão Debito</option>
                                <option value="dp">Deposito</option>
                                <option value="ch">Cheque</option>
                                <option value="t">Transferencia</option>
                                <option value="bo">Boleto</option>
                                <option value="pix">Pix</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-xl-3">
                            <button type="button" class="btn btn-primary waves-effect waves-light m-t-30" id="pesquisar"><i class="fa fa-search"></i> Pesquisar</button>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                <table id="tabela" class="table table-striped table-hover display nowrap">
                                    <thead>
                                      <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Produtos</th>
                                        <th>Tipo Pagamento</th>
                                        <th>Valor Total</th>
                                        <th>Data Venda</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <!-- resultado -->
                                    </tbody>
                                    <tfoot>
                                      <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Produtos</th>
                                        <th>Tipo Pagamento</th>
                                        <th>Valor Total</th>
                                        <th>Data Venda</th>
                                      </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
