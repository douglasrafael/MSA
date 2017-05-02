$(document).ready(function () {

    /**
     * Ativa popover/tooltips
     */
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();

    /**
     * Eventos do modal
     */
    // Evento show do modal
    $('#disciplina-modal').on('shown.bs.modal', function () {
        $('#nomeDisciplina').focus();
    });

    // Evento show do modal
    $('#aluno-modal').on('shown.bs.modal', function () {
        if ($("#mat").val() !== '') {
            $('#nomeAluno').focus();
        } else {
            $('#matriculaAluno').focus();
        }
    });

    /**
     * Captura o evento de submit do formulário curso.
     * 
     * @param {event} e
     */
    $('#form-aluno').submit(function (e) {
        var form = this;
        var btSubmit = $('#form-aluno :submit'); // O botão de submit

        // Pega a ação do form que pode ser: salvar ou atualizar
        var action = ($("#form-aluno input[name=mat]").val() === "") ? 'insert' : 'update';

        sendForm(form, btSubmit, action);
        e.preventDefault(); // Anula o envio convencional
    });

    /**
     * Captura o evento de submit do formulário disciplina.
     * 
     * @param {event} e
     */
    $('#form-disciplina').submit(function (e) {
        var form = this;
        var btSubmit = $('#form-disciplina :submit'); // O botão de submit

        // Pega a ação do form que pode ser: salvar ou atualizar
        var action = ($("#form-disciplina input[name=id]").val() === "") ? 'insert' : 'update';

        sendForm(form, btSubmit, action);
        e.preventDefault(); // Anula o envio convencional
    });

    /**
     * Captura o evento de submit do formulário disciplina.
     * 
     * @param {event} e
     */
    $('#form-atividade').submit(function (e) {
        var form = this;
        var btSubmit = $('#form-atividade :submit'); // O botão de submit

        // Pega a ação do form que pode ser: salvar ou atualizar
        var action = ($("#form-atividade input[name=id]").val() === "") ? 'insert' : 'update';

        sendForm(form, btSubmit, action);
        e.preventDefault(); // Anula o envio convencional
    });

    /**
     * Captura o evento de submit do formulário de busca.
     * 
     * @param {event} e
     */
    $('#form-search').submit(function (e) {
        e.preventDefault(); // Anula o envio convencional

        $.post('controller/AtividadeController.php', {action: 'search', term: $('input[name=term]').val()}, function (data) {
            listarAtividades($.parseJSON(data));
        });

        return false;
    });
});


/**
 * Submete o formulário de acordo com os paramêtros.
 * Pode-se atulizar para cadastrar e atualizar.
 * 
 * @param {object} form - O formulário a ser submetido.
 * @param {object} btnSubmit - O botão clicado para submeter o form.
 * @param {string} action - Tipo da ação: insert | update
 * @returns {undefined}
 */
function sendForm(form, btnSubmit, action) {
    // Não envia o formulário se já tiver algum envio
    var form_send = false;

    if (!form_send) {
        var formData = new FormData(form);
        formData.append('action', action);

        // Envia os dados com Ajax
        $.ajax({
            url: $(form).attr('action'), // Captura a URL de envio do form
            type: $(form).attr('method'), // Captura o método de envio do form
            data: formData, // Os dados do form
            processData: false, // Não processa os dados
            cache: false, // Não faz cache
            contentType: false, // Não checa o tipo de conteúdo
            beforeSend: function () { // Antes do envio
                form_send = true; // Configura a variável enviando
                btnSubmit.button('loading'); // loading do botão submit
            },
            complete: function () {
                btnSubmit.button('reset');  // reseta o loading do botão submit
                form_send = false;
            },
            success: function (result) {
                // verifica se foi tudo ok, afim de resetar o form
                if (result.search('alert-success') !== -1) {
                    $(form)[0].reset(); // limpa os campos do form
                    $(form).find('input:first').focus(); // seta o focus para o primeiro input

                    if ($(form).attr('id') !== 'form-atividade') {
                        refresh();
                    } else {
                        totalInputs = 1;
                        defaultFormAtividade();
                    }
                }
                showMessage(result, '#message-' + $(form).attr('id'));
            }
        });
    }
}

