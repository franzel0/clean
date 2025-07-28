<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
        <p class="mt-2 text-gray-600">Manage dropdown options and system configurations</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex flex-wrap">
            <button 
                wire:click="setActiveTab('instrument-categories')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm {{ $activeTab === 'instrument-categories' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Instrument Categories
            </button>
            <button 
                wire:click="setActiveTab('instrument-statuses')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'instrument-statuses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Instrument Statuses
            </button>
            <button 
                wire:click="setActiveTab('container-types')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'container-types' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Container Types
            </button>
            <button 
                wire:click="setActiveTab('container-statuses')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'container-statuses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Container Statuses
            </button>
            <button 
                wire:click="setActiveTab('defect-types')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'defect-types' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Defect Types
            </button>
            <button 
                wire:click="setActiveTab('purchase-order-statuses')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'purchase-order-statuses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                PO Statuses
            </button>
            <button 
                wire:click="setActiveTab('operating-rooms')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'operating-rooms' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Operating Rooms
            </button>
            <button 
                wire:click="setActiveTab('departments')"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm ml-8 {{ $activeTab === 'departments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Departments
            </button>
        </nav>
    </div>

    <!-- Content Area -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">{{ $this->getActiveTitle() }}</h2>
                @if(!in_array($activeTab, ['operating-rooms', 'departments']))
                    <button 
                        wire:click="openCreateModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Add New
                    </button>
                @endif
            </div>
        </div>

        <div class="px-6 py-4">
            @if(in_array($activeTab, ['operating-rooms', 'departments']))
                <!-- Database Model Tables -->
                @if($activeTab === 'operating-rooms')
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($operatingRooms as $room)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $room->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $room->description ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $room->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $room->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No operating rooms found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($activeTab === 'departments')
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($departments as $department)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $department->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $department->description ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">No departments found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            @else
                <!-- Array-based Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->getActiveData() as $index => $item)
                        <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900">{{ $item }}</span>
                            <div class="flex space-x-2">
                                <button 
                                    wire:click="openEditModal({{ $index }}, {{ json_encode($item) }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                    Edit
                                </button>
                                <button 
                                    wire:click="openDeleteModal({{ $index }}, {{ json_encode($item) }})"
                                    class="text-red-600 hover:text-red-800 text-sm">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(empty($this->getActiveData()))
                    <div class="text-center py-8">
                        <p class="text-gray-500">No items found. Add your first item to get started.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Add New {{ $this->getActiveTitle() }}</h3>
                    
                    <div class="mb-4">
                        <label for="newValue" class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                        <input 
                            type="text" 
                            id="newValue"
                            wire:model="newValue"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter value...">
                        @error('newValue') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button 
                            wire:click="resetModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button 
                            wire:click="create"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit {{ $this->getActiveTitle() }}</h3>
                    
                    <div class="mb-4">
                        <label for="editingValue" class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                        <input 
                            type="text" 
                            id="editingValue"
                            wire:model="editingValue"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('editingValue') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button 
                            wire:click="resetModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button 
                            wire:click="update"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Are you sure you want to delete "{{ $deleteItem['value'] ?? '' }}"? This action cannot be undone.
                    </p>

                    <div class="flex justify-center space-x-3">
                        <button 
                            wire:click="resetModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button 
                            wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
