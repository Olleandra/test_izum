<?php
namespace app\components;

use yii\base\Widget;
use yii\base\Component;
use yii\base\ErrorHandler;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\Model;
use yii\web\JsExpression;
 
class HelloWidget extends Widget
{
	public $model;
	public $attribute;
	public $options = ['class' => 'form-group'];
    public $parts = [];
	public $labelOptions = ['class' => 'control-label'];
 
	public function init(){
		parent::init();
		dropDownList($items, $options = []);
	}
	
	public function dropDownList($items, $options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeDropDownList($this->model, $this->attribute, $items, $options);
        //return $this;
    }
 
	protected function adjustLabelFor($options)
    {
        if (!isset($options['id'])) {
            return;
        }
        $this->_inputId = $options['id'];
        if (!isset($this->labelOptions['for'])) {
            $this->labelOptions['for'] = $options['id'];
        }
    }
 
	public function run(){
		return $this;
	}
}
?>