/**
 * Carrega mensagem que deverá está no formato alert do bootstrap.
 * Aplica evento click para remover.
 * 
 * @param {string} message - Mensagem exibida ao usuário.
 * @param {string} seletor - #id ou .class.
 * @returns {void}
 */
function showMessage(message, seletor) {
    $(seletor).prepend(message);

    $(".alert").addClass("animated");
    $(".alert").addClass("bounce");
    $(".alert").addClass("alert-show");

    setTimeout(function () {
        $('.alert-show').fadeOut("slow");
    }, 6000);

    $(".alert-show").click(function () {
        $(this).fadeOut("slow");
    });
    
    $('html,body').animate({ scrollTop: 0 }, 'slow');
}

/**
 * Faz consuulta no banco pelos alunos e lista na div #list-content
 * 
 * @returns {undefined}
 */
function listarAlunos() {
    $('body').loadie(0);
    $.post('controller/AlunoController.php', {action: 'list'}, function (data) {
        if (data.message !== undefined) { // Houve um messagem de retorno
            showMessage(data.message, '#message-aluno');
        } else {
            var result;
            $("#list-content").empty();

            $.each(data, function (key, aluno) {
                result = '<button href="#" class="list-group-item" data-toggle="collapse" id="' + aluno.matricula + '" data-target="#_' + aluno.matricula + '" data-parent="#content-list-group">';
                result += '<div class="row"><div class="col-md-8"><h4 class="list-group-item-heading">' + aluno.nome + '</h4><small><b>Matricula:</b> ' + aluno.matricula + '<br/><b>Data de Nascimento:</b> ' + date_to_br(aluno.dataNascimento) + '</small></div>';
                result += '<div class="col-md-4"><div class="box-menu-actions"><span onclick="removerAluno(' + aluno.matricula + ')" class="glyphicon glyphicon-trash pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Remover aluno"></span>';
                result += '<span onclick="editarAluno(' + aluno.matricula + ')" class="glyphicon glyphicon-pencil pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Editar dados do aluno"></span></div>';
                result += '<span class="label label-default pull-right margin-right-10">' + aluno.listaDeAtividades.length + ' atividade(s)</span></div></div></button>';

                // Atividades
                if (!$.isEmptyObject(aluno.listaDeAtividades)) {
                    result += '<div id="_' + aluno.matricula + '" class="sublinks collapse">';
                    var descricao = "";
                    $.each(aluno.listaDeAtividades, function (i, atividade) {
                        if (atividade.descricao !== "") {
                            descricao = (atividade.descricao.length > 90) ? (atividade.descricao).substr(0, 90) + '...' : atividade.descricao;
                        }
                        result += '<a class="list-group-item small"><div class="row"><div class="col-md-10"><b>' + atividade.titulo + '</b><br/>' + descricao + '<br><b>Disciplina: </b><button type="button" class="btn btn-link" onclick="window.location.href=\'disciplinas.php#' + atividade.disciplina.id + '\'">' + atividade.disciplina.nome + '</button></div>';
                        result += '<div class="col-md-2"><div class="box-sub-menu-actions"><span onclick="window.location.href=\'index.php#' + atividade.id + '\'" class="glyphicon glyphicon-eye-open pull-right"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Visualizar atividade"></span></div>';
                        result += '<span class="label label-warning small pull-right margin-top-05" data-toggle="tooltip" data-placement="left" title="" data-original-title="Data limite para entrega">' + date_to_br(atividade.dataEntrega) + '</span></div></div></a>';
                    });
                    result += '</div>';
                }
                // add o conteudo na dentro da div
                $("#list-content").append(result);
            });

            // Ativa o tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // add evento click para destar o item selecionado
            $('button.list-group-item').click(function () {
                $(".list-group button").removeClass("active");
                $(this).toggleClass('active');
            });

            // Verifica se é para selecionar um item na lista de acordo com a url
            selecionaItem();
        }
        $('body').loadie(1);
    }, 'json');
}

