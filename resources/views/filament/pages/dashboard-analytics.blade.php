<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h2 class="text-lg font-bold mb-2">Total Posts</h2>
            <p class="text-3xl font-extrabold text-blue-600">{{ $postCount }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h2 class="text-lg font-bold mb-2">Total Comments</h2>
            <p class="text-3xl font-extrabold text-green-600">{{ $commentCount }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <h2 class="text-lg font-bold mb-2">Total Users</h2>
            <p class="text-3xl font-extrabold text-purple-600">{{ $userCount }}</p>
        </div>
    </div>
</x-filament::page>
