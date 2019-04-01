<?php

namespace App\Core\ModelBinding;

class ModelBinders
{
    /**
     * @var ModelBinderDictionary
     */
    private static $binders;

    public static function getBinders() : ModelBinderDictionary
    {
        if (!isset(ModelBinders::$binders))
            ModelBinders::$binders = new ModelBinderDictionary();
        return ModelBinders::$binders ?? ModelBinders::$binders = new ModelBinderDictionary();
    }
}