/**
 * Faz consuulta no banco pelas disciplinas e lista na div #list-content
 * 
 * @returns {undefined}
 */
function listarDisciplinas() {
    $('body').loadie(0);
    $.post('controller/DisciplinaController.php', {action: 'list'}, function (data) {
        if (data.message !== undefined) { // Houve um messagem de retorno
            showMessage(data.message, '#message-disciplina');
        } else {
            var result;
            $("#list-content").empty();

            $.each(data, function (key, disciplina) {
                result = '<button href="#" class="list-group-item" data-toggle="collapse" id="' + disciplina.id + '" data-target="#_' + disciplina.id + '" data-parent="#content-list-group">';
                result += '<div class="row"><div class="col-md-8"><h4 class="list-group-item-heading">' + disciplina.nome + '</h4><small><b>Carga Horária:</b> ' + disciplina.cargaHoraria + ' horas</small></div>';
                result += '<div class="col-md-4"><div class="box-menu-actions"><span onclick="removerDisciplina(' + disciplina.id + ')" class="glyphicon glyphicon-trash pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Remover disciplina"></span>';
                result += '<span onclick="editarDisciplina(' + disciplina.id + ')" class="glyphicon glyphicon-pencil pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Editar dados da disciplina"></span></div>';
                result += '<span class="label label-default pull-right margin-right-10">' + disciplina.listaDeAtividades.length + ' atividade(s)</span></div></div></button>';

                // Atividades
                if (!$.isEmptyObject(disciplina.listaDeAtividades)) {
                    result += '<div id="_' + disciplina.id + '" class="sublinks collapse">';
                    var descricao = "";
                    $.each(disciplina.listaDeAtividades, function (i, atividade) {
                        if (atividade.descricao !== "") {
                            descricao = (atividade.descricao.length > 90) ? (atividade.descricao).substr(0, 90) + '...' : atividade.descricao;
                        }
                        result += '<a class="list-group-item small"><div class="row"><div class="col-md-10"><b>' + atividade.titulo + '</b><br/>' + descricao + '<br><b>Aluno: </b><button type="button" class="btn btn-link" onclick="window.location.href=\'alunos.php#' + atividade.aluno.matricula + '\'">' + atividade.aluno.nome + '</button></div>';
                        result += '<div class="col-md-2"><div class="box-sub-menu-actions"><span onclick="window.location.href=\'index.php#' + atividade.id + '\'" class="glyphicon glyphicon-eye-open pull-right"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Visualizar atividade"></span></div>';
                        result += '<span class="label label-warning small pull-right margin-top-05" data-toggle="tooltip" data-placement="left" title="" data-original-title="Data limite para entrega">' + date_to_br(atividade.dataEntrega) + '</span></div></div></a>';
                    });
                    result += '</div>';
                }
                // add o conteudo na dentro da div
                $("#list-content").append(result);
            });

            // Ativa o tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // add evento click para destar o item selecionado
            $('button.list-group-item').click(function () {
                $(".list-group button").removeClass("active");
                $(this).toggleClass('active');
            });

            // Verifica se é para selecionar um item na lista de acordo com a url
            selecionaItem();
        }
        $('body').loadie(1);
    }, 'json');
}

/**
 * Faz consuulta no banco pelas disciplinas e lista na div #list-content
 * 
 * Se for passado dados como paramentro o mesmo é usando para montar a na view, caso contrário será feito a busca no back-end pelos dados
 * 
 * @param {json} dados
 * @returns {undefined}
 */
function listarAtividades(dados) {
    $('body').loadie(0);

    if (dados === null) {
        $.post('controller/AtividadeController.php', {action: 'list'}, function (data) {
            populaAtividades(data);
        }, 'json');
    } else {
        populaAtividades(dados);
    }
    $('body').loadie(1);
}

/**
 * Popula tela com lista de atividades
 * 
 * @param {json} data
 * @returns {undefined}
 */
