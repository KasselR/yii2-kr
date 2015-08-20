<?php
namespace common\validators;
 
use yii\validators\Validator;
 
class EmbedDocValidator extends Validator
{
    public $scenario;
    public $model;
 
    /**
     * Validates a single attribute.
     * Child classes must implement this method to provide the actual validation logic.
     *
     * @param \yii\mongodb\ActiveRecord $object the data object to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    public function validateAttribute($object, $attribute)
    {
		$attr = $object->{$attribute};
        if (is_array($attr)) {
            $model = new $this->model;
            if($this->scenario){
                $model->scenario = $this->scenario;
            }
            $model->attributes = $attr;
			
            if (!$model->validate()) {
                foreach ($model->getErrors() as $errorAttr) {
                    foreach ($errorAttr as $value) {
                        $this->addError($object, $attribute, $value);
                    }
                }
            }
        } else {
            $this->addError($object, $attribute, 'should be an array');
        }
    }
 
}
