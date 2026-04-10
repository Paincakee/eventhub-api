<?php

return [
    /*
     * Class for user model.
     */
    'user_model' => \Encore\BaseKit\Models\User::class,

    /*
     * Class for user resource.
     */
    'user_resource' => \Encore\BaseKit\Http\Resources\UserResource::class,

    /*
     * Class for store user request.
     */
    'store_user_request' => \Encore\BaseKit\Http\Requests\UserRequest::class,

    /*
     * Class for update user request.
     */
    'update_user_request' => \Encore\BaseKit\Http\Requests\UserRequest::class,

    /**
     * Class for the invitation mail.
     */
    'mail_invitation' => \Encore\BaseKit\Notifications\UserInvitation::class,

    /*
     * Class for all user roles.
     */
    'user_roles' => \Encore\BaseKit\Enums\UserRole::class,

    /**
     * All available permissions.
     */
    'permissions' => [
        'users' => ['view', 'create', 'update', 'delete'],
    ],
];
