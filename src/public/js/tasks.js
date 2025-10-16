// Função principal - Delegação de Eventos
document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('editTaskForm');
    document.addEventListener('click', function (e) {
        // Botão de editar tarefa
        const editBtn = e.target.closest('.edit-task-btn');
        if (editBtn) {
            const task = JSON.parse(editBtn.getAttribute('data-task'));
            fillEditModal(task);
            return;
        }
        // Botão de concluir tarefa
        const completeBtn = e.target.closest('.complete-task-btn');
        if (completeBtn) {
            completeTask(completeBtn.dataset.taskId);
            return;
        }
        // Botão de excluir tarefa
        const deleteBtn = e.target.closest('.delete-task-btn');
        if (deleteBtn) {
            deleteTask(deleteBtn.dataset.id);
            return;
        }
    });

    // Formulário de edição de tarefas
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            updateTask(editForm);
        });
    }

    // Criação de novas tarefas
    const createForm = document.getElementById('formNewTask');
    const modalCreate = document.getElementById('modalNewTask');

    if (createForm) {
        createForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            await createTask(createForm, modalCreate);
        });
    }

    // Função (botão) de nova tarefa (abre o modal de criação)
    const btnNew = document.getElementById('btnNewTask');
    if (btnNew) {
        btnNew.addEventListener('click', () => {
            const modalCreate = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalNewTask'));
            modalCreate.show();
        });
    }
});

// Funções de edição de tarefas
function fillEditModal(task) {
    document.getElementById('editTaskId').value = task.id;
    document.getElementById('editTaskTitle').value = task.title || '';
    document.getElementById('editTaskDescription').value = task.description || '';
    document.getElementById('editTaskDueDate').value = task.due_date || '';
    document.getElementById('editTaskStatus').value = task.status || 'pending';

    const form = document.getElementById('editTaskForm');
    form.action = `/tasks/${task.id}`;

    const modal = new bootstrap.Modal(document.getElementById('modalEditTask'));
    modal.show();
}

function updateTask(form) {
    const formData = new FormData(form);
    const taskId = document.getElementById('editTaskId').value;

    fetch(`/tasks/${taskId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => {
        if (!res.ok) throw new Error('Erro ao atualizar tarefa.');
        return res.json();
    })
    .then(data => {
        if (data.success) {
            closeModal('#modalEditTask');
            showAlert('Tarefa atualizada com sucesso!');
            refreshTaskList();
        }
    })
    .catch(() => showAlert('Ocorreu um erro ao atualizar a tarefa.', 'danger'));
}

// Função para concluir tarefas
function completeTask(taskId) {
    fetch(`/tasks/${taskId}/complete`, {
        method: 'PATCH',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message);
            refreshTaskList();
        }
    })
    .catch(err => console.error(err));
}


// Função de excluir tarefas
function deleteTask(taskId) {
    if (!confirm('Tem certeza que deseja excluir esta tarefa?')) return;

    fetch(`/tasks/${taskId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showAlert('Tarefa excluída com sucesso!');
            refreshTaskList();
        }
    })
    .catch(err => console.error(err));
}

// Função para criar novas tarefas
async function createTask(form, modalElement) {
    const formData = new FormData(form);
    const url = form.action;

    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            const data = await response.json();
            if (data.errors) {
                Object.entries(data.errors).forEach(([field, msgs]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const div = document.createElement('div');
                        div.classList.add('invalid-feedback');
                        div.textContent = msgs[0];
                        input.parentNode.appendChild(div);
                    }
                });
            }
            throw new Error('Erro de validação');
        }

        const data = await response.json();
        if (data.success) {
            showAlert(data.message);
            closeModal('#modalNewTask');
            form.reset();

            setTimeout(() => window.location.reload(), 300);
        }
    } catch (error) {
        console.error(error);
    }
}

// Fechar modal
function closeModal(selector) {
    const modalElement = document.querySelector(selector);
    const modalInstance = bootstrap.Modal.getInstance(modalElement);

    if (modalInstance) {
        modalInstance.hide();
        modalElement.addEventListener('hidden.bs.modal', () => {
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
            document.body.style.overflow = 'auto';
            document.body.style.paddingRight = '0';
        }, { once: true });
    }
}

// Atualizar a lista de tarefas
async function refreshTaskList() {
    const container = document.querySelector('#taskList');
    if (!container) return;

    const filter = container.dataset.taskFilter || 'all';
    let url = '/tasks';

    if (filter !== 'all') url += `/${filter}`;

    try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) throw new Error('Falha ao recarregar lista.');

        container.innerHTML = await res.text();
    } catch (err) {
        console.error('Erro ao atualizar lista:', err);
    }
}

// Alertas de sucesso para as ações
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} text-center position-fixed top-0 start-50 translate-middle-x mt-3`;
    alertDiv.style.zIndex = '2000';
    alertDiv.textContent = message;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 2500);
}