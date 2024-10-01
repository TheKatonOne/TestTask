<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function storeOrganizationWithRelations(Request $request)
    {
        $organizationData = $request->all();

        // Store the organization
        $this->storeOrganization($organizationData['org_name'], $organizationData['daughters'] ?? []);

        return response()->json(['message' => 'Organization and relations stored successfully'], 201);
    }

    private function storeOrganization($name, $daughters)
    {
        // Store the organization if it doesn't already exist
        $organization = Organization::firstOrCreate(['name' => $name]);

        // Store each daughter (child)
        foreach ($daughters as $daughter) {
            $childOrg = $this->storeOrganization($daughter['org_name'], $daughter['daughters'] ?? []);
            // Create the parent-child relation
            $organization->children()->syncWithoutDetaching($childOrg->id);
        }

        return $organization;
    }


    public function getOrganizationRelations(Request $request, $name)
    {
        $name = str_replace('_', ' ', $name);

        $organization = Organization::where('name', $name)->firstOrFail();

        $parents = $organization->parents()->get();
        $parentRelations = $parents->isNotEmpty() ? $parents->map(function ($parent) {
            return [
                'relationship_type' => 'parent',
                'org_name' => $parent->name,
            ];
        }) : collect();

        $daughters = $organization->children()->get()->map(function ($child) {
            return [
                'relationship_type' => 'daughter',
                'org_name' => $child->name,
            ];
        });

        $sisters = collect();

        if ($parents->isNotEmpty()) {
            foreach ($parents as $parent) {
                $siblings = $parent->children()->where('organizations.id', '!=', $organization->id)->get();
                $sisters = $sisters->merge($siblings->map(function ($sibling) {
                    return [
                        'relationship_type' => 'sister',
                        'org_name' => $sibling->name,
                    ];
                }));
            }
        }

        // Combine all results and sort by org_name
        $relations = $parentRelations->merge($daughters)->merge($sisters)->sortBy('org_name')->values();

        // Pagination
        $perPage = $request->input('per_page', 100); // Number of items per page
        $currentPage = $request->input('page', 1); // Current page
        $totalCount = $relations->count(); // Total number of relations

        // Get the items for the current page
        $paginated = $relations->slice(($currentPage - 1) * $perPage, $perPage)->values();
        if ($relations->isNotEmpty()) {
            $response = [
                'data' => $paginated,
            ];
        } else {
            $response = ['message' => 'Organization is not related to other organizations.']; // Handle response if no relatives
        }


        // Check if there is a next page
        if ($currentPage * $perPage < $totalCount) {
            $response['next_page_url'] = url('/api/organizations/' . str_replace(' ', '_', $name) . '?page=' . ($currentPage + 1) . '&per_page=' . $perPage);
        }

        // Check if there is a previous page
        if ($currentPage > 1) {
            $response['prev_page_url'] = url('/api/organizations/' . str_replace(' ', '_', $name) . '?page=' . ($currentPage - 1) . '&per_page=' . $perPage);
        }

        return response()->json($response);
    }


}

