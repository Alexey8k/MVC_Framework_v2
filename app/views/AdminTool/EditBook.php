<?php
/**
 * @var App\Models\Book $model
 * @var array $viewBag
 */
$layout = 'app/views/Shared/_AdminLayout.php';
$viewBag['title'] = !$model->id ? 'Add new book' : "Edie $model->name";
?>

<h1><?= !$model->id ? 'Add new book' : "Edie $model->name" ?></h1>

<form class="form-horizontal" enctype="multipart/form-data" method="post"
      action="<?= App\Core\Url\Url::action('EditBook', 'AdminTool') ?>"
      ng-submit="preSubmit()"
      ng-controller="editBookController">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-5">
            <input id="name" name="name" class="form-control" value="<?= $model->name ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Description</label>
        <div class="col-sm-5">
            <textarea id="description" name="description"
                      cols="40" rows="5" class="form-control"><?= $model->description ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="authors" class="col-sm-2 control-label">Authors</label>
        <div class="col-sm-5">
            <select id="authors" name="authors[]" class="form-control" size="4" multiple
                    ng-model="deleteOptionsFromSelect.authors">
                <?php foreach ($model->authors ?? [] as $author) : ?>
                    <option value="<?= $author->id ?>"><?= $author->name ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
                <input type="button" value="Add" class="btn btn-sm btn-primary col-sm-12"
                       ng-click="addToSelect('authors')"
                       ng-disabled="addFromDictionary.authors|isDisabled"
                />
            </div>
            <div class="form-group">
                <input type="button" value="Delete" class="btn btn-sm btn-primary col-sm-12"
                       ng-click="deleteFromSelect('authors')"
                       ng-disabled="!deleteOptionsFromSelect.authors || !deleteOptionsFromSelect.authors.length" />
            </div>
        </div>
        <div class="col-sm-4">
            <select ui-select2="select2Option.authors" id="select-dictionary-authors" class="select-author form-control"
                    ng-model="addFromDictionary.authors">
                <option value=""></option>
                <option ng-repeat="item in dictionaries.authors" value="{{ item.id }}">{{ item.name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="genres" class="col-sm-2 control-label">Genres</label>
        <div class="col-sm-5">
            <select id="genres" name="genres[]" class="form-control" size="4" multiple
                    ng-model="deleteOptionsFromSelect.genres">
                <?php foreach ($model->genres ?? [] as $genre) : ?>
                    <option value="<?= $genre->id ?>"><?= $genre->name ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
                <input type="button" value="Add" class="btn btn-sm btn-primary col-sm-12"
                       ng-click="addToSelect('genres')"
                       ng-disabled="addFromDictionary.genres|isDisabled"
                />
            </div>
            <div class="form-group">
                <input type="button" value="Delete" class="btn btn-sm btn-primary col-sm-12"
                       ng-click="deleteFromSelect('genres')"
                       ng-disabled="!deleteOptionsFromSelect.genres || !deleteOptionsFromSelect.genres.length" />
            </div>
        </div>
        <div class="col-sm-4">
            <select ui-select2="select2Option.genres" id="select-dictionary-genres" class="select-genre form-control"
                    ng-model="addFromDictionary.genres" >
                <option value=""></option>
                <option ng-repeat="item in dictionaries.genres" value="{{ item.id }}"> {{ item.name }}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">Price</label>
        <div class="col-sm-5">
            <input name="price" class="form-control" value="<?= $model->price ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-lg-offset-2">
            <input type="submit" value="Save" class="btn btn-sm btn-primary" />
            <?= App\Core\Html\Html::actionLink(
                    'Cancel',
                    'Index',
                    'AdminTool',
                    null,
                    ['class'=>"btn btn-sm btn-default"]) ?>
        </div>
    </div>
    <input type="hidden" name="id" value="<?= $model->id ?>" />
</form>
