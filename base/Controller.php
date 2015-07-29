<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

use Yii;
use hiqdev\hiart\ActiveRecord;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class Controller extends \yii\web\Controller
{
    /**
     * @var array internal actions.
     */
    protected $_internalActions;

    /**
     * @inheritdoc
     */
    public function behaviors () {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $submodel the submodel that will be added to the ClassName
     * @return string Main Model class name
     */
    static public function modelClassName () {
        $parts = explode('\\', static::className());
        $last  = array_pop($parts);
        array_pop($parts);
        $parts[] = 'models';
        $parts[] = substr($last, 0, -10);

        return implode('\\', $parts);
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @returns ActiveRecord
     */
    static public function newModel ($config = [], $submodel = '') {
        $config['class'] = static::modelClassName().$submodel;
        return \Yii::createObject($config);
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @returns ActiveRecord Search Model object
     */
    static public function searchModel ($config = []) {
        return static::newModel($config, 'Search');
    }

    /**
     * @returns string main model's formName()
     */
    static public function formName () {
        return static::newModel()->formName();
    }

    /**
     * @returns string search model's formName()
     */
    static public function searchFormName () {
        return static::newModel()->formName() . 'Search';
    }

    /**
     * @param string $separator
     * @return string Main model's camel2id'ed formName()
     */
    static public function modelId ($separator = '-') {
        return Inflector::camel2id(static::formName(), $separator);
    }

    static public function moduleId()
    {
        return explode('\\', get_called_class())[2];
    }

    static public function controllerId()
    {
        return strtolower(substr(explode('\\', get_called_class())[4], 0, -10));
    }

    /**
     * @param int|array $condition scalar ID or array to be used for searching
     * @param array $config config to be used to create the [[Model]]
     * @return array|ActiveRecord|null|static
     * @throws NotFoundHttpException
     */
    static public function findModel ($condition, $config = []) {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $model = static::newModel($config)->findOne(is_array($condition) ? $condition : ['id'=>$condition]);
        if ($model === null) {
            throw new NotFoundHttpException('The requested object not found.');
        };

        return $model;
    }

    static public function findModels($condition, $config = [])
    {
        $int_keys = array_sum(array_map(function ($v) { return is_numeric($v) ? 1 : 0; }, array_keys($condition)));
        if (!is_array($condition) || $int_keys) {
            $condition = ['id' => $condition];
        }
        $models = static::newModel($config)->find()->where($condition)->all();
        if ($models === null) {
            throw new NotFoundHttpException('The requested object not found.');
        };

        return $models;
    }

    static public function renderJson ($data) {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $data;
    }

    static public function renderJsonp ($data) {
        \Yii::$app->response->format = Response::FORMAT_JSONP;

        return $data;
    }

    public function actionIndex () {
        return $this->render('index');
    }

    public function setInternalAction($id, $action)
    {
        $this->_internalActions[$id] = $action;
    }

    public function hasInternalAction($id)
    {
        return array_key_exists($id, $this->_internalActions);
    }

    public function createAction($id)
    {
        $config = $this->_internalActions[$id];
        return $config ? Yii::createObject($config, [$id, $this]) : parent::createAction($id);
    }

    /**
     * Prepares array for building url to action based on given action id and parameters.
     *
     * @param string $action action id
     * @param string|int|array $params ID of object to be action'ed or array of parameters
     * @return array array suitable for Url::to
     */
    static public function getActionUrl ($action = 'index', $params = [])
    {
        $params = is_array($params) ? $params : ['id' => $params];
        return array_merge([implode('/', ['',static::moduleId(), static::controllerId(), $action])], $params);
    }

    /**
     * Prepares array for building url to search with given filters.
     */
    static public function getSearchUrl (array $params = [])
    {
        return static::getActionUrl('index', [static::searchFormName() => $params]);
    }
}
