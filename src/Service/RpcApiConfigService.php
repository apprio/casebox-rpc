<?php

namespace Casebox\RpcBundle\Service;

/**
 * Class RpcApiConfigService
 */
class RpcApiConfigService
{
    /**
     * @return array
     */
    public function getConfig()
    {
        $api = [
            'CB_Browser' => [
                'methods' => [
                    'paste' => ['len' => 1],
                    'saveFile' => ['len' => 1, 'formHandler' => true],
                    'confirmUploadRequest' => ['len' => 1],
                    'delete' => ['len' => 1],
                    'restore' => ['len' => 1],
                    'getObjectsForField' => ['len' => 1],
                ],
            ],
            'CB_Browser_Actions' => [
                'methods' => [
                    'copy' => ['len' => 1],
                    'move' => ['len' => 1],
                    'shortcut' => ['len' => 1],
                ],
            ],
            'CB_BrowserTree' => [
                'methods' => [
                    'getChildren' => ['len' => 1],
                    'delete' => ['len' => 1],
                    'rename' => ['len' => 1],
                    'getRootProperties' => ['len' => 1],
                ],
            ],
            'CB_BrowserView' => [
                'methods' => [
                    'getChildren' => ['len' => 1],
                    'delete' => ['len' => 1],
                    'rename' => ['len' => 1],
                ],
            ],
            'CB_Favorites' => [
                'methods' => [
                    'create' => ['len' => 1],
                    'read' => ['len' => 1],
                    'delete' => ['len' => 1],
                ],
            ],
            'CB_Files' => [
                'methods' => [
                    'getProperties' => ['len' => 1],
                    'getContent' => ['len' => 1],
                    'saveContent' => ['len' => 1],
                    'restoreVersion' => ['len' => 1],
                    'deleteVersion' => ['len' => 1],
                    'checkExistentContents' => ['len' => 1],
                    'saveProperties' => ['len' => 1],
                ],
            ],
            'CB_Notifications' => [
                'methods' => [
                    'getList' => ['len' => 1],
                    'getNew' => ['len' => 1],
                    'updateLastSeenActionId' => ['len' => 1],
                    'markAsRead' => ['len' => 1],
                    'markAllAsRead' => ['len' => 0],
                ],
            ],
            'CB_Objects' => [
                'methods' => [
                    'load' => ['len' => 1],
                    'create' => ['len' => 1],
                    'save' => ['len' => 1, 'formHandler' => true],
                    'getAssociatedObjects' => ['len' => 1],
                    'getPluginsData' => ['len' => 1],
                    'getBasicInfoForId' => ['len' => 1],
                    'setSubscription' => ['len' => 1],
                    'addComment' => ['len' => 1],
                    'updateComment' => ['len' => 1],
                    'removeComment' => ['len' => 1],
                    'setOwnership' => ['len' => 1],
                ],
            ],
            'CB_Objects_Plugins_Comments' => [
                'methods' => [
                    'loadMore' => ['len' => 1],
                ],
            ],
            'CB_Path' => [
                'methods' => [
                    'getPath' => ['len' => 1]
                    ,
                    'getPidPath' => ['len' => 1],
                ],
            ],
            'CB_Security' => [
                'methods' => [
                    'getUserGroups' => ['len' => 1],
                    'createUserGroup' => ['len' => 1],
                    'updateUserGroup' => ['len' => 1],
                    'destroyUserGroup' => ['len' => 1],
                    'searchUserGroups' => ['len' => 1],
                    'getObjectAcl' => ['len' => 1],
                    'addObjectAccess' => ['len' => 1],
                    'updateObjectAccess' => ['len' => 1],
                    'destroyObjectAccess' => ['len' => 1],
                    'setInheritance' => ['len' => 1],
                    'getActiveUsers' => ['len' => 1],
                    'removeChildPermissions' => ['len' => 1],
                ],
            ],
            'CB_System' => [
                'methods' => [
                    'getCountries' => ['len' => 0],
                    'getTimezones' => ['len' => 0],
                ],
            ],
            'CB_Search' => [
                'methods' => [
                    'load' => ['len' => 1],
                    'query' => ['len' => 1],
                ],
            ],
            'CB_State_DBProvider' => [
                'methods' => [
                    'read' => ['len' => 0],
                    'set' => ['len' => 1],
                    'saveGridViewState' => ['len' => 1],
                ],
            ],
            'CB_Tasks' => [
                'methods' => [
                    'setUserStatus' => ['len' => 1],
                    'complete' => ['len' => 1],
                    'close' => ['len' => 1],
                    'reopen' => ['len' => 1],
                    'updateDates' => ['len' => 1],
                ],
            ],
            'CB_Templates' => [
                'methods' => [
                    'readAll' => ['len' => 1],
                    'getTemplatesStructure' => ['len' => 0],
                    'updateSolrData' => ['len' => 1],
                ],
            ],
            'CB_User' => [
                'methods' => [
                    'getLoginInfo' => ['len' => 0],
                    'login' => ['len' => 2],
                    'logout' => ['len' => 0],
                    'setLanguage' => ['len' => 1],
                    'setTheme' => ['len' => 1],
                    'uploadPhoto' => ['len' => 1, 'formHandler' => true],
                    'removePhoto' => ['len' => 1],
                    'getAccountData' => ['len' => 0],
                    'getProfileData' => ['len' => 1],
                    'saveProfileData' => ['len' => 1],
                    'saveSecurityData' => ['len' => 1],
                    'verifyPassword' => ['len' => 1],
                    'verifyPhone' => ['len' => 1],
                    'getTSVTemplateData' => ['len' => 1],
                    'enableTSV' => ['len' => 1],
                    'disableTSV' => ['len' => 0],
                    'getNotificationSettings' => ['len' => 0],
                    'setNotificationSettings' => ['len' => 1],
                ],
            ],
            'CB_UsersGroups' => [
                'methods' => [
                    'getChildren' => ['len' => 1],
                    'getUserData' => ['len' => 1],
                    'getAccessData' => ['len' => 1],
                    'saveAccessData' => ['len' => 1],
                    'addUser' => ['len' => 1],
                    'associate' => ['len' => 2],
                    'deassociate' => ['len' => 2],
                    'deleteUser' => ['len' => 1],
                    'changePassword' => ['len' => 1, 'formHandler' => true],
                    'sendResetPassMail' => ['len' => 1],
                    'renameUser' => ['len' => 1],
                    'renameGroup' => ['len' => 1],
                    'disableTSV' => ['len' => 1],
                    'setUserEnabled' => ['len' => 1],
                ],
            ],
        ];

        \Casebox\CoreBundle\Service\Cache::set('ExtDirectAPI', $api);

        return $api;
    }
}
