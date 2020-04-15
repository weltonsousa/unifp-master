$("#add-aluno").click(function () {
    $("#matriculas-panel").toggle("slow");
    $('.status-lead').hide();
    $('#nome').removeAttr("readonly");
    $('#email').removeAttr("readonly");
    $('#curso').removeAttr("readonly");
    $('#telefone').removeAttr("readonly");
    $('#titulo').html("<i class='fa fa-user'></i> Adicionar Lead Externo");
    $('.unidade').show();
    $('#action_aluno').prop("value", "adicionar");
});

$(".fechar").click(function () {
    $("#matriculas-panel").hide("slow");
    $('.editar').show();
    $('#titulo').html("<i class='fa fa-user'></i> Adicionar Lead Externo");
    $('#action_aluno').prop("value", "adicionar");

});

$(document).on('click', '.encaminhar_aluno', function () {
    var id = $(this).attr('data-id');
    $('#form_result').html('');
    $('#action_aluno').html('<i class="fa fa-check"></i> Encaminhar').prop("value", "encaminhar");

    $.ajax({
        url: "/editar_lead_aluno/" + id + "/edit",
        dataType: "json",
        success: function (html) {
            $('#nome').val(html.data.pag_nome).prop("readonly", "true");
            $('#email').val(html.data.pag_email).prop("readonly", "true");;
            $('#curso').val(html.data.pag_produto).prop("readonly", "true");;
            $('#telefone').val(html.data.pag_telefone).prop("readonly", "true");;
            $('#id').val(html.data.pag_id).prop("readonly", "true");
            $('#titulo').html("<i class='fa fa-link'></i> Encaminhar Lead");
            $('.unidade').show();
            $('.info').hide();
            $("#matriculas-panel").toggle("slow");

        }
    });

});

$(document).on('click', '.edit_situacao', function () {
    var id = $(this).attr('data-id');
    $('#form_result').html('');
    $('#action_aluno').html('<i class="fa fa-check"></i> Editar').prop("value", "editar");

    $.ajax({
        url: "/editar_lead_aluno/" + id + "/edit",
        dataType: "json",
        success: function (html) {
            $('#nome').val(html.data.pag_nome).prop("readonly", "true");
            $('#email').val(html.data.pag_email).prop("readonly", "true");;
            $('#curso').val(html.data.pag_produto).prop("readonly", "true");;
            $('#telefone').val(html.data.pag_telefone).prop("readonly", "true");
            $('#id').val(html.data.pag_id).prop("readonly", "true");
            $('#titulo').html("<i class='fa fa-list'></i> Status Lead");
            $('.editar').hide();
            $(".status-lead").show();
            $(".conheceu").show();
            $("#matriculas-panel").toggle("slow");

        }
    });

});

$(function () {

    var table = $('#matriculados1').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/alunos_leads_externos",
        columns: [
            { data: 'pag_nome', name: 'pag_nome' },
            { data: 'pag_email', name: 'pag_email' },
            { data: 'pag_telefone', name: 'pag_telefone' },
            { data: 'pag_tipo', name: 'pag_tipo' },
            { data: 'pag_status', name: 'pag_status' },
            { data: 'pag_data', name: 'pag_data' },
            { data: 'pag_produto', name: 'pag_produto' },
            { data: 'situacao', name: 'situacao' },
            { data: 'contato', name: 'contato' },
            { data: 'unidade', name: 'unidade' },
            { data: 'und_destino', name: 'und_destino' },
            { data: 'action', name: 'action' }

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

$('#inserir-aluno').on('submit', function (event) {
    event.preventDefault();

    if ($('#action_aluno').val() == 'adicionar') {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "http://localhost:8000/insert_lead",
            method: "POST",
            data: $("#inserir-aluno").serialize(),
            dataType: "json",
            success: function (data) {
                var html = '';
                if (data.errors) {

                    html = '<div class="panel panel-primary alerta" width="100%"><div class="panel-heading"> Opss Campos Obrigatorios </div> <div class="panel-body">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p> * ' + data.errors[count] + '</p>';
                    }
                    html += '</div></div>';
                }
                if (data.success) {
                    html = '<div class="panel panel-success sucesso" width="100%"><div class="panel-heading"> Sucesso </div> <div class="panel-body">' + data.success + '</div></div>';
                    $('#inserir-aluno')[0].reset();
                    $('#matriculados').DataTable().ajax.reload();

                }
                $('#form_result').html(html);
                setTimeout(function () {
                    $('#form_result').fadeOut("slow");
                    $('#action_aluno').html("<i class='fa fa-check'></i> Adicionar ").attr("disabled", false);
                }, 6000);
                $('#inserir-aluno')[0].reset();
                $('#form_result').show("slow");
                $('#action_aluno').html("processando...").attr("disabled", true);

            }
            //    final do if
        })
    } if ($('#action_aluno').val() == "encaminhar") {

        $.ajax({
            url: "leads_externos/update",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (data) {
                var html = '';
                if (data.errors) {
                    html = '<div class="panel panel-primary alerta" width="100%"><div class="panel-heading"> Opss Campos Obrigatorios </div> <div class="panel-body">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p> * ' + data.errors[count] + '</p>';
                    }
                    html += '</div></div>';
                }
                if (data.success) {
                    html = '<div class="panel panel-success sucesso" width="100%"><div class="panel-heading"> Sucesso </div> <div class="panel-body">' + data.success + '</div></div>';
                    $('#matriculados').DataTable().ajax.reload();

                }
                $('#form_result').html(html);
                setTimeout(function () {
                    $('#form_result').fadeOut("slow");
                    $('#action_aluno').html("<i class='fa fa-check'></i> Encaminhar ").attr("disabled", false);
                }, 6000);
                $('#form_result').show("slow");
                $('#action_aluno').html("processando...").attr("disabled", true);

            }
        })
    } if ($('#action_aluno').val() == "editar") {

        $.ajax({
            url: "status_aluno/update",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (data) {
                var html = '';
                if (data.errors) {
                    html = '<div class="panel panel-primary alerta" width="100%"><div class="panel-heading"> Opss Campos Obrigatorios </div> <div class="panel-body">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p> * ' + data.errors[count] + '</p>';
                    }
                    html += '</div></div>';
                }
                if (data.success) {
                    html = '<div class="panel panel-success sucesso" width="100%"><div class="panel-heading"> Sucesso </div> <div class="panel-body">' + data.success + '</div></div>';
                    $('#matriculados').DataTable().ajax.reload();

                }
                $('#form_result').html(html);
                setTimeout(function () {
                    $('#form_result').fadeOut("slow");
                    $('#action_aluno').html("<i class='fa fa-check'></i> Encaminhar ").attr("disabled", false);
                }, 6000);
                $('#form_result').show("slow");
                $('#action_aluno').html("processando...").attr("disabled", true);

            }
        });
    }
});
