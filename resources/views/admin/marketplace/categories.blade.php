@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Marketplace Categories</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.marketplace.dashboard') }}">Marketplace</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Manage Categories</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="ri-add-circle-line me-1"></i> Create Category
                    </button>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover" id="categoriesTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Description</th>
                                <th scope="col">Listings</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }} me-2 text-primary"></i>
                                                @else
                                                    <i class="ri-folder-line me-2 text-primary"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                {{ $category->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $category->slug }}</code>
                                    </td>
                                    <td>
                                        @if($category->description)
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $category->description }}">
                                                {{ Str::limit($category->description, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info rounded-pill">{{ $category->listings_count }}</span>
                                    </td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $category->created_at->format('M j, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}" title="Edit Category">
                                                <i class="ri-edit-box-line"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.marketplace.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" {{ $category->listings_count > 0 ? 'disabled' : '' }} title="{{ $category->listings_count > 0 ? 'Cannot delete category with listings' : 'Delete Category' }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $category->id }}">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.marketplace.categories.update', $category) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="edit_name{{ $category->id }}" class="form-label">Category Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="edit_name{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_description{{ $category->id }}" class="form-label">Description</label>
                                                        <textarea class="form-control" id="edit_description{{ $category->id }}" name="description" rows="3" placeholder="Enter category description (optional)">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_icon{{ $category->id }}" class="form-label">Icon Class (Optional)</label>
                                                        <input type="text" class="form-control" id="edit_icon{{ $category->id }}" name="icon" value="{{ $category->icon }}" placeholder="e.g., ri-fruit-line">
                                                        <div class="form-text">Enter Remix Icon class name (optional)</div>
                                                    </div>
                                                    <div class="mb-3 form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" id="edit_is_active{{ $category->id }}" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="edit_is_active{{ $category->id }}">Active Category</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($categories->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="ri-folder-open-line display-4 text-muted"></i>
                        </div>
                        <h5 class="text-muted">No categories found</h5>
                        <p class="text-muted mb-4">Get started by creating your first category.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                            <i class="ri-add-circle-line me-1"></i> Create Category
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.marketplace.categories.store') }}" id="createCategoryForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Enter category name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter category description (optional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon Class (Optional)</label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="e.g., ri-fruit-line">
                        <div class="form-text">Enter Remix Icon class name (optional)</div>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Active Category</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#categoriesTable').DataTable({
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
            "order": [[0, "desc"]],
            "responsive": true,
            "language": {
                "emptyTable": "No categories found. Click 'Create Category' to add one."
            }
        });

        // Reset form when modal is closed
        $('#createCategoryModal').on('hidden.bs.modal', function () {
            document.getElementById('createCategoryForm').reset();
        });

        // Auto-generate slug from name
        $('#name').on('input', function() {
            const name = $(this).val();
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
                .replace(/\s+/g, '-')        // Replace spaces with -
                .replace(/-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')          // Trim - from start of text
                .replace(/-+$/, '');         // Trim - from end of text
            // You can set this to a hidden slug field if you have one
        });

        // Add similar functionality for edit modals
        $('input[id^="edit_name"]').on('input', function() {
            const name = $(this).val();
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        });
    });
</script>

@push('styles')
<style>
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }
    
    .form-check-input:checked {
        background-color: #38761d;
        border-color: #38761d;
    }
    
    .btn-primary {
        background-color: #38761d;
        border-color: #38761d;
    }
    
    .btn-primary:hover {
        background-color: #2f5f17;
        border-color: #2f5f17;
    }
</style>
@endpush