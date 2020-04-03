$("#add-lead").click(function(){
    $("#lead-panel").toggle("slow");
    
});

$(".fechar").click(function(){
    $("#lead-panel").hide("slow");
    
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
        ajax: "http://localhost:8000/alunos_leads_externos",
        columns: [
            {data: 'nome', name: 'nome'},
            {data: 'email', name: 'email'},
            {data: 'telefone', name: 'telefone'},
            {data: 'curso', curso: 'curso'},
            {data: 'unidade_id', curso: 'unidade_id'},
            {data: 'data', data: 'created_at'},
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

    $('#add_lead').click(function(){
        $('#action').val("Adicionar");
    });

    $('#inserir_lead').on('submit', function(event){
        event.preventDefault();
        
        if($('#action').val() == 'Adicionar')
        {
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
            url:"http://localhost:8000/inserir_lead",
            method:"POST",
            data: $("#inserir_lead").serialize(),
            dataType:"json",
            success:function(data)
            {
            var html = '';
            if(data.errors)
            {
            html = '<div class="alert alert-danger">';
            for(var count = 0; count < data.errors.length; count++)
            {
            html += '<p>' + data.errors[count] + '</p>';
            }
            html += '</div>';
            }
            if(data.success)
            {
            html = '<div class="alert alert-success">' + data.success + '</div>';
            $("#lead-panel").hide("slow");
            $('#inserir_lead').DataTable().ajax.reload();
        }
            $('#form_result').html(html);
            $('#form_result').show();
            }
        })
        }
       });
    });