<x-layout title="Gerenciador de Tarefas - Tarefas completadas">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Minhas Tarefas - Lista de tarefas finalizadas</h3>
        </div>
        <div id="taskList" data-task-filter="completed">
            @include('tasks.partials.task_list')
        </div>
    </div>
        @include('tasks.partials.edit_list')
</x-layout>