<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>MSA - Cadastro de Atividade</title>
        <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/jquery-ui.min.css" />
        <link rel="stylesheet" href="assets/css/styles.css" />

        <script type="text/javascript" src="assets/js/jquery-3.2.0.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="assets/js/my-actions.js"></script>
    </head>
    <body onload="popularFormAtividades(<?php echo (filter_has_var(INPUT_GET, 'id')) ? filter_input(INPUT_GET, 'id') : null; ?>)">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <?php include_once 'nav-bar.php'; ?>
        </nav>

        <section>
            <div class="container ">
                <header>
                    <h1>Cadastro de Atividade</h1>
                </header>

                <div class="row">
                    <div class="col-md-9 box-form">
                        <div id="message-form-atividade"></div>

                        <form action="controller/AtividadeController.php" method="POST" class="well" id="form-atividade">
                            <div class="form-group">
                                <label for="titulo">Título:*</label>
                                <input type="text" id="idAtividade" name="id" hidden>
                                <input type="text" class="form-control" id="tituloAtividade" name="titulo" placeholder="Insira o título da atividade..." required autofocus tabindex="1">
                            </div>

                            <div class="form-group">
                                <label for="descricao" class="control-label">Descrição</label>
                                <textarea name="descricao" class="form-control"  id="descricaoAtividade" rows="3" placeholder="Insira a descrição da atividade..." tabindex="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="aluno">Aluno:* 
                                    <a href="#" class="color-default" data-html="true" data-trigger="focus" title="Atenção!" data-html="true" data-toggle="popover" data-placement="top" data-content="<p><b>Se o aluno desejado possuir cadastro irá aparecer na lista ao começar a digitar.</b></p>
                                       <p>Caso contrário, insira o aluno clicando no botão + ao lado e o mesmo estará dispónível para seleção após o cadastro com sucesso...</p>">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </a>
                                    <a href="#" data-toggle="modal" data-target="#aluno-modal" onclick="cleanInputs('aluno');" class="margin-left-05"><span data-toggle="tooltip" data-placement="right" title="" data-original-title="Inserir novo usuário..." class="glyphicon glyphicon-plus font-size-plus-10"></span></a>
                                </label>
                                <input type="text" data-clean='aluno' name="matricula-aluno" id="matriculaAlunoAtividade" hidden>
                                <input type="text" data-clean='aluno' class="form-control" name="aluno" id="alunoAtividade" placeholder="Selecione o aluno..." required tabindex="3">
                            </div>

                            <div class="form-group">
                                <label for="diciplina">Disciplina:*
                                    <a href="#" class="color-default"  data-html="true" data-trigger="focus" title="Atenção!" data-html="true" data-toggle="popover" data-placement="top" data-content="<p><b>Se a disciplina desejada possuir cadastro irá aparecer na lista ao começar a digitar.</b></p>
                                       <p>Caso contrário, insira a disciplina clicando no botão + ao lado e a mesma estará dispónível para seleção após o cadastro com sucesso...</p>">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </a>
                                    <a href="" data-toggle="modal" data-target="#disciplina-modal" onclick="cleanInputs('disciplina');" class="margin-left-05"><span data-toggle="tooltip" data-placement="right" title="" data-original-title="Inserir nava disciplina..." class="glyphicon glyphicon-plus font-size-plus-10"></span></a>
                                </label>
                                <input type="text" data-clean='disciplina' name="id-disciplina" id="idDisciplinaAtividade" hidden>
                                <input type="text" data-clean='disciplina' class="form-control" name="disciplina" id="disciplinaAtividade" placeholder="Selecione a disciplina..." required tabindex="4">
                            </div>

                            <div class="form-group">
                                <label for="data-entrega">Data para entrega:*</label>
                                <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" name="data-entrega" id="dataEntregaAtividade" placeholder="Insira a data limite para entrega da atividade..." required tabindex="5">
                            </div>

                            <div class="form-group">
                                <label for="documentos">Documentos:*
                                    <a href="#" class="color-default"  data-html="true" data-trigger="focus" title="Atenção!" data-html="true" data-toggle="popover" data-placement="top" data-content="<p><b>Clique no botão + ao lado para selecionar mais arquivos. Você pode adicionar até 5 documentos no máximo.</b></p>
                                       <p>Após selecionar o documento você poderá mudar o nome se preferir...</p>">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </a>
                                    <a href="#" onclick="addFile();" class="margin-left-05"><span data-toggle="tooltip" data-placement="right" title="" data-original-title="Inserir mais documento..." class="glyphicon glyphicon-plus font-size-plus-10"></span></a>
                                </label>

                                <div id="box-files">
                                    <div class="input-group" tabindex="6">
                                        <label class="input-group-btn">
                                            <span class="btn btn-success">
                                                Selecionar<input type="file" name="documentos[]" style="display: none;">
                                            </span>
                                        </label>
                                        <input type="text" class="form-control" name="nome-documentos[]" readonly></input>

                                        <span class = "input-group-btn">
                                            <button class="btn btn-danger bt-remove-file" type="button"><span class="glyphicon glyphicon-trash"></span></button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group pull-right box-form-buttons">
                                <button type="reset" class="btn btn-default" tabindex="7" onclick="defaultFormAtividade();">Cancelar</button>
                                <button type="submit" class="btn btn-primary" id="bt-salvar-atividade" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Salvando dados..." tabindex="8">Cadastrar Atividade</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-3">
                        <?php include_once 'nav-right.php'; ?>
                    </div>
                </div>

            </div>
        </section>
        <script>
            $(document).ready(function () {
                /**
                 * Usanda para manter coontrole do total de inputs files
                 * @type Number
                 */
                totalInputs = 1;

                /**
                 * Anexar o evento change a todos os inputs do tipo file
                 */
                $(document).on('change', ':file', function () {
                    var input = $(this), name = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    name = name.substring(name.lastIndexOf('.'), 0);
                    
                    var input_label = $(this).parents('.input-group').find(':text');
                    $(this).parents('.input-group').find('.id-documento').remove();
                    
                    $(input_label).prop('required', true);
                    $(input_label).prop('readonly', false).focus();
                    input_label.val(name);
                });

                /**
                 * Evento click no botão remover inputs
                 */
                $(document).on('click', '.bt-remove-file', function () {
                    ($(this).parents('.input-group').find(':file')).val("");
                    ($(this).parents('.input-group').find(':text')).val("");
                    $(this).parents('.input-group').fadeOut(300, function () {
                        $(this).remove();
                        totalInputs -= 1;
                    });
                });
            });

            /**
             * Adiciona novo input file
             * 
             * @returns {undefined}
             */
            function addFile() {
                if (totalInputs < 5) {
                    var inputFile = '<div class="input-group margin-top-05 animated bounce" tabindex="6"><label class="input-group-btn"><span class="btn btn-success">Selecionar<input type="file" name="documentos[]" style="display: none;"></span></label>';
                    inputFile += '<input type="text" class="form-control" name="nome-documentos[]" readonly></input><span class = "input-group-btn"><button class="btn btn-danger bt-remove-file" type="button"><span class="glyphicon glyphicon-trash"></span></button></span></div>';

                    $('#box-files').append(inputFile);
                    totalInputs += 1;
                    
                    $('body').animate({scrollTop: $('body').prop('scrollHeight')}, 500);
                }
            }
            /**
             * Autocomplete para o inout aluno
             * @type type
             */
            $(document).ready(function () {
                $('#alunoAtividade').autocomplete({
                    minLength: 2,
                    source: function (request, response) {
                        $.get({
                            url: "controller/AtividadeController.php",
                            dataType: "json",
                            data: {
                                action: 'auto-complete-aluno',
                                term: $('#alunoAtividade').val()
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    change: function (event, ui) {
                        if (ui.item === null) {
                            $('#matriculaAlunoAtividade').val("");
                            $(this).val("");
                        }
                        return false;
                    },
                    select: function (e, ui) {
                        $(this).val(ui.item.nome);
                        $('#matriculaAlunoAtividade').val(ui.item.matricula);
                        return false;
                    }
                }).autocomplete("instance")._renderItem = function (ul, item) {
                    return $("<li>")
                            .data("item.autocomplete", item)
                            .append("<a>" + item.nome + " - <small> Mat. " + item.matricula + "</small></a>")
                            .appendTo(ul);
                };

                /**
                 * Autocomplete para o inout disciplina
                 * @type type
                 */
                $("#disciplinaAtividade").autocomplete({
                    minLength: 2,
                    source: function (request, response) {
                        $.get({
                            url: "controller/AtividadeController.php",
                            dataType: "json",
                            data: {
                                action: 'auto-complete-disciplina',
                                term: $('#disciplinaAtividade').val()
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    change: function (event, ui) {
                        if (ui.item === null) {
                            $('#idDisciplinaAtividade').val("");
                            $(this).val("");
                        }
                        return false;
                    },
                    select: function (e, ui) {
                        $(this).val(ui.item.nome);
                        $('#idDisciplinaAtividade').val(ui.item.id);
                        return false;
                    }
                }).autocomplete("instance")._renderItem = function (ul, item) {
                    return $("<li>")
                            .data("item.autocomplete", item)
                            .append("<a>" + item.nome + " - <small>" + item.cargaHoraria + " horas</small></a>")
                            .appendTo(ul);
                };
            });
        </script>
    </body>

</html>