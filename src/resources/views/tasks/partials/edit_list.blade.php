    <div class="modal fade" id="modalEditTask" tabindex="-1" aria-labelledby="editTaskLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editTaskForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskLabel">Editar Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editTaskId" name="id">
                    <div class="mb-3">
                        <label for="editTaskTitle" class="form-label">Título</label>
                        <input type="text" class="form-control" id="editTaskTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" id="editTaskDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDueDate" class="form-label">Prazo</label>
                        <input type="date" class="form-control" id="editTaskDueDate" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskStatus" class="form-label">Status</label>
                        <select class="form-select" id="editTaskStatus" name="status" required>
                            <option value="pending">Pendente</option>
                            <option value="in_progress">Em progresso</option>
                            <option value="completed">Concluída</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>