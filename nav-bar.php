<?php include_once './uteis/URL.php'; ?>
<script type="text/javascript" src="assets/js/jquery.loadie.min.js"></script>
<link rel="stylesheet" href="assets/css/font-awesome.min.css" />
<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php" title="Manager of School Activities">MSA</a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse">
        <form class="navbar-form navbar-right" id="form-search" role="search" method="post" action="#">
            <div class="form-group">
                <input type="text" size="40" class="form-control input-sm" name="term" placeholder="Buscar atividade por título, aluno ou disciplina...">
            </div>
            <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;Buscar</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Mais&nbsp;<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#" data-toggle="modal" data-target="#aluno-modal">Inserir novo aluno</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#disciplina-modal">Inserir nova disciplina</a></li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        <li class="<?php echo URL::getPagina() === 'cadastrar-atividade' ? 'active': ''; ?>"><a href="cadastrar-atividade.php"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Inserir nova atividade</a></li>
        </ul>
    </div>
</div>
