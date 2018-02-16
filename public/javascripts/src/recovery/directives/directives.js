recoveryModule.directive('otOpenModal', function () {
    return {
        restrict: 'A',
        link: function (scope, elem, attrs) {
            if (attrs.otOpenModal) {
                scope.modal = $('#'+attrs.otOpenModal);
                elem.bind('click', function () {
                    scope.modal.modal();
                });
            }
        },
    }
});

recoveryModule.directive('otCloseModal', function () {
    return {
        scope: {
            cond: '='
        },
        restrict: 'A',
        link: function (scope, elem, attrs) {
            if (attrs.otCloseModal) {
                scope.modal = $('#'+attrs.otCloseModal);
                elem.bind('click', function () {
                    if (!angular.isDefined(scope.cond) || scope.cond) {
                        scope.modal.modal('hide');
                    }
                });
            }
        },
    }
});

recoveryModule.directive('otTypeahead', function () {
    return {
        restrict: 'A',
        replace: true,
        scope: {
            list: '='
        },
        link: function (scope, elem, attrs) {
            var options = {
                source: scope.list
            }
            elem.typeahead(options);
        }
    }
});

recoveryModule.directive('oqInput', function () {
    return {
        restrict: 'A',
        replace: true,
        scope: {
            model: '=',
            name: '@',
            label: '@',
            required: '@',
            maxLength: '@',
        },
        template: '<div>'+
        '<label for="{{name}}" class="control-label">{{label}}</label>'+
        '<div class="controls">'+
        '<input ng-required="{{required || false}}" ng-maxlength="{{maxLength || false}}" ng-model="model" type="text" name="{{name}}" id="{{name}}" value="" placeholder="{{placeholder || label}}">'+
        '</div>'+
        '</div>',
        link: function (scope, elem, attrs) {
                     // var input = elem.find('input');
                     // console.log(attrs.required);
                     // if (attrs.required) {
                     //    input.attr('required',"true");
                     //    $scope.apply();
                     // }
                    //scope.label = attrs.label;
                },
            }
        });