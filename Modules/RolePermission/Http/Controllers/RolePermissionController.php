<?php

namespace Modules\RolePermission\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Modules\RolePermission\Http\Requests\RolePermissionRequest;
use Modules\RolePermission\Repositories\RolePermissionRepo;
use Modules\RolePermission\Services\RolePermissionService;
use Modules\Share\Http\Controllers\Controller;
use Modules\Share\Responses\AjaxResponses;
use Modules\Share\Services\ShareService;

class RolePermissionController extends Controller
{
    public RolePermissionRepo $repo;
    public RolePermissionService $service;

    public function __construct(RolePermissionService $rolePermissionService, RolePermissionRepo $permissionRepo)
    {
        $this->repo = $permissionRepo;
        $this->service = $rolePermissionService;
    }

    /**
     * Get latest roles.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $roles = $this->repo->index()->paginate(10);
        return view('RolePermission::index', compact('roles'));
    }

    /**
     * Create page for role.
     */
    public function create()
    {
        $permissions = $this->repo->getAllPermissions();
        return view('RolePermission::create', compact('permissions'));
    }

    /**
     * Store role with redirect.
     *
     * @param  RolePermissionRequest $request
     * @return RedirectResponse
     */
    public function store(RolePermissionRequest $request)
    {
        $this->service->store($request);

        return $this->successMessageWithRedirect('Create role');
    }

    /**
     * Edit role by id.
     *
     * @param  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $role = $this->repo->findById($id);
        $permissions = $this->repo->getAllPermissions();

        return view('RolePermission::edit', compact(['role', 'permissions']));
    }

    /**
     * Update role by id.
     *
     * @param  RolePermissionRequest $request
     * @param  $id
     * @return RedirectResponse
     */
    public function update(RolePermissionRequest $request, $id)
    {
        $this->service->update($request, $id);

        return $this->successMessageWithRedirect('Update role');
    }

    /**
     * Delete role by id.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repo->delete($id);
        return AjaxResponses::SuccessResponse();
    }

    /**
     * Show success message with redirect.
     *
     * @param  string $title
     * @return RedirectResponse
     */
    private function successMessageWithRedirect(string $title)
    {
        ShareService::successToast($title);
        return to_route('role-permissions.index');
    }
}