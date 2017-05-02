<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>MSA - Disciplinas</title>
        <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/styles.css" />

        <script type="text/javascript" src="assets/js/jquery-3.2.0.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/my-actions.js"></script>
    </head>
    <body onload="listarDisciplinas();">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <?php include_once 'nav-bar.php'; ?>
        </nav>

        <section>
            <div class="container">
                <header id="title-header">
                    <h1>Lista de Disciplinas<a href="relatorios/disciplinas_print.php" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gerar PDF da lista de disciplinas..."><span class="glyphicon glyphicon-print"></span></a></h1> 
                </header>

                <div class="row">
                    <div class="col-md-9" id="content-list-group">
                        <div id="message-disciplina"></div>
                        <div class="panel list-group" id="list-content"></div>
                    </div>

                    <div class="col-md-3">
                        <?php include_once 'nav-right.php'; ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="modal-remove-disciplina" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Remover Disciplina?</h4>
                    </div>
                    <div class="modal-body clean-margin">
                        <p>Tem certeza que deseja remover a disciplina?</p>
                        <p><i>OBS.: Só será possível remover a disciplina caso não esteja relacionada com nenhuma atividade!</i></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="bt-remover-disciplina" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Removendo Disciplina...">Remover Disciplina</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
