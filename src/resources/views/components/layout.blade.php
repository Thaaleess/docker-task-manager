<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/sass/app.scss'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="{{ route('tasks.home') }}">Gerenciador de Tarefas</a>
        <div class="ms-auto">
            <button class="btn btn-outline-light" id="btnNewTask">+ Nova Tarefa</button>
        </div>
    </nav>
    <div class="container-fluid p-0">
        <div class="sidebar">
            <h5 class="px-3">Categorias</h5>
            <a href="{{ route('tasks.index') }}">Todas</a>
            <a href="{{ route('tasks.pending') }}">Pendentes</a>
            <a href="{{ route('tasks.in_progress') }}">Em Progresso</a>
            <a href="{{ route('tasks.completed') }}">Concluídas</a>
        </div>
        <div class="main-content">
            <div id="alert-container" class="mt-3"></div>

            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showAlert(@json(session('success')));
                    });
                </script>
            @endif

            {{ $slot }}
        </div>
    </div>
    <div class="modal fade" id="modalNewTask" tabindex="-1" aria-labelledby="newTaskLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formNewTask" action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="newTaskLabel">Adicionar Nova Tarefa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Título</label>
                            <input type="text" class="form-control" id="taskTitle" name="title" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Descrição</label>
                            <textarea class="form-control" id="taskDescription" name="description" rows="3"></textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="taskDueDate" class="form-label">Prazo</label>
                            <input type="date" class="form-control" id="taskDueDate" name="due_date">
                            @error('due_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="taskStatus" class="form-label">Status</label>
                            <select class="form-select" id="taskStatus" name="status" required>
                                <option value="pending">Pendente</option>
                                <option value="in_progress">Em progresso</option>
                                <option value="completed">Concluída</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Tarefa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/tasks.js') }}"></script>
</body>
</html>