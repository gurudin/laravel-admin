<?php

namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gurudin\LaravelAdmin\Support\Helper;
use Gurudin\LaravelAdmin\Models\User;
use Gurudin\LaravelAdmin\Models\AuthItem;
use Gurudin\LaravelAdmin\Models\AuthAssignment;

class AssignmentController extends Controller
{
    /**
     * (view) Assignment index
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\LaravelAdmin\Models\User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, User $user)
    {
        $users = $user->getUser();
        foreach ($users as &$user) {
            $user['admin'] = in_array($user['email'], config('admin.admin_email'));
        }
        unset($user);

        return view('admin::assignment.index', compact('users'));
    }

    /**
     * (view) Assignment view
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment
     * @param Gurudin\LaravelAdmin\Models\User $user
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(
        Request $request,
        AuthItem $authItem,
        AuthAssignment $authAssignment,
        User $user,
        string $id
    ) {
        $assignments          = $authAssignment->getAuthAssignment($id);
        $items['permissions'] = $authItem->getPermission();
        $items['roles']       = $authItem->getRole();
        $userDetail           = $user->getUser($id);

        return view('admin::assignment.view', compact('userDetail', 'items', 'assignments'));
    }

    /**
     * (post) Batch create Assignments.
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment
     * 
     * @return Json
     */
    public function batchCreateAssignment(Request $request, AuthAssignment $authAssignment)
    {
        return $authAssignment->createAuthAssignments($request->all())
            ? $this->response(true)
            : $this->response(false);
    }

    /**
     * (post) Batch remove Assignments.
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment
     * 
     * @return Json
     */
    public function batchRemoveAssignment(Request $request, AuthAssignment $authAssignment)
    {
        return $authAssignment->removeAuthAssignments($request->all())
            ? $this->response(true)
            : $this->response(false);
    }
}
