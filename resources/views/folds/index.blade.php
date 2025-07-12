@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Folds</h1>
        @if(auth()->user()->isAdmin() || auth()->user()->isCellLeader())
            <a href="{{ route('folds.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Add Fold</a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($folds->count() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden font-montserrat">
            <table class="min-w-full divide-y divide-gray-200 border border-[#3a1d09] rounded-lg shadow">
                <thead class="bg-[#f58502]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Cell</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Fold Leaders</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-[#3a1d09] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100">
                    @foreach($folds as $fold)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $fold->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $fold->description ?? 'No description' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $fold->cell->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($fold->foldLeader)
                                        <span class="font-semibold">Leader:</span> {{ $fold->foldLeader->name }}<br>
                                    @endif
                                    @if($fold->assistantLeader)
                                        <span class="font-semibold">Assistant:</span> {{ $fold->assistantLeader->name }}
                                    @endif
                                    @if(!$fold->foldLeader && !$fold->assistantLeader)
                                        <span class="text-gray-400">None</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fold->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $fold->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('folds.show', $fold) }}" class="text-orange-600 hover:text-orange-900 mr-3">View</a>
                                @if(auth()->user()->isAdmin() || (auth()->user()->isCellLeader() && auth()->user()->getLedCell()->id == $fold->cell_id))
                                    <a href="{{ route('folds.edit', $fold) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('folds.destroy', $fold) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this fold?')">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $folds->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">No folds found.</p>
            @if(auth()->user()->isAdmin() || auth()->user()->isCellLeader())
                <a href="{{ route('folds.create') }}" class="mt-4 inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Create your first fold</a>
            @endif
        </div>
    @endif
</div>
@endsection 