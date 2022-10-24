<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script type="text/javascript">

    var cont = 0;
    var listaProdutos = [];

    $(document).ready(function() {
        
        $('#valorRecebido').maskMoney({allowZero: true, thousands:'', decimal:'.'});

        $("#total").html('0,00');

        $('#listaProdutos').html('');
        var produtos = '';


        $('.produto').click(function(event) {
            var id = $(this).attr("id");
            preencher(id);
        });
        
        $("#pagamento").change(function(){
            $(".btpagar").prop('disabled', true);
            $(".btpagar").addClass('hide');
            $('#valortroco').val('');
            $('.troco').html('');
            
            if ($(this).val() != "") {
               var pagamento = $("#pagamento").val();
               if(pagamento == 'v'){
                    $('.avista').removeClass('hide');
                    $('.cartao').addClass('hide');
                    $('#valorRecebido').val('');
                    $('#codVeridicadorCartao').val('');
                    $('#parcela').val(null).trigger('change');
                    
               }else if(pagamento == 'c'){
                    $('.avista').addClass('hide');
                    $('.cartao').removeClass('hide');
                    $('#valorRecebido').val('');
                    $('#codVeridicadorCartao').val('');
                    $('#parcelas').val(null).trigger('change');
                   
               }else if(pagamento == 'd'){
                    $('#parcelas').val(null).trigger('change');
                    $('.avista').addClass('hide');
                    $('.cartao').removeClass('hide');
                    $('#valorRecebido').val('');
                    $('#codVeridicadorCartao').val('');
                    
               }else{
                    $('.avista').removeClass('hide');
                    $('.cartao').addClass('hide');
                    $('#valorRecebido').val('');
                    $('#codVeridicadorCartao').val('');
                    $('#parcela').val(null).trigger('change');
                    
               }
            }else{
                $('.avista').addClass('hide');
                $('.cartao').addClass('hide');
                $(".btpagar").prop('disabled', true);
                $(".btpagar").addClass('hide');
            }
        });


        $('#formCadastrar').validate({
            rules: {
              cliente : { required: true},
              pagamento : { required: true},
            },
            messages: {
              cliente : { required: 'Preencha este campo' },
              pagamento : { required: 'Preencha este campo' },
            },
            submitHandler: function( form ){
              $('#carregando').removeClass('hide');

              var cliente =  $('#cliente').val();
              var pagamento =  $('#pagamento').val();

              var qnt = [];            
              $("input[name=qnt]").each(function(){
                  qnt.push($(this).val());
              });
              
              var preco = [];            
              $("input[name=preco]").each(function(){
                  preco.push($(this).val());
              });
              
              var total = [];            
              $("input[name=total]").each(function(){
                  total.push($(this).val());
              });
              
              var idProduto = [];            
              $("input[name=idProduto]").each(function(){
                  idProduto.push($(this).val());
              });
              
              $.ajax({
                type: "GET",
                url: "<?php echo AJAX; ?>pdv.php",
                data: {acao: 'salvarFicha', cliente: cliente, pagamento: pagamento, qnt: qnt, preco: preco, total: total, idProduto: idProduto},
                dataType: 'json',
                success: function(res) {
                  $('#carregando').addClass('hide');
                  if(res.status == 200){
                    
                    // window.open("<?php echo SITE; ?>pdv/imprimircupom/"+res.id, "minhaJanela", "height=1000,width=1000");

                   swal({   
                      title: "Cadastrado com Sucesso",  
                      type: "success",   
                      showConfirmButton: false,
                    });
                     window.setTimeout(function(){
                       $('#formCadastrar input').val(""); 
                       swal.close();
                       location.reload();
                    } ,1500);
                }else{
                  swal({   
                      title: "Error",  
                      type: "error",   
                      showConfirmButton: false,
                       });
                     window.setTimeout(function(){
                         swal.close();
                    } ,2500);
                }
            }
              });
              return false;
            }
      });

    });

    function preencher(id){
      $('#carregando').removeClass('hide');
      $.ajax({
        url: '<?php echo AJAX; ?>pdv.php?acao=buscarProduto',
        type: 'GET',
        dataType: 'json',
        data: {id: id},
      })
      .done(function(dados) {
        cont++;
        listaProdutos.push(dados.id);

      var textproduto = '<div class="row mt-4" id="p'+cont+'" >'+
      '<div class="col-md-12 mt-4">'+
          '<h3><b>'+dados.nome.toUpperCase()+'</b></h3>'+
        '</div>'+
        '<div class="col-md-3 mt-10">'+
          '<label><b>Quantidade</b></label>'+
          '<input type="text" name="qnt" id="qnt'+cont+'" class="form-control valor qnt" value="1.00" required="required" data-cont="'+cont+'">'+
        '</div>'+
        '<div class="col-md-3 mt-10 ">'+
          '<label><b>Preço Unitario</b></label>'+
          '<input type="text" name="preco" id="preco'+cont+'" class="form-control valor valorProduto" value="'+dados.valor+'" required="required" data-cont="'+cont+'">'+
        '</div>'+
        '<div class="col-md-3 mt-10 ">'+
          '<label><b>Preço Total</b></label>'+
          '<input type="text" name="total" id="total'+cont+'" class="form-control valor total" value="'+dados.valor+'" required="required" disabled>'+
        '</div>'+
        '<div class="col-md-3 mt-4">'+
          '<button class="btn btn-danger btn-xs" onclick="remover('+cont+','+dados.id+')">Remover</button>'+
        '</div>'+
        '<input type="hidden" name="idProduto" value="'+dados.id+'" />'+
      '</div>';
       
        
        $('#listaProdutos').prepend(textproduto);      


        $(".valor").maskMoney({thousands:'', decimal:'.', symbolStay: true});

        valorTotal();

        $('.qnt').keyup(function(){ 
          var qnt = $(this).val();
          var contAtual = $(this).attr('data-cont');
          var valor = $('#preco'+contAtual).val();
          var calcular = valor * qnt;
          $('#total'+contAtual).val(parseFloat(calcular));
          // total = parseFloat(total) - parseFloat(valor);
          valorTotal();
        });

        $('.valorProduto').keyup(function(){ 
          var valor = $(this).val();
          var contAtual = $(this).attr('data-cont');
          var qnt = $('#qnt'+contAtual).val();
          var calcular = valor * qnt;
          $('#total'+contAtual).val(parseFloat(calcular));
          valorTotal();
        });

        // termina
        $('#carregando').addClass('hide');
      });
    }

    function valorTotal(){
      
      var total = 0;

      $('.total').each(function(){
        var valorTotal = $(this).val();
        total = parseFloat(total) + parseFloat(valorTotal);
      });

      $('#total').html(numberFormat(total.toFixed(2)));

    }

    function remover(id, idProduto) {

      //removar o beneficio da lsita de array
      listaProdutos.splice((listaProdutos.indexOf(idProduto.toString())), 1);
      $('#p'+id).remove();
      valorTotal();
      // console.log(listaProdutos);
    }

    function numberFormat(n) {
      var parts=n.toString().split(".");
      return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".") + (parts[1] ? "," + parts[1] : "");
    }

