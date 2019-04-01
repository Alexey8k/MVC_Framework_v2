angular.module('adminApp').controller('editBookController', function ($scope, $http, $document, $timeout) {
    let selects = {
        authors : $document.find('#authors'),
        genres : $document.find('#genres')
    };

    $scope.defaultDictionaries = {
        authors : [],
        genres : []
    };

    $scope.dictionaries = {
        authors : [],
        genres : []
    };

    $scope.addFromDictionary = {
        authors : undefined,
        genres : undefined
    };

    $scope.deleteOptionsFromSelect = {
        authors : undefined,
        genres : undefined
    };

    $scope.foo = function () {
      console.log($scope.defaultDictionaries.authors);
    };

    $scope.select2Option = {
        authors : {placeholder: 'Add author'},
        genres : {placeholder: 'Add genre'}
    };

    Object.keys($scope.defaultDictionaries).forEach((key) => {
        if ($scope.defaultDictionaries.hasOwnProperty(key)) {
            $http({
                url: '/dictionary/' + key,
                method: 'GET',
            }).then(function success(response) {
                $scope.defaultDictionaries[key] = response.data;
                let values = getSelectValues(key);
                $scope.dictionaries[key] = $scope.defaultDictionaries[key]
                    .filter((el) => !values.includes(el.id.toString()));
            });
        }
    });

    $scope.addToSelect = function (selectName) {
        let index = $scope.dictionaries[selectName]
            .findIndex((item) => item.id === parseInt($scope.addFromDictionary[selectName]));

        if (index !== -1) {
            let addedParam = $scope.dictionaries[selectName].splice(index, 1)[0];
            addOption(selectName, addedParam.id, addedParam.name);
            $timeout(() => {
                $scope.addFromDictionary[selectName] = undefined;
                $scope.$digest();
            });
            return;
        }

        let newParam = document.getElementById('select-dictionary-' + selectName)
            .selectedOptions[0].value;
        $document.find("#select-dictionary-" + selectName + " option[data-select2-tag='true']").remove();
        $scope.addFromDictionary[selectName] = undefined;
        addOption(selectName, newParam, newParam);
    };

    $scope.deleteFromSelect = function (selectName) {
        let deleteOptionsFromSelect = $scope.deleteOptionsFromSelect[selectName];
        deleteOptionsFromSelect.forEach((value) => {
            Array.apply(null, selects[selectName][0].options)
                .find((option) => option.value === value).remove();
        });
        $scope.dictionaries[selectName] = $scope.dictionaries[selectName]
            .concat(deleteOptionsFromSelect.map((value) => $scope.defaultDictionaries[selectName].find((el) => el.id === parseInt(value))));
        $scope.deleteOptionsFromSelect[selectName] = undefined;

    };

    $scope.preSubmit = function () {
        Object.keys(selects).forEach((key) => {

            Array.apply(null, selects[key][0].options)
                .forEach((option) => option.selected = 'selected');
        });
    };

    function getSelectValues(selectName) {
        return Array.apply(null,selects[selectName][0].options)
            .map((option) => option.value);
    }

    function getSelectLabels(selectName) {
        return Array.apply(null,selects[selectName][0].options)
            .map((option) => option.label);
    }

    function addOption(selectName, value, label) {
        let values = getSelectValues(selectName);
        let labels = getSelectLabels(selectName);
        if (!values.includes(value) && !labels.includes(label))
            selects[selectName].append($('<option></option>').attr('value',value).text(label));
    }
});