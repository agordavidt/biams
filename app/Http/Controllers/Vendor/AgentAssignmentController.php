<?php




namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\AgentResourceAssignment;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentAssignmentController extends Controller
{
    /**
     * Show resource assignment interface
     */
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        // Get all distribution agents for this vendor
        $agents = User::where('vendor_id', $vendor->id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'Distribution Agent');
            })
            ->with(['assignedResources' => function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            }])
            ->get();

        // Get all vendor resources
        $resources = $vendor->resources()
            ->where('status', 'active')
            ->with('assignedAgents')
            ->get();

        return view('vendor.team.assignments', compact('vendor', 'agents', 'resources'));
    }

    /**
     * Assign resource to agent
     */
    public function assign(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
            'resource_id' => 'required|exists:resources,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify agent belongs to vendor
        $agent = User::findOrFail($validated['agent_id']);
        if ($agent->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Invalid agent'], 403);
        }

        // Verify resource belongs to vendor
        $resource = Resource::findOrFail($validated['resource_id']);
        if ($resource->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Invalid resource'], 403);
        }

        try {
            AgentResourceAssignment::updateOrCreate(
                [
                    'agent_id' => $validated['agent_id'],
                    'resource_id' => $validated['resource_id'],
                ],
                [
                    'assigned_by' => $user->id,
                    'assigned_at' => now(),
                    'is_active' => true,
                    'notes' => $validated['notes'] ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Resource assigned successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Assignment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unassign resource from agent
     */
    public function unassign(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
            'resource_id' => 'required|exists:resources,id',
        ]);

        $assignment = AgentResourceAssignment::where('agent_id', $validated['agent_id'])
            ->where('resource_id', $validated['resource_id'])
            ->first();

        if (!$assignment) {
            return response()->json(['error' => 'Assignment not found'], 404);
        }

        // Verify ownership
        $agent = User::find($validated['agent_id']);
        if ($agent->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $assignment->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unassignment failed'
            ], 500);
        }
    }

    /**
     * Bulk assign resources to agent
     */
    public function bulkAssign(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
            'resource_ids' => 'required|array',
            'resource_ids.*' => 'exists:resources,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['resource_ids'] as $resourceId) {
                AgentResourceAssignment::updateOrCreate(
                    [
                        'agent_id' => $validated['agent_id'],
                        'resource_id' => $resourceId,
                    ],
                    [
                        'assigned_by' => $user->id,
                        'assigned_at' => now(),
                        'is_active' => true,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($validated['resource_ids']) . ' resources assigned successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'error' => 'Bulk assignment failed'
            ], 500);
        }
    }
}