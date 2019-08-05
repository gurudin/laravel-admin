<?php

namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Gurudin\LaravelAdmin\Support\Helper;
use Gurudin\LaravelAdmin\Models\User;
use Gurudin\LaravelAdmin\Models\AuthItem;
use Gurudin\LaravelAdmin\Models\AuthAssignment;

class AssignmentController extends Controller
{
    /**
     * (view) Assignment index
     *
     * @param Illuminate\Http\Request          $request 
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
     * @param Illuminate\Http\Request                    $request 
     * @param Gurudin\Admin\Models\AuthItem              $authItem 
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment 
     * @param Gurudin\LaravelAdmin\Models\User           $user 
     * @param string                                     $id 
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

        return view(
            'admin::assignment.view',
            compact(
                'userDetail',
                'items',
                'assignments'
            )
        );
    }

    /**
     * (view) User edit.
     *
     * @param Illuminate\Http\Request          $request 
     * @param Gurudin\LaravelAdmin\Models\User $user 
     * @param string                           $id 
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editView(Request $request, User $user, string $id)
    {
        $detail = $user->getUser($id);

        return view('admin::assignment.edit', compact('detail'));
    }

    /**
     * (put) User update.
     *
     * @param Illuminate\Http\Request          $request 
     * @param Gurudin\LaravelAdmin\Models\User $user 
     *
     * @return Json
     */
    public function update(Request $request, User $user)
    {
        $req = $request->all();
        if ($req['type'] == 'nick') {
            if (strlen($req['data']['name']) < 2) {
                return $this->response(
                    false,
                    __('admin::messages.assignment.nick-length-error')
                );
            }

            if ($user->where(['id' => $req['data']['id']])->update(['name' => $req['data']['name']])) {
                return $this->response(true);
            } else {
                return $this->response(true, 'Failed to update.');
            }
        }

        if ($req['type'] == 'password') {
            if (strlen($req['data']['password']) < 6) {
                return $this->response(
                    false,
                    __('admin::messages.assignment.password-length-error')
                );
            }
            if ($req['data']['password'] != $req['data']['c_password']) {
                return $this->response(
                    false,
                    __('admin::messages.assignment.password-match-error')
                );
            }

            if ($user->where(['id' => $req['data']['id']])->update(['password' => bcrypt($req['data']['password'])])) {
                return $this->response(true);
            } else {
                return $this->response(true, 'Failed to update.');
            }
        }
    }

    /**
     * (post) Batch create Assignments.
     *
     * @param Illuminate\Http\Request                    $request 
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment 
     * 
     * @return Json
     */
    public function batchCreateAssignment(
        Request $request,
        AuthAssignment $authAssignment
    ) {
        Helper::removeCache('menu');

        return $authAssignment->createAuthAssignments($request->all())
            ? $this->response(true)
            : $this->response(false);
    }

    /**
     * (post) Batch remove Assignments.
     *
     * @param Illuminate\Http\Request                    $request 
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment 
     * 
     * @return Json
     */
    public function batchRemoveAssignment(
        Request $request,
        AuthAssignment $authAssignment
    ) {
        Helper::removeCache('menu');

        return $authAssignment->removeAuthAssignments($request->all())
            ? $this->response(true)
            : $this->response(false);
    }

    /**
     * (delete) Destroy user.
     *
     * @param Illuminate\Http\Request                    $request 
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment 
     * @param Gurudin\LaravelAdmin\Models\User           $user 
     *
     * @return Json
     */
    public function destroy(
        Request $request,
        AuthAssignment $authAssignment,
        User $user
    ) {
        return $user->removeUser($authAssignment, $request->all()['id'])
            ? $this->response(true)
            : $this->response(false, 'Failed to delete.');
    }
}
