<?php
use App\Core\Html\Html;
use App\Core\Url\Url;

$layout = 'app/views/Shared/_AdminLayout.php';
$viewBag['title'] = 'Все товары';
/**
 * @var App\Models\Book[] $model
 */
?>

<h1>All books</h1>
<table class="Grid">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th class="NumericCol">Price</th>
        <th>Action</th>
    </tr>
    <?php foreach ($model as $item) : ?>
        <tr>
            <td><?= $item->id ?></td>
            <td>
                <?= Html::actionLink($item->name, 'EditBook', 'AdminTool', ['id'=>$item->id]) ?>
            </td>
            <td class="NumericCol">
                <?=
                (new NumberFormatter( 'un_US', NumberFormatter::CURRENCY ))
                    ->formatCurrency( $item->price, "USD")
                ?>
            </td>
            <td style="width: 1%">
                <form action="<?= Url::action('DeleteBook', 'AdminTool') ?>" method="post">
                    <input type="hidden" name="id" value="<?= $item->id ?>" />
                    <input type="submit" value="Delete" class="btn btn-sm btn-primary" />
                </form>
            </td>
        </tr>
    <?php endforeach ?>
</table>
<p>
    <?= Html::actionLink('Add book', 'AddBook', 'AdminTool', null, ['class'=>'btn btn-sm btn-primary']) ?>
</p>