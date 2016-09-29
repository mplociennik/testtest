# testtest

app.controller('AdminCtrl', ['$scope', '$timeout', 'Permissions', 'User', 'Roles', 'PermissionsGroups',
    function ($scope, $timeout, Permissions, User, Roles, PermissionsGroups) {

        $scope.usersLoading = true;
        $scope.permissionsLoading = true;
        $scope.rolesLoading = false;
        $scope.allRolesLoading = true;
        $scope.allPermissionsGroupsLoading = true;
        $scope.tabTypes = [];
        $scope.tabTypes.case = 'case';
        $scope.tabTypes.email = 'email';
        $scope.tabTypes.phone = 'phone';
        $scope.tabTypes.messenger = 'messenger';
        $scope.permissionsAccordion = {
            userRoles: true,
            permissionsForUser: true,
            permissionsForRoles: true
        };
        $scope.permissionsGroups = [];
        $scope.users = [];
        $scope.roles = [];
        $scope.rolePermissions = [];
        $scope.adminSelectedUser = '';
        $scope.adminSelectedRole = '';
        $scope.selectedPermissions = [];

        $scope.adminSelectedUserRoles = {};
        $scope.getAllUsers = function () {
            User.getAllUsers(function (callback) {
                $scope.users = callback.data;
                $timeout(function () {
                    $scope.usersLoading = false;
                    $('.selectpicker').selectpicker('render');
                }, 1100);
            });
        };

        $scope.permissionsSearchUser = function (userId) {
            Permissions.getAllPermissionsWithUserChecked(userId, function (callback) {
                $scope.allPermissionsWithUserChecked = callback.data;
                $scope.permissionsLoading = false;
            });
        };

        $scope.rolesSearchUser = function (userId) {
            Roles.getAllRolesWithUserChecked(userId, function (callback) {
                $scope.allRolesWithUserChecked = callback.data;
                $scope.rolesLoading = false;
                $('.selectpicker').selectpicker('refresh');
            });
        };

        $scope.getPermissionsWithUserArray = function (adminSelectedUser) {
            $scope.permissionsSearchUser(adminSelectedUser);
            $scope.rolesSearchUser(adminSelectedUser);
        };

        $scope.getAllRoles = function () {
            Roles.getAllRoles(function (callback) {
                $scope.roles = callback.data;
                $scope.allRolesLoading = false;
            });
        };
        
        $scope.getAllPermissionsGroups = function () {
            PermissionsGroups.getAll(function (callback) {
                $scope.permissionsGroups = callback.data;
                $scope.allPermissionsGroupsLoading = false;
            });
        };

        $scope.getRolePermissions = function (role) {

            var rolePermissions = [];
            angular.forEach($scope.roles, function (value, key) {
                if (parseInt(role) === value.id) {
                    rolePermissions = value.permissions;
                }
            });
            $scope.rolePermissions = rolePermissions;
            $('.selectpicker').selectpicker('refresh');
            return false;
        };

        $scope.saveUserRoles = function (userId, roles) {
            var rolesIds = $scope.getCheckedIds(roles);

            if (rolesIds.length > 0) {
                User.storeUserRoles(userId, rolesIds, function (callback) {
                    $scope.notificationSuccess('Roles has been updated!');
                    $scope.getPermissionsWithUserArray(userId);
                });
            }
        };

        $scope.saveUserPermissions = function (userId) {
            var permissionsIds = $scope.getCheckedIds($scope.allPermissionsWithUserChecked);
            User.storeUserPermissions(userId, permissionsIds, function (callback) {
                $scope.notificationSuccess(callback.data);
                $scope.getPermissionsWithUserArray(userId);
            });

        };

        $scope.saveRolePermissions = function (roleId) {
            var permissionsIds = $scope.getCheckedIds($scope.rolePermissions);
            Roles.storeRolePermissions(roleId, permissionsIds, function (callback) {
                $scope.notificationSuccess(callback.data);
                $scope.getAllRoles();
            });

        };

        $scope.getCheckedIds = function (records) {
            var ids = [];
            angular.forEach(records, function (value, key) {
                if (value.checked === true) {
                    ids.push(value.id);
                }
            });
            return ids;
        };

        $scope.setUserPermissionChecked = function (itemId) {
            angular.forEach($scope.allPermissionsWithUserChecked, function (value, key) {
                if (value.id === itemId) {
                    $scope.allPermissionsWithUserChecked[key]['checked'] = $scope.allPermissionsWithUserChecked[key]['checked'] ? false : true;
                }
            });
        };

        $scope.setRolePermissionChecked = function (itemId) {
            angular.forEach($scope.rolePermissions, function (value, key) {
                if (value.id === itemId) {
                    $scope.rolePermissions[key]['checked'] = $scope.rolePermissions[key]['checked'] ? false : true;
                }
            });
        };

        $scope.getAdminPanelData = function () {
            $timeout(function () {
                $scope.getAllUsers();
                $scope.getAllRoles();
                $scope.getAllPermissionsGroups();
            }, 1000);
        };

        $scope.getAdminPanelData();

    }
]);
