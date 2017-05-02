<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>MSA - Atividades</title>
        <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/styles.css" />

        <script type="text/javascript" src="assets/js/jquery-3.2.0.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/my-actions.js"></script>
    </head>
    <body onload="listarAtividades(null);">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <?php include_once 'nav-bar.php'; ?>
        </nav>

        <section>
            <div class="container">
                <header id="title-header">
                    <h1>Lista de Atividades<a href="relatorios/atividades_print.php" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gerar PDF da lista de ativiades..."><span class="glyphicon glyphicon-print"></span></a></h1> 
                </header>

                <div class="row">
                    <div class="col-md-9" id="content-list-group">
                        <div id="message-atividade"></div>
                        <div class="panel list-group" id="list-content">
                            <!--                            <button  href="#" class="list-group-item">
                                                            <div class="pull-right">
                                                                <span class="label label-default">Programação Web</span>
                                                                <span class="label label-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Data limite para entrega">27/03/2017</span>
                                                            </div>
                                                            <h4 class="list-group-item-heading">Mini Projeto de Web</h4>
                                                            <p class="list-group-item-text">Projeto de web em php com PDO. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus, earum impedit tempore voluptatum recusandae ipsa est cupiditate optio? Sunt, quisquam, nesciunt voluptatibus minus quis aspernatur similique consequatur incidunt praesentium facilis.</p>
                                                            <div class="margin-top-10">
                                                                <strong>Aluno:  </strong><a href="#">Douglas Rafael</a>&nbsp;&nbsp;|&nbsp;
                                                                <strong>Documento: </strong><a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Baixar documento"><span class="glyphicon glyphicon-file"></span></a>
                            
                                                                <small class=" pull-right">Cadastrado em 23/12/2017 às 22:24</small>
                                                            </div>
                                                        </button>
                            
                                                        <button  href="#" class="list-group-item " id="1">
                                                            <div class="pull-right box-menu-actions">
                                                                <span onclick="removerAtividade(' + disciplina.id + ')" class="glyphicon glyphicon-trash pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Remover atividade"></span>
                                                                <span onclick="cadastrar - atividade.php?id = 1" class="glyphicon glyphicon-pencil pull-right" data-toggle="tooltip" data-placement="left" title="" data-original-title="Editar dados da atividade"></span>
                                                            </div>
                            
                                                            <div class="pull-right">
                                                                <span class="label label-default"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Disciplina: NOME AQUI DA DISCIPLINA">Tópicos especiais em...</span>
                                                                <span class="label label-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Data limite para entrega">27/03/2017</span>
                                                            </div>
                            
                                                            <h4 class="list-group-item-heading">Mini Projeto de Web parasassssdd apresentação de disco fina</h4>
                                                            <p class="list-group-item-text">quia laborum laboriosam fugit dolor. iste omnis fugiat pariatur culpa veritatis reprehenderit eligendi natus neque debitis hic dolorum quas quos autem aut. Modi.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero, laborum necessitatibus nulla. Perferendis, nisi, provident, eveniet consequuntur cumque placeat fuga labore officia deleniti nostrum laudantium ducimus architecto mollitia quaerat sequi.</p>
                            
                                                            <div class="margin-top-05">
                                                                <div>
                                                                    <strong>Aluno:  </strong><a href="#">Douglas Rafael</a>
                                                                </div>
                            
                                                                <div class="pull-left box-documentos" style="">
                                                                    <strong>Documento(s): </strong>
                                                                    <a href="#" class="badge badge-info margin-right-05" data-toggle="tooltip" data-placement="top" title="" data-original-title="Baixar documento">Atividade - Prolog 01</a>
                                                                    <a href="#" class="badge badge-info margin-right-05" data-toggle="tooltip" data-placement="top" title="" data-original-title="Baixar documento">Atividade - Prolog 01</a>
                                                                    <a href="#" class="badge badge-info margin-right-05" data-toggle="tooltip" data-placement="top" title="" data-original-title="Baixar documento">Atividade - Prolog 01</a>
                                                                </div>
                                                                <div class="pull-right"><small>Cadastrado em 23/12/2017 às 22:24</small></div>
                                                            </div>
                                                        </button>-->
                        </div>
                    </div>

                    <div class="col-md-3">
                        <?php include_once 'nav-right.php'; ?>
                    </div>
                </div>
            </div>
        </section>
        
        <div class="modal fade" id="modal-remove-atividade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Remover Atividade?</h4>
                    </div>
                    <div class="modal-body clean-margin">
                        <p>Tem certeza que deseja remover a atividade?</p>
                        <p><i>OBS.: Todos os documnetos associado a esta atividade serão perdidos...</i></p>
                        <p class="text-danger text-center">Não será possível recuperar os dados após a remoção!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="bt-remover-atividade" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Removendo Atividade...">Remover Atividade</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