</script>
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">PDV</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <form role="form" id="formCadastrar">
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-6">
                <div class="card">

                    <div class="card-header">
                        <h5>Produtos</h5>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="espprodutos">
                        <div class="row">
                            <div class="col-md-12">
                                <?php 
                                    $sqlProdutos = mysqli_query($con, "SELECT * FROM produtos WHERE ativo = 1 order by nome asc");
                                    while ($produto = mysqli_fetch_object($sqlProdutos)):
                                  ?>
                                    <button type="button" class="btn btn-primary btn-lg btn-block mt-15 text-uppercase produto" id="<?php echo $produto->id; ?>" data-preco="<?php echo $produto->preco; ?>">
                                        <i class="fa fa-barcode"></i> 
                                        <?php echo $produto->nome; ?><br>
                                        R$ <?php echo number_format($produto->preco,2,",","."); ?>
                                    </button>
                                  <?php 
                                    endwhile;
                                  ?>
                            </div>
                        </div>
                        <hr>
                        <div id="listaProdutos">
                          
                        </div>
                        
                    </div>
                </div>
            </div>


            <div class="col-sm-6">
                <div class="card">

                    <div class="card-header">
                        <h5>Dados do Cliente  / Pagamento</h5>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12  ">
                                <h3>Valor Total: <b>R$ <span id="total" style="color:red;"></span></b></h3>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b>Cliente</b></label>
                                    <select name="cliente" id="cliente" class="form-control select select2" required="required">
                                      <option value="">Selecione um Cliente</option>
                                      <?php 
                                        $sqlCliente = mysqli_query($con, "SELECT * FROM clientes WHERE ativo = 1");
                                        while ($cliente = mysqli_fetch_object($sqlCliente)):
                                      ?>
                                        <option value="<?php echo $cliente->id; ?>"><?php echo $cliente->id.' - '.$cliente->nome.' - '.$cliente->cpf; ?></option>
                                      <?php 
                                        endwhile;
                                      ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b>Forma Pagamento</b></label>
                                    <select name="pagamento" id="pagamento" class="form-control select2-single" required="required">
                                        <option value="">Selecione um Metodo</option>
                                        <option value="v">À Vista</option>
                                        <option value="c">Cartão Crédito</option>
                                        <option value="d">Cartão Débito</option>
                                        <option value="pix">Pix</option>
                                        <option value="dp">Deposito</option>
                                        <option value="t">Transferencia</option>
                                        <option value="bo">Boleto</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- caso for cartão -->
                                <div class="form-group cartao hide">
                                    <label class="f-18"><b>Quantidade Parcelas</b></label>
                                    <select name="parcelas" id="parcelas" class="form-control select2-single">
                                        <option value="">Selecione a quantidade</option>
                                        <?php
                                            for ($i=1; $i <= 12; $i++) { 
                                                echo '<option value="'.$i.'">'.$i.'x</option>';
                                            }
                                        ?> 
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg" id="salvar"><i class="fa fa-floppy-o"></i> SALVAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</div>