function populaAtividades(data) {
    $("#list-content").empty();
    $("#title-header").html('<h1>Lista de Atividades<a href="relatorios/atividades_print.php" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gerar PDF da lista de ativiades..."><span class="glyphicon glyphicon-print"></span></a></h1>');
    
    if (data !== null && data.message === undefined) {
        $.each(data, function (key, atividade) {
            var result = '<button  href="#" class="list-group-item " id="' + atividade.id + '"><div class="pull-right box-menu-actions">';
            result += '<span onclick="removerAtividade(' + atividade.id + ')" class="glyphicon glyphicon-trash pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Remover atividade"></span>';
            result += '<span onclick="window.location.href=\'cadastrar-atividade.php?id=' + atividade.id + '\'" class="glyphicon glyphicon-pencil pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Editar dados da atividade"></span></div>';
            result += '<div class="pull-right"><span class="label label-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Disciplina: ' + atividade.disciplina.nome + '">' + ((atividade.disciplina.nome.length > 20) ? (atividade.disciplina.nome).substr(0, 20) + '...' : atividade.disciplina.nome) + '</span>';
            result += '<span class="label label-warning margin-left-05" data-toggle="tooltip" data-placement="top" title="" data-original-title="Data limite para entrega">' + date_to_br(atividade.dataEntrega) + '</span></div>';
            result += '<h4 class="list-group-item-heading">' + atividade.titulo + '</h4><p class="list-group-item-text">' + (atividade.descricao !== null ? atividade.descricao : '') + '</p>';
            result += '<div class="margin-top-05"><div><strong>Aluno(a):  </strong><a href="alunos.php#' + atividade.aluno.matricula + '">' + atividade.aluno.nome + '</a></div>';
            result += '<div class="pull-left box-documentos"><strong>Documento(s): </strong><br/>';

            // Documentos
            if (!$.isEmptyObject(atividade.listaDeDocumentos)) {
                $.each(atividade.listaDeDocumentos, function (i, documento) {
                    result += '<a href="upload/docs/' + documento.endereco + '" target="_blank" class="label label-info margin-right-05" data-toggle="tooltip" data-placement="top" title="" data-original-title="Baixar documento">' + documento.titulo + '</a>';
                });
            }
            result += '</div><div class="pull-right"><small>Cadastrado em ' + datetime_to_br(atividade.dataCadastro) + '</small></div></div></button>';

            // add o conteudo na dentro da div
            $("#list-content").append(result);
        });

        // Ativa o tooltip
        $('[data-toggle="tooltip"]').tooltip();

        // add evento click para destar o item selecionado
        $('button.list-group-item').click(function () {
            $(".list-group button").removeClass("active");
            $(this).toggleClass('active');
        });

        // Verifica se é para selecionar um item na lista de acordo com a url
        selecionaItem();
    } else {
        showMessage(data.message, '#message-atividade');
    }
}

/**
 * Seleciona um item da list-group de acordo com a url.
 * 
 * @returns {undefined}
 */
function selecionaItem() {
    if (getUrl().search('#') !== -1) {
        var id = getUrl().split('#')[1];
        if (id !== undefined) {
            $("#" + id).addClass('active');
            $("#" + id).removeClass('collapsed');
            $("#_" + id).addClass('in');
        }
    }
}

/**
 * Abre modal com os dados para edição do aluno.
 * 
 * @param {int} matricula
 * @returns {undefined}
 */
function editarAluno(matricula) {
    $.post('controller/AlunoController.php', {action: 'get', matricula: matricula}, function (result) {
        if (result.error !== undefined) { // Houve um erro no back
            showMessage(result, '#message-form-aluno');
        } else {
            /**
             * Popula o form com os dados
             */
            $("#mat").val(result.matricula);
            $("#matriculaAluno").val(result.matricula);
            $('#nomeAluno').val(result.nome);
            $('#dataNascimentoAluno').val(result.dataNascimento);

            $('#bt-salvar-aluno').text('Atualizar Aluno');
            $("#matriculaAluno").prop('disabled', true); // Desabilita o input matricula para evitar alteração
            $('#nomeAluno').focus();
        }
    }, 'json');

    // Open Modal
    $('#aluno-modal').modal();

    // Evento close do modal / reseta o form
    $('#aluno-modal').on('hidden.bs.modal', function () {
        $('#form-aluno')[0].reset();
        $("#matriculaAluno").prop('disabled', false); // Habilita o input matricula
        $('#bt-salvar-aluno').text('Cadastrar Aluno');
        $('#nomeAluno').blur();
        $('#matriculaAluno').blur();
    });
}

