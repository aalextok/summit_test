<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

/**
 * For debugging data
 */
function pre ($data, $title = '') {
  echo '<b>' . $title . '</b>';
  echo '<pre>' . print_r($data, true) . '</pre>';
}
