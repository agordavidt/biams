<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container mx-auto p-4"> <div class="bg-white shadow-lg rounded-lg p-6"> <h1 class="text-3xl font-bold text-gray-800 mb-4">LGA Administrator Dashboard</h1> <h2 class="text-xl text-indigo-600 mb-6">Welcome, {{ Auth::user()->name }} ({{ $lgaName }})</h2>

    <p class="text-gray-600 mb-8">
        You are logged in as an LGA Administrator. From here, you manage farmer registrations and resource distribution manifests specific to the {{ $lgaName }} Local Government Area.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-4 bg-indigo-50 border-l-4 border-indigo-500 rounded-lg shadow-sm">
            <p class="text-sm font-medium text-indigo-700">Farmers Registered</p>
            <p class="text-2xl font-bold text-indigo-900 mt-1">1,200</p>
        </div>
        <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
            <p class="text-sm font-medium text-green-700">Pending Approvals</p>
            <p class="text-2xl font-bold text-green-900 mt-1">45</p>
        </div>
        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg shadow-sm">
            <p class="text-sm font-medium text-yellow-700">Upcoming Distribution</p>
            <p class="text-2xl font-bold text-yellow-900 mt-1">Fertilizer Manifest</p>
        </div>
    </div>

    <div class="mt-8 border-t pt-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">Quick Actions</h3>
        <div class="flex space-x-4">
            <a href="#" class="px-4 py-2 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 transition duration-150 shadow-md">
                Register New Farmer
            </a>
            <a href="#" class="px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition duration-150 shadow-md">
                View Manifests
            </a>
        </div>
    </div>
</div>

</body>
</html>