/**
 * Abre modal com os dados para edição da disciplina.
 * 
 * @param {int} id
 * @returns {undefined}
 */
function editarDisciplina(id) {
    $.post('controller/DisciplinaController.php', {action: 'get', id: id}, function (result) {
        if (result.error !== undefined) { // Houve um erro no back
            showMessage(result, '#message-form-disciplina');
        } else {
            /**
             * Popula o form com os dados
             */
            $("#idDisciplina").val(result.id);
            $("#nomeDisciplina").val(result.nome);
            $("#cargaHorariaDisciplina").val(result.cargaHoraria);

            $('#bt-salvar-disciplina').text('Atualizar Disciplina');
        }
    }, 'json');

    // Open Modal
    $('#disciplina-modal').modal();

    // Evento close do modal / reseta o form
    $('#disciplina-modal').on('hidden.bs.modal', function () {
        $('#form-disciplina')[0].reset();
        $('#bt-salvar-disciplina').text('Cadastrar Disciplina');
        $('#nomeDisciplina').blur();
    });
}

/**
 * Abre modal com os dados para edição da disciplina.
 * 
 * @param {int} id
 * @returns {undefined}
 */
function popularFormAtividades(id) {
    if (id !== null && id !== undefined) {
        $('body').loadie(0);
        $('#bt-salvar-atividade').prop('disabled', true);

        $.post('controller/AtividadeController.php', {action: 'get', id: id}, function (atividade) {
            if (atividade.error !== undefined) { // Houve um erro no back
                showMessage(atividade.error, '#message-form-atividade');
            } else {
                /**
                 * Popula o form com os dados
                 */
                $("#idAtividade").val(atividade.id);
                $("#tituloAtividade").val(atividade.titulo);
                $("#descricaoAtividade").val(atividade.descricao);
                $("#matriculaAlunoAtividade").val(atividade.aluno.matricula);
                $("#alunoAtividade").val(atividade.aluno.nome);
                $("#idDisciplinaAtividade").val(atividade.disciplina.id);
                $("#disciplinaAtividade").val(atividade.disciplina.nome);
                $("#dataEntregaAtividade").val(atividade.dataEntrega);

                if (!$.isEmptyObject(atividade.listaDeDocumentos)) {
                    $('#box-files').empty();
                    $.each(atividade.listaDeDocumentos, function (i, documento) {
                        var inputFile = '<div class="input-group margin-top-05 animated bounce" tabindex="6"><label class="input-group-btn"><span class="btn btn-success">Selecionar<input type="file" name="documentos[]" style="display: none;"><input type="text" class="id-documento" name="id-documentos[]" hidden value="' + documento.id + '"></span></label>';
                        inputFile += '<input type="text" class="form-control" name="nome-documentos[]" value="' + documento.titulo + '"></input><span class = "input-group-btn"><button class="btn btn-danger bt-remove-file" type="button"><span class="glyphicon glyphicon-trash"></span></button></span></div>';
                        $('#box-files').append(inputFile);
                    });
                }

                $('#bt-salvar-atividade').text('Atualizar Atividade');
                $('#bt-salvar-atividade').prop('disabled', false);
            }
            $('body').loadie(1);
        }, 'json');
    } else {
        $('#bt-salvar-atividade').text('Cadastrar Atividade');
        $('#bt-salvar-atividade').prop('disabled', false);
    }
}

/*
 * Add elemntos para o form voltar ao estado default
 * 
 * @returns {undefined}
 */
