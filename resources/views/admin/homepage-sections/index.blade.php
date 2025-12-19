@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Homepage Sections</h4>
                    <p class="card-text">Manage which sections appear on the homepage and their order</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homepage-sections.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Section</th>
                                        <th>Enabled</th>
                                        <th>Display Order</th>
                                    </tr>
                                </thead>
                                <tbody id="sections-table-body">
                                    @foreach($sections as $section)
                                    <tr>
                                        <td>{{ $section->section_name }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="sections[{{ $section->id }}][is_enabled]" id="enabled_{{ $section->id }}" {{ $section->is_enabled ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="sections[{{ $section->id }}][display_order]" value="{{ $section->display_order }}" class="form-control w-50" min="1">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Sections</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('sections-table-body');
    
    // Make table rows sortable with drag and drop
    let draggedItem = null;

    // Add drag events to table rows
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        row.setAttribute('draggable', true);
        
        row.addEventListener('dragstart', function() {
            draggedItem = this;
            setTimeout(() => this.classList.add('dragging'), 0);
        });
        
        row.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            draggedItem = null;
            
            // Update order numbers after reordering
            updateOrderNumbers();
        });
        
        row.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        row.addEventListener('dragenter', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        
        row.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });
        
        row.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            if (draggedItem !== this) {
                const allRows = Array.from(table.querySelectorAll('tr'));
                const draggedIndex = allRows.indexOf(draggedItem);
                const targetIndex = allRows.indexOf(this);
                
                if (draggedIndex < targetIndex) {
                    table.insertBefore(draggedItem, this.nextSibling);
                } else {
                    table.insertBefore(draggedItem, this);
                }
            }
        });
    });
    
    function updateOrderNumbers() {
        const rows = table.querySelectorAll('tr');
        rows.forEach((row, index) => {
            const input = row.querySelector('input[type="number"]');
            if (input) {
                input.value = index + 1;
            }
        });
    }
});
</script>
@endsection