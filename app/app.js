angular.module('reminderApp', [])
    .controller('RemindersController', function ($scope, $http) {

        //var newRemind = '';
        function updateData() {
            $http.get('backend.php?method=index').then(function(response) {
                console.log('Result from method index----');
                console.log(response.data);
                $scope.items = response.data;
            });
        }

        function updateItem(item) {
            $http.post('backend.php?method=update', item);
        }

        $scope.addRemind = function (item, level) {
            var object = {
                name: item,
                level: level,
                checked: false
            };

            $http.post('backend.php?method=add', object).then(function(result) {
                console.log(result);
                updateData();
            });

            $scope.newRemind = '';
        };

        $scope.deleteChecked = function() {
            $scope.items = _.filter($scope.items, function(item){
                return item.checked == false;
            })
        };

        $scope.setState = function(item,g ) {
            item.checked = g;
            updateItem(item);
        };

        $scope.activeOnly = function() {
            var cnt = _.filter($scope.items, function(item){
                return item.checked == true;
            });

            return cnt.length;
        };

        $scope.items = [];
        updateData();


    });