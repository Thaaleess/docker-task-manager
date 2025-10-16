    @if ($tasks->isEmpty())
        <div class="text-center my-5 p-5 bg-light rounded-3 shadow-sm">
            <h5 class="fw-semibold mb-2">Nenhuma tarefa encontrada.</h5>
            <p class="text-muted mb-0">Clique no botão <strong>“+ Nova Tarefa”</strong> para adicionar uma tarefa.</p>
        </div>
    @else
        <div class="task-list list-group shadow-sm rounded-3 mb-2">
            @foreach ($tasks as $task)
                <div class="list-group-item d-flex justify-content-between align-items-start" data-task-id="{{ $task->id }}">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-1 fw-bold task-title">{{ $task->title }}</h5>
                            <span class="badge task-status 
                                @if ($task->status === 'pending') bg-warning text-dark
                                @elseif ($task->status === 'in_progress') bg-info text-dark
                                @else bg-success
                                @endif ">
                                {{ $task->status_label }}
                            </span>
                        </div>
                        <p class="mb-1 text-muted task-desc">{{ $task->description ?: 'Sem descrição' }}</p>
                        <small class="text-secondary task-due">
                            Prazo: {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                        </small>
                    </div>
                    <div class="ms-3 d-flex align-items-center">
                        @if($task->status !== 'completed')
                            <button class="btn btn-sm btn-success complete-task-btn me-2" 
                                    title="Concluir" 
                                    data-task-id="{{ $task->id }}">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        @endif
                        <button class="btn btn-sm btn-primary me-2 edit-task-btn" data-task='@json($task)'>
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-task-btn" title="Excluir" data-id="{{ $task->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {{ $tasks->links() }}
        </div>
    @endif