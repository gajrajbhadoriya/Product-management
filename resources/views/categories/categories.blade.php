<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('categoriesListing') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories List') }}
            </h2>
        </a>
        <x-primary-button class="ms-4">
            <a href="{{ route('categories.create') }}">
                {{ __('Add Category') }}
            </a>
        </x-primary-button>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table id="categories-table" class="display">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectall"></th> <!-- Add the checkbox header -->
                                <th>Title</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        $('#categories-table').DataTable({
            "bProcessing": true,
            "bServerSide": false,
            "sAjaxSource": "{{ URL::route('categoriesListing') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'image',
                    name: 'image',
                    bSortable: false,
                    render: function(data, type, row) {
                        return '<img src="/storage/' + data +
                            '" alt="Category Image" style="width: 50px; height: auto;"/>';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        return data == 1 ?
                            '<span style="color: green;">Active</span>' :
                            '<span style="color: red;">Inactive</span>';
                    }
                },
                {
                    sWidth: "20px",
                    bSortable: false,
                    sClass: 'text-center',
                    render: function(v, t, o) {
                        var editUrl = '{{ route('categories.edit', ':id') }}'.replace(':id', o[
                            'id']);
                        var deleteUrl = '{{ route('categories.destroy', ':id') }}'.replace(
                            ':id', o['id']);

                        return "<a href='" + editUrl +
                            "' class='btn btn-primary btn-sm'>Edit</a>" +
                            "<form action='" + deleteUrl +
                            "' method='POST' style='display:inline;'>" +
                            '@csrf' +
                            '@method('DELETE')' +
                            "<button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</button>" +
                            "</form>";
                    }
                }
            ]
        });
    });
</script>
