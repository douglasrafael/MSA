<div class="modal fade" id="aluno-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cadastro de Aluno</h4>
            </div>
            <div class="modal-body">
                <div id="message-form-aluno"></div>
                <form action="controller/AlunoController.php" method="POST" id="form-aluno">
                    <div class="form-group">
                        <label for="matricula" class="control-label">Matrícula:*</label>
                        <input type="number" name="mat" id="mat" hidden>
                        <input type="number" class="form-control" id="matriculaAluno" name="matricula" placeholder="Insira a matrícula..." required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome:*</label>
                        <input type="text" class="form-control" id="nomeAluno" name="nome" placeholder="Insira nome completo do aluno..." required>
                    </div>
                    <div class="form-group">
                        <label for="data-nascimento">Data de Nacimento:*</label>
                        <input type="date" class="form-control" id="dataNascimentoAluno" name="data-nascimento" placeholder="Insira a data de nascimento..." required>
                    </div>
                    <div class="form-group pull-right box-form-buttons">
                        <button type="reset" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="bt-salvar-aluno" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Salvando dados...">Cadastrar Aluno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>