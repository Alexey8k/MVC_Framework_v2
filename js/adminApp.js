angular.module("adminApp", ['ui.select2','ngMessages', 'ngAnimate', 'ngSanitize', 'ui.bootstrap'])
    // .config(function ($httpProvider) {
    //     // send all requests payload as query string
    //     $httpProvider.defaults.transformRequest = function (data) {
    //         if (data === undefined) {
    //             return data;
    //         }
    //         return jQuery.param(data);
    //     };
    //
    //     // set all post requests content type
    //     $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    // })
    .filter('isDisabled', function () {
        return (input) => input === undefined;
    })
    .run(['uiSelect2Config', function(uiSelect2Config) {
        uiSelect2Config.tags = true;
        uiSelect2Config.allowClear = true;
    }]);