function defaultFormAtividade() {
    $('#box-files').empty();
    var inputFile = '<div class="input-group margin-top-05 animated bounce" tabindex="6"><label class="input-group-btn"><span class="btn btn-success">Selecionar<input type="file" name="documentos[]" style="display: none;"></span></label>';
    inputFile += '<input type="text" class="form-control" name="nome-documentos[]" readonly></input><span class = "input-group-btn"><button class="btn btn-danger bt-remove-file" type="button"><span class="glyphicon glyphicon-trash"></span></button></span></div>';
    $('#box-files').append(inputFile);
    $('#bt-salvar-atividade').text('Cadastrar Atividade');
    $('#tituloAtividade').focus();
}

/**
 * Remove Aluno
 * 
 * @param {int} matricula
 * @returns {undefined}
 */
function removerAluno(matricula) {
    $('#modal-remove-aluno').modal({
        backdrop: 'static',
        keyboard: false
    }).one('click', '#bt-remover-aluno', function () {
        $(this).button('loading'); // loading do botão submit
        $.post('controller/AlunoController.php', {action: 'delete', matricula: matricula}, function (result) {
            showMessage(result, '#message-aluno');
            $('#bt-remover-aluno').button('reset'); // reseta botão submit
            refresh();
        });
    });
}

/**
 * Remove Aluno
 * 
 * @param {int} id
 * @returns {undefined}
 */
function removerDisciplina(id) {
    $('#modal-remove-disciplina').modal({
        backdrop: 'static',
        keyboard: false
    }).one('click', '#bt-remover-disciplina', function () {
        $(this).button('loading'); // loading do botão submit
        $.post('controller/DisciplinaController.php', {action: 'delete', id: id}, function (result) {
            showMessage(result, '#message-disciplina');
            $('#bt-remover-disciplina').button('reset'); // reseta botão submit
            refresh();
        });
    });
}

/**
 * Remove Ativiade
 * 
 * @param {int} id
 * @returns {undefined}
 */
function removerAtividade(id) {
    $('#modal-remove-atividade').modal({
        backdrop: 'static',
        keyboard: false
    }).one('click', '#bt-remover-atividade', function () {
        $(this).button('loading'); // loading do botão submit
        $.post('controller/AtividadeController.php', {action: 'delete', id: id}, function (result) {
            showMessage(result, '#message-atividade');
            $('#bt-remover-atividade').button('reset'); // reseta botão submit
            refresh();
        });
    });
}

/**
 * retorna a utl atual
 * 
 * @returns {window.location.href|DOMString}
 */
function getUrl() {
    return window.location.href;
}

/**
 * Aplica refresh na tela de acordo com a url solicitada.
 * 
 * @returns {undefined}
 */
function refresh() {
    if (getUrl().search('alunos') !== -1) {
        listarAlunos();
    } else if (getUrl().search('disciplinas') !== -1) {
        listarDisciplinas();
    } else if (getUrl().search('cadastrar-atividade') === -1) {
        listarAtividades(null);
    }
}

/**
 * Formata data para padrão brasileiro.
 * 
 * @param {String} date
 * @returns {String}
 */
function date_to_br(date) {
    var parts = date.split("-");
    return  parts[2].toString() + '/' + parts[1].toString() + '/' + parts[0].toString();
}

/**
 * Formata data para padrao brasileiro: 23/12/2017 às 22:24
 * @param {String} date
 * @returns {undefined}
 */
function datetime_to_br(date) {
    var parts_date = date.split('-');
    var parts_time = parts_date[2].substr(2, parts_date[2].length);
    parts_date[2] = parts_date[2].substr(0, 2);

    return parts_date[2].toString() + '/' + parts_date[1].toString() + '/' + parts_date[0].toString()
            + ' às ' + parts_time;
}

/**
 * Útil no cadastro de atividade para limpar inputs quando abrir o modal.
 * 
 * @param {String} seletor
 * @returns {undefined}
 */
function cleanInputs(seletor) {
    $('input[data-clean=' + seletor + ']').val("");
}