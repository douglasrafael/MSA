<div class="modal fade" id="disciplina-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cadastro de Disciplina</h4>
            </div>
            <div class="modal-body">
                <div id="message-form-disciplina"></div>
                <form action="controller/DisciplinaController.php" method="POST" id="form-disciplina">
                    <div class="form-group">
                        <label for="nome" class="control-label">Nome:*</label>
                        <input type="text" name="id" id="idDisciplina" hidden>
                        <input type="text" class="form-control" name="nome" id="nomeDisciplina" placeholder="Insira o nome da disciplina..." required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="carga-horaria">Carga Horária:*</label>
                        <input type="number" class="form-control" value="120" name="carga-horaria" id="cargaHorariaDisciplina" placeholder="Insira o valor em horas da carga horária..." required>
                    </div>
                    <div class="form-group pull-right box-form-buttons">
                        <button type="reset" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="bt-salvar-disciplina" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Salvando dados...">Cadastrar Disciplina</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>