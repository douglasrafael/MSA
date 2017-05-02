<ul class="nav nav-pills nav-stacked">
    <li class="<?php echo (URL::getPagina() === 'index' || URL::getPagina() === FALSE) ? 'active': ''; ?>"><a href="index.php"><span class="glyphicon glyphicon-list-alt"></span>Atividades</a></li>
    <li class="<?php echo (URL::getPagina() === 'alunos') ? 'active': ''; ?>"><a href="alunos.php"><span class="glyphicon glyphicon-user"></span>Alunos</a></li>
    <li class="<?php echo (URL::getPagina() === 'disciplinas') ? 'active': ''; ?>"><a href="disciplinas.php"><span class="glyphicon glyphicon-th"></span>Disciplinas</a></li>
</ul>
<?php
    include_once 'form-modal-aluno.php';
    include_once './form-modal-disciplina.php';
    
