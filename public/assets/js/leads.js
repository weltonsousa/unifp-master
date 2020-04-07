$("#add-lead").click(function(){
    $("#lead-panel").toggle("slow");
    $('.editar').show();
    $('.status-lead').hide();
    $('#action_lead').prop("value","adicionar");
    
});



$(".fechar").click(function(){
    $("#lead-panel").hide("slow");
    $("#lead-aluno-panel").hide("slow");
    
});


$(document).on('click', '.edit_lead', function(){
    var id = $(this).attr('data-id');
    $('#form_result').html('');
    $('.editar').hide();
    $('.status-lead').show();
    $('#action_lead').html('<i class="fa fa-check"></i> Salvar').prop("value","editar");
    
    $.ajax({
        url:"/editar_lead/"+id+"/edit",
        dataType:"json",
        success:function(html){
         $('#nome').val(html.data.nome);
         $('#email').val(html.data.email);
         $('#telefone').val(html.data.telefone);
         $('#id').val(html.data.id_lead);
         $("#lead-panel").toggle("slow");
        }
    });
    
});

$(document).on('click', '.edit_lead_aluno', function(){
    var id = $(this).attr('data-id');
    $('#form_result').html('');
    $('#action_lead_aluno').html('<i class="fa fa-check"></i> Encaminhar').prop("value","editar");
   
    $.ajax({
        url:"/editar_lead_aluno/"+id+"/edit",
        dataType:"json",
        success:function(html){
            $('#nome').val(html.data.pag_nome);
            $('#email').val(html.data.pag_email);
            $('#curso').val(html.data.pag_produto);
            $('#telefone').val(html.data.pag_telefone);
            $("#lead-aluno-panel").toggle("slow");
         
        }
    });
    
});


jQuery("input.telefone")
        .mask("(99) 99999-999?9")
        .focusout(function (event) {  
            var target, phone, element;  
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
            phone = target.value.replace(/\D/g, '');
            element = $(target);  
            element.unmask();  
            if(phone.length > 10) {  
                element.mask("(99) 99999-999?9");  
            } else {  
                element.mask("(99) 9999-9999?9");  
            }  
        });
$(function () {
    
    var table = $('#leads-externos').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/alunos_leads_externos",
        columns: [
            {data: 'nome', name: 'nome'},
            {data: 'email', name: 'email'},
            {data: 'telefone', name: 'telefone'},
            {data: 'curso', curso: 'curso'},
            {data: 'unidade', unidade: 'unidade'},
            {data: 'conheceu', conheceu: 'conheceu'},
            {data: 'data', data: 'created_at'},
            {data: 'situacao', situacao: 'situacao'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
            
        ],
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            "select": {
                "rows": {
                    "_": "Selecionado %d linhas",
                    "0": "Nenhuma linha selecionada",
                    "1": "Selecionado 1 linha"
                }
            }
        }
        
    });

    $(function () {
    
        var table = $('#leads').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/alunos_leads",
            columns: [
                {data: 'nome', name: 'nome'}, 
                {data: 'email', name: 'email'},
                {data: 'pag_telefone', name: 'pag_telefone'}, 
                {data: 'pag_produto', name: 'pag_produto'}, 
                {data: 'pag_tipo', name: 'pag_tipo'}, 
                {data: 'pag_data', name: 'pag_data'}, 
                {data: 'unidade_id', name: 'unidade_id'}, 
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "Selecionado %d linhas",
                        "0": "Nenhuma linha selecionada",
                        "1": "Selecionado 1 linha"
                    }
                }
            }
            
        });
    });

    $('#inserir-lead').on('submit', function(event){
        event.preventDefault();
        
        if($('#action_lead').val() == 'adicionar')
        {
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
            url:"http://localhost:8000/insert_lead",
            method:"POST",
            data: $("#inserir-lead").serialize(),
            dataType:"json",
            success:function(data)
            {
            var html = '';
            if(data.errors)
            {
    
            html = '<div class="panel panel-primary alerta" width="100%"><div class="panel-heading"> Opss Campos Obrigatorios </div> <div class="panel-body">';
            for(var count = 0; count < data.errors.length; count++)
            {
            html += '<p> * ' + data.errors[count] + '</p>';
            }
            html += '</div></div>';
            }
            if(data.success)
            {
            html = '<div class="panel panel-success sucesso" width="100%"><div class="panel-heading"> Sucesso </div> <div class="panel-body">' + data.success + '</div></div>';
            $('#inserir-lead')[0].reset();
            $('#leads-externos').DataTable().ajax.reload();
       
        }
            $('#form_result').html(html);
            setTimeout(function() { 
                $('#form_result').fadeOut("slow");
                $('#action_lead').html("<i class='fa fa-check'></i> Adicionar ").attr("disabled", false);
            }, 6000);
            $('#inserir-lead')[0].reset();
            $('#form_result').show("slow");
            $('#action_lead').html("processando...").attr("disabled", true);
            
           }
        //    final do if
        })
        }  /* editar */
        if($('#action_lead').val() == "editar")
        {
          
        $.ajax({
          url:"leads_externos/update",
          method:"POST",
          data:new FormData(this),
          contentType: false,
          cache: false,
          processData: false,
          dataType:"json",
          success:function(data)
          {
           var html = '';
           if(data.errors)
           {
            html = '<div class="panel panel-primary alerta" width="100%"><div class="panel-heading"> Opss Campos Obrigatorios </div> <div class="panel-body">';
            for(var count = 0; count < data.errors.length; count++)
            {
            html += '<p> * ' + data.errors[count] + '</p>';
            }
            html += '</div></div>';
           }
           if(data.success)
           {
            html = '<div class="panel panel-success sucesso" width="100%"><div class="panel-heading"> Sucesso </div> <div class="panel-body">' + data.success + '</div></div>';
            $('#leads-externos').DataTable().ajax.reload();
       
           }
            $('#form_result').html(html);
            setTimeout(function() { 
                $('#form_result').fadeOut("slow");
                $('#action_lead').html("<i class='fa fa-check'></i> Salvar ").attr("disabled", false);
            }, 6000);
             $('#form_result').show("slow");
             $('#action_lead').html("processando...").attr("disabled", true);

            }
         });
        }
       });
    });