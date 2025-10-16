<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [ 'title', 'description', 'due_date', 'status' ];

    public function getStatusLabelAttribute(){
        return match ($this->status) {
            'pending' => 'Pendente',
            'in_progress' => 'Em progresso',
            'completed' => 'Finalizada',
            default => ucfirst($this->status),
        };
    }
}